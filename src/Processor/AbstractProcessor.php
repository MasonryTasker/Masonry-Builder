<?php
/**
 * AbstractProcessor.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Processor;

use Foundry\Masonry\Core\Mediator;
use Foundry\Masonry\Interfaces\MediatorInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


/**
 * Class AbstractProcessor
 *
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
abstract class AbstractProcessor implements ProcessorInterface, LoggerAwareInterface
{

    /**
     * @var MediatorInterface
     */
    protected $mediator;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param MediatorInterface $mediator
     * @return $this
     */
    public function setMediator(MediatorInterface $mediator)
    {
        $this->mediator = $mediator;
        return $this;
    }

    /**
     * Get the mediator
     * @return MediatorInterface
     */
    protected function getMediator()
    {
        if(!$this->mediator) {
            $this->setMediator(new Mediator());
        }
        return $this->mediator;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if(!$this->logger) {
            $this->logger = new NullLogger();
        }
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

}