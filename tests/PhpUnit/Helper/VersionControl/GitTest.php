<?php
/**
 * GitTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Helper\VersionControl;

use Foundry\Masonry\Builder\Helper\System;
use Foundry\Masonry\Builder\Helper\VersionControl\Git;
use Foundry\Masonry\Builder\Tests\PhpUnit\Helper\SystemTestTrait;
use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;

/**
 * Class GitTest
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 * @coversDefaultClass Foundry\Masonry\Builder\Helper\VersionControl\Git
 */
class GitTest extends TestCase
{

    use SystemTestTrait;

    public function getTestSubject()
    {
        return new Git();
    }

    /**
     * @test
     * @covers ::cloneRepository
     * @uses Foundry\Masonry\Builder\Helper\System
     * @uses Foundry\Masonry\Builder\Helper\SystemTrait
     * @return void
     */
    public function testCloneRepository()
    {
        $repository = 'ssh://some-repository.example.com';
        $directory = '/some/directory/example';

        /** @var System|\PHPUnit_Framework_MockObject_MockObject $system */
        $system = $this->getMock(System::class);
        $system->expects($this->once())
            ->method('exec')
            ->with($this->fixShellArgumentQuotes('git "clone" "--recursive" "'.$repository.'" "'.$directory.'"'))
            ->will($this->returnValue(0));

        $git = $this->getTestSubject();
        $git->setSystem($system);

        $this->assertTrue(
            $git->cloneRepository($repository, $directory)
        );
    }

    /**
     * @test
     * @covers ::checkout
     * @uses Foundry\Masonry\Builder\Helper\VersionControl\Git::cloneRepository
     * @uses Foundry\Masonry\Builder\Helper\System
     * @uses Foundry\Masonry\Builder\Helper\SystemTrait
     * @return void
     */
    public function testCheckout()
    {
        $repository = 'ssh://some-repository.example.com';
        $directory = '/some/directory/example';
        $identifier = 'some-branch';

        /** @var System|\PHPUnit_Framework_MockObject_MockObject $system */
        $system = $this->getMock(System::class);
        // Pass
        $system->expects($this->at(0))
            ->method('exec')
            ->with($this->fixShellArgumentQuotes('git "checkout" "--detach" "-C" "'.$directory.'" "'.$identifier.'"'))
            ->will($this->returnValue(0));
        // Pass
        $system->expects($this->at(1))
            ->method('exec')
            ->with($this->fixShellArgumentQuotes('git "clone" "--recursive" "'.$repository.'" "'.$directory.'"'))
            ->will($this->returnValue(0));
        // Pass
        $system->expects($this->at(2))
            ->method('exec')
            ->with($this->fixShellArgumentQuotes('git "checkout" "--detach" "-C" "'.$directory.'" "'.$identifier.'"'))
            ->will($this->returnValue(0));
        // Fail
        $system->expects($this->at(3))
            ->method('exec')
            ->with($this->fixShellArgumentQuotes('git "clone" "--recursive" "'.$repository.'" "'.$directory.'"'))
            ->will($this->returnValue(1));

        $git = $this->getTestSubject();
        $git->setSystem($system);

        $this->assertTrue(
            $git->checkout($directory, null, $identifier)
        );

        $this->assertTrue(
            $git->checkout($directory, $repository, $identifier)
        );

        $this->assertFalse(
            $git->checkout($directory, $repository, $identifier)
        );
    }
}
