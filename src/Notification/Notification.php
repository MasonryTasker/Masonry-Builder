<?php
/**
 * Notification.php
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/TheFoundryVisionmongers/Masonry-Builder
 */


namespace Foundry\Masonry\Builder\Notification;


/**
 * Class Notification
 *
 * @package Masonry-Builder
 * @see       https://github.com/TheFoundryVisionmongers/Masonry-Builder
 */
class Notification implements NotificationInterface
{

    /**
     * The notification message
     * @var int
     */
    protected $message;

    /**
     * The priority of the notification
     * Lower number is higher priority.
     * @var string
     */
    protected $priority;

    /**
     * AbstractNotification constructor.
     * @param string $message  The message to display
     * @param int    $priority Message priority, lower number is higher priority
     * @throws \InvalidArgumentException
     */
    public function __construct($message, $priority = 1)
    {
        if(!is_int($priority)) {
            throw new \InvalidArgumentException('$priority must be an integer');
        }
        if($priority < 0) {
            throw new \InvalidArgumentException('$priority greater than or equal to 0');
        }
        if(!is_string($message)) {
            throw new \InvalidArgumentException('$message must be a string');
        }
        $this->priority = $priority;
        $this->message  = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return string
     */
    protected function __toString()
    {
        return $this->getMessage();
    }

}