<?php

namespace App\Conversations;

use App\ChatUser;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Exception;
use Illuminate\Support\Facades\Log;

class DeleteBirthdayConversation extends Conversation
{
    public function askBirthday()
    {
        $this->ask('Напиши имя, которое нужно удалить. Например: "Василь Быков"', function(Answer $answer) {

            try {
                $payload = $this->getBot()->getMessage()->getPayload()->all();
                $chatUserId = $payload['sender']['id'] ?? null;
                $chatUser = ChatUser::whereChatUserId($chatUserId)->first();
                if (!$chatUser) {
                    $this->say('Извини, у меня нет информации о тебе в база данных');
                    return;
                }

                $message = trim($payload['message']['text']);
                
                $result = $chatUser->birthdays()->whereName($message)->first();

                if (!$result) {
                    throw new Exception('Error deleting birthday');
                }

                $result->delete();

            } catch (Exception $e) {
                Log::error($e);
                $this->say('Извини, не получилоь удалить. Если хочешь попробовать удалить ещё раз. Напиши: "Удалить день рождения"');
                return;
            }

            $this->say('День рождения удалено');
            $this->say($chatUser->getBirthdaysList());
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askBirthday();
    }
}
