<?php


namespace ixapek\BuyItAgain\Component\Main;


class RandomHelper
{
    /**
     * Random string
     *
     * @param int $length Word length
     *
     * @return string
     */
    public static function word(int $length = 6): string
    {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        return substr(implode($word), 0, $length);
    }

    /**
     * Random float with 2 symbols after dot
     *
     * @return float
     */
    public static function price(): float
    {
        $rnd = mt_rand(10, 1000);
        $rndFloat = mt_rand(1 * $rnd, 99999 * $rnd) / $rnd;
        return round($rndFloat, 2);
    }
}