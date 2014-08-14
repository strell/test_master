<?php
/**
 * @Developer:
 *       Roman Strilenko
 * @E-mail:
 *       roman.strelenko@autoda.de
 *       strell@strelldev.com
 * @Luxodo.com
 */
/**
 * @package Strell
 * @class Strell_Dump
 */

class test2{
}

class Strell_Dump
{
    /**
     * Retrun a dump value
     * @param $value
     * @return string
     */
    public static function dump($value)
    {
        ob_start();
        print_r($value);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * Provide a logging of the value
     * @param $value
     */
    public static function log($title, $value)
    {
        Strell_Log::log(
            sprintf('%s == %s', $title, self::dump($value)));
    }
}
