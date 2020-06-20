<?php

namespace App\Conversations;

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
        $question = Question::create('Пока я могу рассказать анекдот. Рассказать?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Да')->value('joke'),
                Button::create('Нет')->value('quote')
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
            } else {
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
