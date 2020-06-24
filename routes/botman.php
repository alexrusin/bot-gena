<?php

use App\Conversations\InitialConversation;
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    // dd($bot->getMessage()->getPayload()->all());
    $bot->reply('Hello');
});

$botman->hears('Привет', function ($bot) {
    $bot->reply('Привет. Как дела?');
});

$botman->hears('(Хорошо|Круто|Клёво|Клево|ok|ок|нормально|норм)', function ($bot) {
    $bot->reply('У меня тоже всё клёво ;-)');
});

$botman->hears('(так себе|плохо|хреново|хуёво|хуево)', function ($bot) {
    $bot->reply('Ничего, всё образуется.  Проблем нет только у тех людей, которые сейчас на кладбище ;-)');
});

$botman->hears('Что ты можешь(\?)?', BotManController::class . '@startConversation');
$botman->hears('Добавить день рождения', BotManController::class . '@addBirthdayConversation');
$botman->hears('Удалить день рождения', BotManController::class . '@deleteBirthdayConversation');

$botman->on('subscribed', 'App\Http\Controllers\ChatUserController@create');
$botman->on('conversation_started', 'App\Http\Controllers\ChatUserController@create');
$botman->on('unsubscribed', 'App\Http\Controllers\ChatUserController@delete');

$botman->fallback(function ($bot) {
    $bot->reply('Извини, я не понимаю ¯\_(ツ)_/¯');
    $bot->startConversation(new InitialConversation());
});
