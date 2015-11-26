<?php
/**
 * NotificationInterface.php
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/TheFoundryVisionmongers/Masonry-Builder
 */


namespace Foundry\Masonry\Builder\Notification;


/**
 * Interface NotificationInterface
 *
 * @package Masonry-Builder
 * @see       https://github.com/TheFoundryVisionmongers/Masonry-Builder
 */
interface NotificationInterface
{

    /**
     * The contents of the notification
     * @return string
     */
    public function getMessage();

    /**
     * Get the importance of the notification.
     * Lower numbers are better, 0 will always show.
     * @return int
     */
    public function getPriority();

}