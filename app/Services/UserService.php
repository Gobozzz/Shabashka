<?php

namespace App\Services;

use App\Actions\User\IsFieldsUpdatedUserModeration;
use App\Enums\RolesUser;
use App\Enums\StatusUser;
use App\Models\User;
use App\Notifications\Admin\UserInModeration;
use App\Notifications\User\ModerationAccount;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class UserService
{
    public function __construct(public FileService $fileService)
    {
    }

    public function getByEmail(string $email): User|null
    {
        return User::where('email', $email)->first();
    }
    public function getByEmailPhone(string $text): User|null
    {
        return User::where('email', $text)->orWhere('phone', $text)->first();
    }

    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => $data['email_verified_at'] ?? null,
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'status' => StatusUser::MODERATION,
        ]);
        if ($user->role->value === RolesUser::WORKER->value) {
            $user->worker()->create([
                'user_id' => $user->getKey(),
            ]);
        } else if ($user->role->value === RolesUser::EMPLOYER->value) {
            $user->employer()->create([
                'user_id' => $user->getKey(),
            ]);
        }
        return $user;
    }

    public function updatePersonalData(array $data, User $user): bool
    {
        try {
            return DB::transaction(function () use ($data, $user): bool {
                $old_user_image = $user->image;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->phone = $data['phone'];
                $user->city_id = $data['city_id'];
                if (isset($data['image'])) {
                    $image = $this->downloadUserImage($data['image']);
                    if ($image) {
                        $user->image = $image['path'];
                    }
                }
                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                    $user->sendEmailVerificationNotification();
                }
                if ($user->isDirty('image') && $old_user_image) { // удаляем старую фотку
                    $this->fileService->removeFile($old_user_image);
                }
                if ((new IsFieldsUpdatedUserModeration())->handle($user)) {
                    $user->status = StatusUser::MODERATION;
                    Notification::route('mail', config('data_app.admin_email'))->notify(new UserInModeration($user));
                    $user->notify(new ModerationAccount());
                }
                return $user->save();
            });
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function downloadUserImage($image): array|null
    {
        return $this->fileService->downloadImage($image, 'users', ['width' => 256]);
    }

    public function updatePushSubscription(array $data, User $user): bool
    {
        try {
            $user->updatePushSubscription($data['endpoint'], $data['public_key'], $data['auth_token'], $data['encoding']);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function removePushSubscription(string $endpoint, User $user): bool
    {
        try {
            $user->deletePushSubscription($endpoint);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function blocked(User $user): bool
    {
        $user->status = StatusUser::BLOCKED;
        return $user->save();
    }

    public function actived(User $user): bool
    {
        $user->status = StatusUser::ACTIVE;
        return $user->save();
    }

    public function moderated(User $user): bool
    {
        $user->status = StatusUser::MODERATION;
        return $user->save();
    }

    public function setAdminMessage(User $user, string $text)
    {
        $user->adminMessage()->updateOrCreate(
            ['user_id' => $user->getKey()],
            ['text' => $text],
        );
    }

}