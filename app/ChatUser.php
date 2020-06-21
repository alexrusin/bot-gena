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
                'name' => $name,
                'birthday' => $birthdayDate
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return null;
        }    
    }
}
