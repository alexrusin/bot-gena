<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Birthday extends Model
{
    protected $guarded = [];

    protected $dates = ['birthday', 'reminded_at'];

    public function alreadyReminded()
    {
        if (!$this->reminded_at) {
            return false;
        }

        return $this->reminded_at->addDays(364)->gt(Carbon::now());
    }
}
