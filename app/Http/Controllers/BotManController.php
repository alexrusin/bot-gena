<?php

namespace App\Http\Controllers;

use App\Conversations\AddBirthdayConversation;
use App\Conversations\DeleteBirthdayConversation;
use BotMan\BotMan\BotMan;
use App\Conversations\InitialConversation;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');
        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new InitialConversation());
    }

    public function addBirthdayConversation(BotMan $bot)
    {
        $bot->startConversation(new AddBirthdayConversation());
    }

    public function deleteBirthdayConversation(BotMan $bot)
    {
        $bot->startConversation(new DeleteBirthdayConversation());
    }
}
