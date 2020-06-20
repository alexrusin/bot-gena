<?php

namespace App\ChatDrivers;

use BotMan\BotMan\Messages\Outgoing\Question;
use TheArdent\Drivers\Viber\Extensions\KeyboardTemplate;
use TheArdent\Drivers\Viber\ViberDriver;

class MyViberDriver extends ViberDriver
{
    /**
    * Convert a Question object
    *
    * @param  Question  $question
    *
    * @return array
    */
    protected function convertQuestion(Question $question): array
    {
        $actions = $question->getActions();
        if (count($actions) > 0) {
            $keyboard = new KeyboardTemplate($question->getText());
            foreach ($actions as $action) {
                $text = $action['text'];
                // $actionType = $action['additional']['url'] ? 'open-url' : 'reply';
                if (isset($action['additional']['url'])) {
                    $actionType = 'open-url';
                } else {
                    $actionType = 'reply';
                }
                $actionBody = $action['additional']['url'] ?? $action['value'] ?? $action['text'];
                $silent = isset($action['additional']['url']);
                $keyboard->addButton($text, $actionType, $actionBody, 'regular', null, 6, $silent);
            }
            return $keyboard->jsonSerialize();
        }

        return [
            'text' => $question->getText(),
            'type' => 'text',
        ];
    }
}
