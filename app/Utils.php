<?php

namespace App;

class Utils
{
    public static function getCountriesTimezone($countryCode = 'RU')
    {
        $countryTimezone = [
            'RU' => 'Europe/Moscow',
            'BY' => 'Europe/Minsk',
            'US' => 'America/New_York',
            'DE' => 'Europe/Berlin',
            'NL' => 'Europe/Amsterdam',
            'IL' => 'Asia/Jerusalem'
        ];

        return $countryTimezone[$countryCode] ?? 'Europe/Moscow';
    }
}