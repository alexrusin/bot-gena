<?php

namespace App\Http\Controllers;

use App\ChatUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatUserController extends Controller
{
    public function create($payload, $bot)
    {
        $user = $payload['user'] ?? null;
        if (!$user) {
            return;
        }
        $chatUser = ChatUser::withTrashed()
            ->whereChatUserId($user['id'])
            ->first();

        if ($chatUser) {
            $chatUser->restore();
        } else {
            try {
                ChatUser::create([
                    'chat_user_id' => $user['id'],
                    'name' => $user['name'],
                    'avatar' => $user['avatar'],
                    'country' => $user['country'],
                    'language' => $user['language'],
                    'api_version' => $user['api_version']
                ]);
            } catch (Exception $e) {
                Log::error($e);
            }
        }
    }

    public function delete($payload, $bot)
    {
        $userId = $payload['user_id'] ?? null;
        if (!$userId) {
            return;
        }

        $chatUser = ChatUser::whereChatUserId($userId)
            ->first();

        if ($chatUser) {
            $chatUser->delete();
        }
    }
}
