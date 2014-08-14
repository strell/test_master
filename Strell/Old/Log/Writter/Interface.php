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
 * @class Strell_Log_Writter_Interface
 */

interface Strell_Log_Writter_Interface
{
    /**
     * Write a log messgae
     * @return Strell_Log_Writter_Interface
     */
    public function write($message, $breakLine = true);

    /**
     * Renew a log
     * @return Strell_Log_Writter_Interface
     */
    public function renew();

    /**
     * Close a log writter
     * @return Strell_Log_Writter_Interface
     */
    public function close();
}