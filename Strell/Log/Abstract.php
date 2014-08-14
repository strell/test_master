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
 * @class Strell_Log_Abstract
 */

abstract class Strell_Log_Abstract
{
    const DATE_FORMAT_FULL = 'Y-m-d H:i:s';
    const DATE_FORMAT_SHORT = 'H:i:s';

    public function writeLog($logFileHandle, $logString)
    {
        if ($logFileHandle) {
            try {
                flock($logFileHandle, LOCK_EX);
                fprintf($logFileHandle, "[%s]: %s\n", date(self::DATE_FORMAT_SHORT), $logString);
                flock($logFileHandle, LOCK_UN);
            } catch (Exception $e) {
                flock($logFileHandle, LOCK_UN);
            }
        }
        return $this;
    }
}