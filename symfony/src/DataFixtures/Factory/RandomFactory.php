<?php

namespace App\DataFixtures\Factory;

abstract class RandomFactory
{
    private static string $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec posuere ornare dolor
    , ac mattis elit auctor ac. Vestibulum neque nisi, consectetur in lorem et, iaculis dignissim elit. Duis vitae 
    varius ipsum. Nulla orci mi, aliquet nec rutrum quis, dapibus vel augue. In fringilla congue libero ac blandit. 
    Nullam elementum libero leo, nec tempor nibh sagittis sit amet. Duis finibus, tortor ac tristique imperdiet,
     magna ipsum facilisis odio, at maximus dolor quam in odio. Proin mollis dolor in massa vestibulum gravida. Sed et 
     sodales justo. Sed sapien leo, maximus quis purus a, sollicitudin rhoncus nisi.';

    public static function getRandomFloat(float $min = 0, float $max = 1, int $precision = 2): float
    {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $precision);
    }

    public static function getLoremIpsum(int $multiplicator = 1, ?int $maxLength = null): string
    {
        $text = str_repeat(self::$lorem, $multiplicator);

        if (!is_null($maxLength)) {
            $text = substr($text, 0, $maxLength);
        }

        return $text;
    }
}
