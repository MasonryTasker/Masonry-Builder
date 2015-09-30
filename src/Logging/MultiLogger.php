<?php
/**
 * MultiLogger.php
 * PHP version 5.4
 * 2015-09-29
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Logging;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * Class MultiLogger
 * Add multiple loggers to a single class to output to multiple locations at once.
 * @package Foundry\Masonry\Builder
 */
class MultiLogger extends AbstractLogger
{

    /**
     * @var LoggerInterface[]
     */
    private $loggers = [];

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function addLogger(LoggerInterface $logger)
    {
        $this->loggers[] = $logger;
        return $this;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        foreach($this->loggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }
}