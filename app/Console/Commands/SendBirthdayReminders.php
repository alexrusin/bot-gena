<?php

namespace App\Console\Commands;

use App\Birthday;
use App\ChatUser;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendBirthdayReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends reminders for birthdays';

    /** @var GuzzleHttp\Client */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->client = new Client([
            'base_uri' => 'https://chatapi.viber.com/pa/',
            'timeout' => 5.0,
            'verify' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Viber-Auth-Token' => config('botman.viber.token')
            ]
        ]);

        Log::info('Start sending reminders');

        ChatUser::with('birthdays')->chunk(500, function ($users) {
            $users->each(function ($user) {
                $this->sendReminder($user);
            });
        });

        Log::info('End sending reminders');
    }

    protected function sendReminder(ChatUser $user): void
    {
        $user->birthdays->each(function ($birthday) use ($user) {
            $this->sendBirthdayReminder($birthday, $user);
        });
    }

    protected function sendBirthdayReminder(Birthday $birthday, ChatUser $user): void
    {
        if ($this->needToSendReminder($user, $birthday)) {
            $this->sendReminderTo($user, $birthday);
        }
    }

    protected function needToSendReminder(ChatUser $user, Birthday $birthday): bool
    {
        return $birthday->birthday->isBirthday() && $this->betweenHours($user) && !$birthday->alreadyReminded();
    }

    protected function betweenHours($user): bool
    {
        $start = Carbon::now($user->timezone)->startOfDay()->addHours(8)->addMinutes(30);
        $end = Carbon::now($user->timezone)->startOfDay()->addHours(10)->addMinutes(30);
        $now = Carbon::now($user->timezone);

        return $now->between($start, $end, true);
    }

    protected function sendReminderTo(ChatUser $user, Birthday $birthday): void
    {
        Log::info("Sending reminder to {$user->name} ({$user->timezone}) for birthday of {$birthday->name}");
        $response = $this->client->post('send_message', [
            'json' => [
                'receiver' => $user->chat_user_id,
                'type' => 'text',
                'text' => "Сегодня {$birthday->name} празднует день рождения"
            ]
        ]);

        Log::info($response->getBody()->getContents());
        $birthday->reminded_at = Carbon::now();
        $birthday->save();
    }
}
