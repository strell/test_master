<?php
/**
 * @Developer:
 *       Roman Strilenko
 * @E-mail:
 *       strelenko.roman@gmail.com
 *       strell@strelldev.com
 * @Strelldev
 * @url http://strelldev.com
 */ 


/**
 * @package Strell_Log
 * @class Strell_Log_Interface
 */

interface Strell_Log_Interface
{
    public function log();
    public function logException(Exception $exception);
}