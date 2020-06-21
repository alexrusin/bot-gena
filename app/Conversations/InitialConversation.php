<?php

namespace App\Conversations;

use App\ChatUser;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class InitialConversation extends Conversation
{
    /**
     * First question
     */
    public function askReason()
    {
        $question = Question::create('Я могу')
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Рассказать анекдот')->value('joke'),
                Button::create('Напомнить о дне рождении')->value('birthday_reminder')
            ]);

        return $this->ask($question, function (Answer $answer) {
            
            if ($answer->getText() === 'joke') {
                $joke = file_get_contents('http://rzhunemogu.ru/Rand.aspx?CType=1');
                $joke = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $joke);
                $joke = str_replace('<root>', '', $joke);
                $joke = str_replace('</root>', '', $joke);
                $joke = str_replace('<content>', '', $joke);
                $joke = str_replace('</content>', '', $joke);
                $this->say(mb_convert_encoding($joke, "utf-8", "windows-1251"));
            } elseif ($answer->getText() === 'birthday_reminder') {
                $payload = $this->getBot()->getMessage()->getPayload()->all();
                $chatUserId = $payload['sender']['id'] ?? null;
                $chatUser = ChatUser::with('birthdays')->whereChatUserId($chatUserId)->first();
                if (!$chatUser) {
                    $this->say('Извини, у меня нет информации о тебе в база данных');
                    return;
                }

                if ($chatUser->birthdays->isEmpty()) {
                    $this->say('У тебя нет в дней рождений в списке.  Напиши "Добавить день рождения"');
                    return;
                }

                $birthdaysList = $chatUser->getBirthdaysList();

                $this->say($birthdaysList);
            } 
            else {
                $this->say(Inspiring::quote());
            }
           
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }
}
