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
            'DE' => 'Europe/Zurich',
            'NL' => 'Europe/Zurich'
        ];

        return $countryTimezone[$countryCode] ?? 'Europe/Moscow';
    }
}