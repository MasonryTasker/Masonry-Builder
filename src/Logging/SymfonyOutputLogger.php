<?php
/**
 * SymfonyOutputLogger.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Logging;

use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class SymfonyOutputLogger
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class SymfonyOutputLogger extends AbstractSimpleLogger
{

    const MIN_SIZE = 7;

    protected $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        $this->output->writeln($this->formatLog($level, $message));
    }

    /**
     * Apply a colour based on the level
     * Use symfony colors
     * @param $level
     * @param $textToColor
     * @return string
     */
    protected function colorForLevel($level, $textToColor)
    {
        switch ($level) {
            case LogLevel::NOTICE:
                return "<fg=yellow>".$textToColor."</>";
                break;
            case LogLevel::INFO:
                return "<fg=green>".$textToColor."</>";
                break;
            case LogLevel::DEBUG:
                return "<fg=cyan>".$textToColor."</>";
                break;
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
            case LogLevel::ERROR:
            case LogLevel::WARNING:
            default:
        }
        return "<fg=red>$textToColor</>";
    }


    /**
     * Translate the word
     * @param $level
     * @return string
     */
    protected function translateLevel($level)
    {
        switch ($level) {
            case LogLevel::INFO:
                return "Success";
            case LogLevel::ERROR:
                return "Failure";
            case LogLevel::DEBUG:
            case LogLevel::NOTICE:
                return ucwords(strtolower($level));
                break;
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
            case LogLevel::WARNING:
            default:
                return strtoupper($level);
                break;
        }
    }

}
