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
 * @class Strell_Log_Message_Abstract
 */

abstract class Strell_Log_Message_Abstract
{
    /**
     * A log writter instance
     * @var Strell_Log_Writter_Interface
     */
    protected $_logWritter = null;

    /**
     * Check for the valid log writter and return it
     * @return Strell_Log_Writter_Interface
     */
    protected function _getWritter()
    {
        if (!$this->_logWritter instanceof Strell_Log_Writter_Interface) {
            Strell_Log_Exception::throwException('The log writter was not set');
        }

        return $this->_logWritter;
    }

    /**
     * Decorate an message
     * @param $message
     * @return mixed
     */
    protected abstract function _decorateMessage($message);

    /**
     * Initialize with the log writter
     * @param Strell_Log_Writter_Interface $logWritter
     * @return $this
     */
    public function init(Strell_Log_Writter_Interface $logWritter)
    {
        $this->_logWritter = $logWritter;
        return $this;
    }

    /**
     * Initialize a log writter in the constructor
     * @param Strell_Log_Writter_Interface $logWritter
     */
    public function __construct(Strell_Log_Writter_Interface $logWritter)
    {
        $this->init($logWritter);
    }

    /**
     * Write a message to the log
     * @param $message
     * @return $this
     */
    public function write($message)
    {
        $this->_getWritter()->write(
            $this->_decorateMessage($message)
        );
        return $this;
    }
}