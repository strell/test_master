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
 * @class Strell_Log_Writter_File
 */

class Strell_Log_Writter_File
    implements Strell_Log_Writter_Interface
{
    /**
     * A log file name
     * @var string
     */
    protected $_logFileName = '/tmp/log';

    /**
     * Write a log messgae
     * @return Strell_Log_Writter_File
     */
    public function write($message, $breakLine = true)
    {
        $fileHandle = fopen($this->_logFileName, 'a');
        flock($fileHandle, LOCK_EX);

        try {
            fwrite($fileHandle, $message . ($breakLine ? "\n" : ''));
        } catch (Exception $e) {
            flock($fileHandle, LOCK_UN);
            fclose($fileHandle);
            throw $e;
        }
        flock($fileHandle, LOCK_UN);
        fclose($fileHandle);

        return $this;
    }

    /**
     * Renew a log
     * @return Strell_Log_Writter_File
     */
    public function renew()
    {
        $fileHandle = fopen($this->_logFileName, 'w');
        fclose($fileHandle);
        return $this;
    }

    /**
     * Close a log writter
     * @return Strell_Log_Writter_File
     */
    public function close()
    {
        return $this;
    }
}