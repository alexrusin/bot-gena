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

$botman->hears('Start conversation', BotManController::class . '@startConversation');

$botman->fallback(function ($bot) {
    $bot->reply('Извини, я не понимаю ¯\_(ツ)_/¯');
});
