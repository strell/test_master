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
 * @class Strell_Log
 */

class Strell_Log
{
    /**
     * A default log writter
     */
    const DEFAULT_LOG_WRITTER = 'Strell_Log_Writter_File';

    /**
     * A prefix for the factory
     */
    const FACTORY_CLASS_PREFIX = 'Strell_Log_Message';

    /**
     * A possible log messsages type
     */
    const TYPE_DEFAULT  = 'default';
    const TYPE_ERROR    = 'error';
    const TYPE_WARNING  = 'warnong';
    const TYPE_NOTICE   = 'notice';

    /**
     * An instance that will be used for the singleton
     * @var Strell_Log
     */
    protected static $_instance = null;

    /**
     * A log writter cached instance
     * @var Strell_Log_Writter_Interface
     */
    protected $_logWritter      = null;

    /**
     * Cached factory instances
     * @var array
     */
    protected $_factoryInstances = array();

    /**
     * Reset a log file on class creation flag
     * @var bool
     */
    protected $_resetOnStartup   = false;


    /**
     * Allowed messages types that are used for the validation
     * @var array
     */
    protected $_allowedMessageTypes = array(
        self::TYPE_DEFAULT,
        self::TYPE_ERROR,
        self::TYPE_NOTICE,
        self::TYPE_WARNING
    );

    /**
     * Retrieve a default log file writter
     * @return Strell_Log_Writter_Interface
     */
    protected function _getDefaultLogWritterInstance()
    {
        $logWritterClassName = self::DEFAULT_LOG_WRITTER;
        return new $logWritterClassName();
    }

    /**
     * Retrieve a default log writter
     * @return null|Strell_Log_Writter_Interface
     */
    protected function _getWriter()
    {
        if (is_null($this->_logWritter)) {
            $this->_logWritter = $this->_getDefaultLogWritterInstance();
        }

        return $this->_logWritter;
    }

    /**
     * Set a new log writter
     * @param Strell_Log_Writter_Interface $logWritter
     * @return $this
     */
    protected function _setWritter(Strell_Log_Writter_Interface $logWritter)
    {
        if ($this->_logWritter) {
            $this->_logWritter->close();
        }
        $this->_logWritter = $logWritter;
        return $this;
    }

    /**
     * generate a factory class name
     * @param $messageType
     * @return string
     */
    protected function _getFactoryClassName($messageType)
    {
        return sprintf('%s_%s', self::FACTORY_CLASS_PREFIX, ucfirst($messageType));
    }

    /**
     * Generate and retrieve a required factory object
     * @param $messageType
     * @return Strell_Log_Message_Abstract
     */
    protected function _factory($messageType)
    {
        if (!in_array($messageType, $this->_allowedMessageTypes)) {
            Strell_Log_Exception::throwException(
                sprintf('The message type %s is not allowed or found', $messageType)
            );
        }

        if (!array_key_exists($messageType, $this->_factoryInstances)) {
            $factoryClassName = $this->_getFactoryClassName($messageType);
            if (!class_exists($factoryClassName)) {
                Strell_Log_Exception::throwException('The factory object was not found ("%s/%s")', $factoryClassName, $messageType);
            }

            $this->_factoryInstances[$messageType] = new $factoryClassName($this->_getWriter());

            if (!$this->_factoryInstances[$messageType] instanceof Strell_Log_Message_Abstract) {
                Strell_Log_Exception::throwException('A wrong factory object was created');
            }
        }

        return $this->_factoryInstances[$messageType];
    }

    /**
     * Make an initialization
     */
    protected function __construct()
    {
        if ($this->_resetOnStartup) {
            $this->_getWriter()->renew();
        }
    }

    /**
     * Initialize a log instance
     * @return null|Strell_Log
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Strell_Log();
        }

        return self::$_instance;
    }

    /**
     * Set a new log writter
     * @param Strell_Log_Writter_Interface $logWritter
     * @return $this
     */
    protected function _setLogWritter(Strell_Log_Writter_Interface $logWritter)
    {
        if ($this->_logWritter) {
            $this->_logWritter->close();
        }
        $this->_logWritter = $logWritter;

        /**
         * @var $factoryInstance Strell_Log_Message_Abstract
         */
        foreach ($this->_factoryInstances as $factoryInstance) {
            $factoryInstance->init($logWritter);
        }

        return $this;
    }

    /**
     * Close a writter when the class going to be destroyed
     */
    public function __destruct()
    {
        if ($this->_logWritter) {
            $this->_logWritter->close();
        }
    }

    /**
     * Make a log entry
     */
    public static function log($message, $type = self::TYPE_DEFAULT, $renew = false)
    {
        if ($renew) {
            self::getInstance()->_getWriter()->renew();
        }

        self::getInstance()->_factory($type)->write($message);
    }

    /**
     * Log an errror (facede method)
     * @param $message
     * @param bool $renew
     */
    public static function logError($message, $renew = false)
    {
        self::log($message, self::TYPE_ERROR, $renew);
    }

    /**
     * Log a notice
     * @param $message
     * @param bool $renew
     */
    public static function logNotice($message, $renew = false)
    {
        self::log($message, self::TYPE_NOTICE, $renew);
    }

    /**
     * Log a warning
     * @param $message
     * @param bool $renew
     */
    public static function logWarning($message, $renew = false)
    {
        self::log($message, self::TYPE_WARNING, $renew);
    }

    /**
     * Reset a log
     */
    public static function reset()
    {
        self::getInstance()->_getWriter()->renew();
    }

    /**
     * Set a log writter
     * @param Strell_Log_Writter_Interface $logWritter
     */
    public static function setWritter(Strell_Log_Writter_Interface $logWritter)
    {
        self::getInstance()->_setLogWritter($logWritter);
    }
}