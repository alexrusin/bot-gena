<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    // dd($bot->getMessage()->getPayload()->all());
    $bot->reply('Hello');
});

$botman->hears('Привет', function ($bot) {
    $bot->reply('Привет. Как дела?');
});

$botman->hears('Хорошо', function ($bot) {
    $bot->reply('У меня тоже всё клёво ;-)');
});

$botman->hears('Что ты можешь(\?)?', BotManController::class . '@startConversation');

$botman->on('subscribed', 'App\Http\Controllers\ChatUserController@create');
$botman->on('unsubscribed', 'App\Http\Controllers\ChatUserController@delete');

$botman->on('conversation_started', function($payload, $bot) {
    $bot->reply('Привет. Спроси у меня "Что ты можешь?"');
});

$botman->fallback(function ($bot) {
    $bot->reply('Извини, я не понимаю ¯\_(ツ)_/¯ Спроси у меня "Что ты можешь?"');
});
