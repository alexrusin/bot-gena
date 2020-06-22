<?php

namespace App;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class ChatUser extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'eleven_a' => 'boolean'
    ];

    public function birthdays()
    {
        return $this->hasMany(Birthday::class);
    }

    public function addBirthdays(array $birthdays)
    {
        foreach ($birthdays as $birthday) {
            $this->addBirthday($birthday);
        }
    }

    public function addBirthday(string $birthday): ?Birthday
    {
        $data = explode(' ', trim($birthday));
        
        $date = array_pop($data);

        $name = implode(' ', $data);


        $birthdayDate = Carbon::createFromFormat('d/m', $date)->startOfDay();

        try {
            return $this->birthdays()->create([
                'name' => substr($name, 0, 250),
                'birthday' => $birthdayDate
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return null;
        }    
    }

    public function getBirthdaysList(): string
    {
        $birthdaysCollection = $this->birthdays->map(function($item) {
            return $item->name . ' ' . $item->birthday->format('d/m');
        });

        $birthdaysCollection->prepend("Твой список дней рождений:\n");
        $birthdaysCollection->push("\nНапиши \"Добавить день рождения\" или \"Удалить день рождения\"");

        return implode("\n", $birthdaysCollection->toArray());
    }
}
