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
 * @class Strell_Log_Browser
 */

class Strell_Log_Browser
    implements Strell_Log_Interface
{
    const LOGGER_TYPE = 'browser';

    protected $_messageHeader = null;

    protected $_messageHtml   = null;

    protected $_backtraseScrollInvokedFiles = array(
        'Dump.php'
    );

    protected function _resetMessage()
    {
        $this->_messageHtml   = null;
        $this->_messageHeader = null;
        return $this;
    }

    protected function _renderTemplate($messageHeader, $messageBody)
    {
        $this->_resetMessage()
            ->_setMessageHeader($messageHeader)
            ->_setMessageHtml($messageBody);

        include "browser.phtml";

        return $this;
    }

    protected function _getInvokedLineData()
    {
        $backTrace = debug_backtrace();
        $backTrace = array_slice($backTrace, 5);
        $executedLine = array_shift($backTrace);

        while (!array_key_exists('file', $executedLine)
            || in_array(basename($executedLine['file']), $this->_backtraseScrollInvokedFiles)
        ) {
            $executedLine = array_shift($backTrace);
        }


        return sprintf('%s, Line: %d', $executedLine['file'], $executedLine['line']);
    }

    protected function _setMessageHeader($messageHeader)
    {
        $this->_messageHeader = $messageHeader;
        return $this;
    }

    protected function _setMessageHtml($messageHtml)
    {
        $this->_messageHtml = $messageHtml;
        return $this;
    }

    public function getMessageHeader()
    {
        return $this->_messageHeader;
    }

    public function getMessageHtml()
    {
        return $this->_messageHtml;
    }

    public function log()
    {
        foreach (func_get_args() as $logMessage) {
            $this->_renderTemplate(
                sprintf('[%s] - Debug Message from %s', date('Y-m-d H:i:s'), $this->_getInvokedLineData()),
                htmlspecialchars($logMessage)
            );
        }
    }

    public function logException(Exception $exception)
    {
        return $this->log(
            sprintf(
                "The Exception \"%s\" with the Messsage \"%s\", backtrace bellow:\n\n%s",
                get_class($exception),
                $exception->getMessage(),
                $exception->getTraceAsString()
            )
        );
    }
}