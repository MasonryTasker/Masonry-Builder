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
     * @covers Foundry\Masonry\Builder\Helper\VersionControl\GitTrait::setGit
     * @return void
     */
    public function testSetGit()
    {
        $gitUser = $this->getTestSubject();

        $git = new Git();
        $gitUser->setGit($git);

        $this->assertSame(
            $git,
            $this->getObjectAttribute($gitUser, 'git')
        );
    }

    /**
     * @covers Foundry\Masonry\Builder\Helper\VersionControl\GitTrait::getGit
     * @uses Foundry\Masonry\Builder\Helper\VersionControl\GitTrait::setGit
     * @return void
     */
    public function testGetGit()
    {
        $gitUser = $this->getTestSubject();

        $git = new Git();

        $getGit = $this->getObjectMethod($gitUser, 'getGit');

        $this->assertInstanceOf(
            Git::class,
            $getGit()
        );

        $this->assertNotSame(
            $git,
            $getGit()
        );

        $gitUser->setGit($git);

        $this->assertInstanceOf(
            Git::class,
            $getGit()
        );

        $this->assertSame(
            $git,
            $getGit()
        );
    }
}
