<?php

use App\ChatUser;
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

$botman->hears('(Рассказать анекдот|анекдот)', function ($bot) {
    $joke = file_get_contents('http://rzhunemogu.ru/Rand.aspx?CType=1');
    $joke = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $joke);
    $joke = str_replace('<root>', '', $joke);
    $joke = str_replace('</root>', '', $joke);
    $joke = str_replace('<content>', '', $joke);
    $joke = str_replace('</content>', '', $joke);
    $bot->reply(mb_convert_encoding($joke, "utf-8", "windows-1251"));
});

$botman->hears('Напомнить о дне рождении', function($bot) {
    $payload = $bot->getMessage()->getPayload()->all();
    $chatUserId = $payload['sender']['id'] ?? null;
    $chatUser = ChatUser::with('birthdays')->whereChatUserId($chatUserId)->first();
    if (!$chatUser) {
        $bot->reply('Извини, у меня нет информации о тебе в база данных');
        return;
    }

    if ($chatUser->birthdays->isEmpty()) {
        $bot->reply('У тебя нет дней рождений в списке.  Напиши "Добавить день рождения"');
        return;
    }

    $birthdaysList = $chatUser->getBirthdaysList();

    $bot->reply($birthdaysList);
});

$botman->hears('Меню', BotManController::class . '@startConversation');
$botman->hears('Добавить день рождения', BotManController::class . '@addBirthdayConversation');
$botman->hears('Удалить день рождения', BotManController::class . '@deleteBirthdayConversation');

$botman->on('subscribed', 'App\Http\Controllers\ChatUserController@create');
$botman->on('conversation_started', 'App\Http\Controllers\ChatUserController@create');
$botman->on('unsubscribed', 'App\Http\Controllers\ChatUserController@delete');

$botman->fallback(function ($bot) {
    $bot->reply('Извини, я не понимаю ¯\_(ツ)_/¯');
    $bot->startConversation(new InitialConversation());
});
