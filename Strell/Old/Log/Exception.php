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
 * @package Strell_Log
 * @class Strell_Log_Exception
 */

class Strell_Log_Exception
    extends Exception
{
    /**
     * Throw an log exception
     * @throws Strell_Log_Exception
     * @param $message
     */
    public static function throwException($message)
    {
        throw new Strell_Log_Exception($message);
    }
}