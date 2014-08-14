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
 * @class Strell_Log_Message_Error
 */

class Strell_Log_Message_Error
    extends Strell_Log_Message_Abstract
{
    /**
     * Decorate a message with the default output
     * @param $message
     * @return mixed|string
     */
    protected function _decorateMessage($message)
    {
        return sprintf(' !!!Error!!! [%s](%d): %s', @date('Y-m-d H:i:s'), getmypid(), $message);
    }
}