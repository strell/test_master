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
 * @class Strell_Autoload
 */

class Strell_Autoload
{
    /**
     * A pre-defined class name separator
     */
    const CLASS_NAME_SEPARATOR      = '_';

    /**
     * A required class namespace
     */
    const TARGET_CLASS_NAMESPACE    = 'Strell';

    /**
     * Retrieve a base firectory to include
     * @return string
     */
    protected static function _getBaseDirectory()
    {
        return dirname(__FILE__);
    }

    /**
     * @param $classParts
     * @return string
     */
    protected static function _getClassFileName($classParts)
    {
        return sprintf('%s.php',
            implode(DIRECTORY_SEPARATOR,
                array_merge(
                    array(
                        self::_getBaseDirectory()
                    ),
                    $classParts
                )
            )
        );
    }

    /**
     * Handle a autoload
     * @param $className
     */
    public static function handleAutoload($className)
    {
        $classNameParts = explode(self::CLASS_NAME_SEPARATOR, $className);

        $classNamespace = array_shift($classNameParts);

        if ($classNamespace == self::TARGET_CLASS_NAMESPACE) {
            require_once self::_getClassFileName($classNameParts);
        }
    }
}

spl_autoload_register('Strell_Autoload::handleAutoload', true, true);
