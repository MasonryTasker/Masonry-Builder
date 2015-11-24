<?php
/**
 * BlockingProcessor.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Processor;

use Foundry\Masonry\Builder\Coroutine\Factory;
use Foundry\Masonry\Builder\Notification\Notification;
use Foundry\Masonry\Builder\Notification\NotificationInterface;
use Foundry\Masonry\Interfaces\Pool\StatusInterface;
use Foundry\Masonry\Interfaces\PoolInterface;


/**
 * Class BlockingProcessor
 * Deals with one task at a time from the pool
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class BlockingProcessor extends AbstractProcessor
{

    /**
     * @param PoolInterface $pool
     * @return bool|\Generator
     */
    public function run(PoolInterface $pool)
    {
        $success = true;
        while ($success && $pool->getStatus() != StatusInterface::STATUS_EMPTY) {
            // Block until task complete
            $taskComplete = false;

            // Set up the task and prepare for feedback
            $this->getMediator()->process($pool->getTask())
                // On success
                ->then(function ($result) use (&$taskComplete) {
                    $this->getLogger()->info($result);
                })
                // On failure
                ->otherwise(function ($result) use (&$success) {
                    if(!$result instanceof NotificationInterface) {
                        $result = new Notification($result, 0); // Failures should always show
                    }
                    $this->getLogger()->error($result);
                    $success = false;
                })
                // When something happens
                ->progress(function ($result) {
                    $this->getLogger()->notice($result);
                })
                // When complete, regardless of success of failure
                ->done(function () use (&$taskComplete) {
                    $taskComplete = true;
                });

            // Until the task is complete, keep processing deferred work
            while (!$taskComplete) {
                Factory::getCoroutineRegister()->tick();
            }
        }

        return $success;
    }

}