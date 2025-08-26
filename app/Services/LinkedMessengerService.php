<?php
namespace App\Services;

use App\Models\LinkedMessenger;
use Illuminate\Support\Facades\DB;

class LinkedMessengerService
{
    public function getByUserMessenger(int $user_id, int $messenger_id): LinkedMessenger|null
    {
        return LinkedMessenger::where('user_id', $user_id)->where('messenger_id', $messenger_id)->first();
    }
    public function getByUserMessengerMessenger(int $user_messenger_id, int $messenger_id): LinkedMessenger|null
    {
        return LinkedMessenger::where('user_messenger_id', $user_messenger_id)->where('messenger_id', $messenger_id)->first();
    }

    public function create(array $data): void
    {
        $linkedMessenger = LinkedMessenger::create([
            "user_id" => $data['user_id'],
            "messenger_id" => $data['messenger_id'],
            "user_messenger_id" => $data['user_messenger_id'],
        ]);
        $this->select($linkedMessenger);
    }

    public function remove(LinkedMessenger $linkedMessenger): bool|null
    {
        return $linkedMessenger->delete();
    }
    public function select(LinkedMessenger $linkedMessenger): bool
    {
        DB::beginTransaction();
        try {
            LinkedMessenger::where('user_id', $linkedMessenger->user_id)->update([
                'is_selected' => false
            ]);
            $linkedMessenger->is_selected = true;
            $linkedMessenger->save();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function unselect(LinkedMessenger $linkedMessenger): bool
    {
        $linkedMessenger->is_selected = false;
        return $linkedMessenger->save();
    }

}