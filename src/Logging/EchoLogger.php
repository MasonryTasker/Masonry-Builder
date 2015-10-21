<?php
/**
 * EchoLogger.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Logging;

use Psr\Log\LogLevel;


/**
 * Class EchoLogger
 * ${CARET}
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class EchoLogger extends AbstractSimpleLogger
{

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        echo $this->formatLog($level, $message) . PHP_EOL;
    }

}