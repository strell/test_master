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
 * @class Strell_Log
 */


class test{

}


class test1{

}

class Strell_Log
{
    static protected  $_logInstance = null;

    protected $_defaultLogger = Strell_Log_Pipe::LOGGER_TYPE;

    protected $_availableLoggers = array(
        Strell_Log_Pipe::LOGGER_TYPE,
        Strell_Log_Browser::LOGGER_TYPE
    );

    protected $_loggersInstances = array();

    protected $_currentLoggerType = null;

    protected function _getCurrentLoggerType()
    {
        return is_null($this->_currentLoggerType)
            ? $this->_defaultLogger
            : $this->_currentLoggerType;
    }

    protected function _initializeLogger($loggerType)
    {
        if (!in_array($loggerType, $this->_availableLoggers)) {
            throw new Exception(sprintf('The logger with type %s was not found', $loggerType));
        }

        $loggerClassName = sprintf('%s_%s', __CLASS__, $loggerType);

        return new $loggerClassName();
    }

    /**
     * @param $loggerType
     *
     * @return Strell_Log_Interface
     */
    protected function _getLoggerInstance($loggerType)
    {
        if (!array_key_exists($loggerType, $this->_loggersInstances)) {
            $this->_loggersInstances[$loggerType] = $this->_initializeLogger($loggerType);
        }

        return $this->_loggersInstances[$loggerType];
    }

    protected function _setType($loggerType)
    {
        $this->_currentLoggerType = $loggerType;
        return $this;
    }

    protected function _log()
    {
        call_user_func_array(
            array(
                 $this->_getLoggerInstance($this->_getCurrentLoggerType()),
                'log'
            ),
            func_get_args()
        );
        return $this;
    }


    protected function _logException(Exception $e)
    {
        $this->_getLoggerInstance($this->_getCurrentLoggerType())
            ->logException($e);
        return $this;
    }

    static public function getInstance($loggerType = null)
    {
        (!self::$_logInstance instanceof Strell_Log) && (self::$_logInstance = new Strell_Log());

        if ($loggerType) {
            self::$_logInstance->_setType($loggerType);
        }

        return self::$_logInstance;
    }


    static public function log()
    {
        return call_user_func_array(
            array(
                 self::getInstance(),
                 '_log'
            ),
            func_get_args()
        );
    }

    static public function logException(Exception $e)
    {
        return self::getInstance()->_logException($e);
    }
}
