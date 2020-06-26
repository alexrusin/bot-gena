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

    public static function tellAJoke()
    {
        $joke = file_get_contents('http://rzhunemogu.ru/Rand.aspx?CType=1');
        $joke = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $joke);
        $joke = str_replace('<root>', '', $joke);
        $joke = str_replace('</root>', '', $joke);
        $joke = str_replace('<content>', '', $joke);
        $joke = str_replace('</content>', '', $joke);
        return mb_convert_encoding($joke, "utf-8", "windows-1251");
    }
}