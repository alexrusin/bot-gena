<?php

namespace App\Conversations;

use App\ChatUser;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Exception;
use Illuminate\Support\Facades\Log;

class AddBirthdayConversation extends Conversation
{
    public function askBirthday()
    {
        $this->ask('Напиши имя и день рождения. Например: "Василь Быков 19/06"', function(Answer $answer) {

            try {
                $payload = $this->getBot()->getMessage()->getPayload()->all();
                $chatUserId = $payload['sender']['id'] ?? null;
                $chatUser = ChatUser::whereChatUserId($chatUserId)->first();
                if (!$chatUser) {
                    $this->say('Извини, у меня нет информации о тебе в база данных');
                    return;
                }

                $message = $payload['message']['text'];
                
                $result = $chatUser->addBirthday($message);

                if (!$result) {
                    throw new Exception('Error saving birthday');
                }

            } catch (Exception $e) {
                Log::error($e);
                $this->say('Извини, не получилоь добавить. Если хочешь попробовать добавить ещё раз. Напиши: "Добавить день рождения"');
                return;
            }

            $this->say('День рождения добавлено');
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
