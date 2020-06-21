<?php

use App\ChatUser;
use Illuminate\Database\Seeder;

class Birthdays11ASeeder extends Seeder
{
    protected $birthdays = [
        'Суворов А. 6/1',
        'Русин А. 17/01',
        'Мотова А. 28/1'
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chatUsers = ChatUser::where('eleven_a', true)
            ->get();
        
        $chatUsers->each->addBirthdays($this->birthdays);
    }
}
