<?php
namespace App\Services;

use App\Models\RequestLinkedMessenger;
use Carbon\Carbon;
use Str;

class RequestLinkedMessengerService
{
    public const COUNT_MINUTE_VALID_REQUEST = 10;
    public function getByToken(string $token): RequestLinkedMessenger|null
    {
        return RequestLinkedMessenger::where('token', $token)->first();
    }
    public function remove(RequestLinkedMessenger $requestLinked): bool|null
    {
        return $requestLinked->delete();
    }

    public function is_valid(RequestLinkedMessenger $requestLinked): bool
    {
        $now = Carbon::now();
        if (abs($now->diffInMinutes($requestLinked->created_at)) <= self::COUNT_MINUTE_VALID_REQUEST) {
            return true;
        }
        return false;
    }

    public function create(int $user_id, int $messenger_id): RequestLinkedMessenger
    {
        RequestLinkedMessenger::where('user_id', $user_id)->where('messenger_id', $messenger_id)->delete();
        return RequestLinkedMessenger::create([
            'user_id' => $user_id,
            'messenger_id' => $messenger_id,
            'token' => env('START_TEXT_COMMAND_FOR_LINKED_ACCOUNT') . Str::random(4) . $user_id . $messenger_id . Str::random(4),
        ]);
    }

}