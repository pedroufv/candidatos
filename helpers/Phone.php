<?php

namespace Helpers;

class Phone {

    public static function onlyDigits($text)
    {
        return preg_replace('/\D/', '', $text);
    }

    public static function formatPhone($string)
    {
        if (strlen($string) > 10)
            $string = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 1) .
                '.' . substr($string, 3, 4) . '-' . substr($string, 7, 4);
        else
            $string = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 4) .
                '-' . substr($string, 6, 4);

        return $string;
    }
}
