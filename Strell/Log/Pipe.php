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
 * @class Strell_Log_Pipe
 */

class Strell_Log_Pipe
    extends Strell_Log_Abstract
    implements Strell_Log_Interface
{
    const LOGGER_TYPE = 'pipe';

    const PIPE_FILE_NAME_BASE_PATH = '/tmp';

    const PIPE_FILE_NAME_PREFIX = 'debug_';

    protected $_fileHandle = null;

    public function __construct()
    {
        $this->_initializePipe();
    }

    public function __destruct()
    {
        $this->_destroyPipe();
    }

    protected function _getPipeFileName()
    {
        return self::PIPE_FILE_NAME_BASE_PATH
            . DS
            . self::PIPE_FILE_NAME_PREFIX
            . $_SERVER['HTTP_HOST']
            . '.log';
    }

    protected function _initializePipe()
    {
        $pipeFileName = $this->_getPipeFileName();
        if (!file_exists($pipeFileName)) {
            if (!posix_mkfifo($pipeFileName, 0777)) {
                throw new Exception('Unable to create or initialize a fifo');
            }
        }

        $this->_fileHandle = fopen($pipeFileName, 'w');

        if (!$this->_fileHandle) {
            throw new Exception('Unable to initialize a logging pipe');
        }

        return $this;
    }

    protected function _destroyPipe()
    {
        fclose($this->_fileHandle);
        return $this;
    }

    public function log()
    {
        $arguments = func_get_args();
        foreach ($arguments as $logEntry) {
            $this->writeLog($this->_fileHandle, $logEntry);
        }

        return $this;
    }

    public function logException(Exception $exception)
    {
        return $this->log(
            sprintf('The exception was fired "%s", see backtrace below: ', $exception->getMessage()), (string)$exception);
    }
}