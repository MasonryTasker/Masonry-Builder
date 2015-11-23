<?php
/**
 * AbstractSimpleLogger.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Logging;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;


/**
 * Class AbstractSimpleLogger
 * Formats and potentially colors a log message
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
abstract class AbstractSimpleLogger extends AbstractLogger
{

    const MIN_SIZE = 6;

    protected function formatLog($level, $message)
    {
        return $this->formatLevel($level) . $this->formatMessage($message);
    }

    /**
     * Format the message
     * @param string $level
     * @return string
     */
    protected function formatLevel($level = '')
    {
        $translatedLevel = $this->translateLevel($level);
        $decoratedLevel  = $this->decorateLevel($translatedLevel);
        $coloredLevel    = $this->colorForLevel($level, $decoratedLevel);
        return $coloredLevel;
    }

    /**
     * Translate the word
     * @param $level
     * @return string
     */
    protected function translateLevel($level)
    {
        switch ($level) {
            case LogLevel::NOTICE:
            case LogLevel::INFO:
            case LogLevel::DEBUG:
                return strtolower($level);
                break;
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
            case LogLevel::ERROR:
            case LogLevel::WARNING:
            default:
                return strtoupper($level);
                break;
        }
    }

    /**
     * Apply decorators to the level
     * @param $level
     * @return string
     */
    protected function decorateLevel($level)
    {
        return str_pad($level, static::MIN_SIZE, ' ') . " :  ";
    }

    /**
     * Apply a colour based on the level
     * This function does not change color by default but should be extended
     * @param $level
     * @param $textToColor
     * @return string
     */
    protected function colorForLevel($level, $textToColor)
    {
        return $textToColor;
    }

    /**
     * Format the message as required
     * @param $message
     * @return string
     */
    protected function formatMessage($message)
    {
        return $this->decorateMessage($message);
    }

    /**
     * A simple decoration
     * @param $message
     * @return string
     */
    protected function decorateMessage($message)
    {
        return $message;
    }

}