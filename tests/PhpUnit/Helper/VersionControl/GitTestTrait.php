<?php
/**
 * GitTestTrait.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Helper\VersionControl;

use Foundry\Masonry\Builder\Helper\VersionControl\Git;
use Foundry\Masonry\Builder\Helper\VersionControl\GitTrait;
use Foundry\Masonry\Builder\Tests\PhpUnit\Helper\TestTrait;


/**
 * Trait GitTestTrait
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
trait GitTestTrait
{
    use TestTrait;

    /**
     *
     * @return GitTrait
     */
    protected abstract function getTestSubject();

    /**
     * @covers ::setGit
     * @return void
     */
    public function testSetGit()
    {
        $environmentUser = $this->getTestSubject();

        $environment = new Git();
        $environmentUser->setGit($environment);

        $this->assertSame(
            $environment,
            $this->getObjectAttribute($environmentUser, 'environment')
        );
    }

    /**
     * @covers ::getGit
     * @uses Foundry\Masonry\Builder\Helper\GitTrait::setGit
     * @return void
     */
    public function testGetGit()
    {
        $environmentUser = $this->getTestSubject();

        $environment = new Git();

        $getGit = $this->getObjectMethod($environmentUser, 'getGit');

        $this->assertInstanceOf(
            Git::class,
            $getGit()
        );

        $this->assertNotSame(
            $environment,
            $getGit()
        );

        $environmentUser->setGit($environment);

        $this->assertInstanceOf(
            Git::class,
            $getGit()
        );

        $this->assertSame(
            $environment,
            $getGit()
        );
    }
}