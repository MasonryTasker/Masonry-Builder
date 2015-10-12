<?php
/**
 * Worker.php
 * PHP version 5.4
 * 2015-10-01
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\FileSystem\Delete;

use Foundry\Masonry\Builder\Helper\FileSystem;
use Foundry\Masonry\Builder\Tests\PhpUnit\Helper\FileSystemTestTrait;
use Foundry\Masonry\Builder\Tests\PhpUnit\Workers\GenericWorkerTestCase;
use Foundry\Masonry\Builder\Workers\FileSystem\Delete\Worker;
use Foundry\Masonry\Builder\Workers\FileSystem\Delete\Description;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;
use React\Promise\Deferred;

/**
 * Class WorkerTest
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\FileSystem\Delete\Worker
 * @package Foundry\Masonry-Website-Builder
 */
class WorkerTest extends GenericWorkerTestCase
{

    use FileSystemTestTrait;

    /**
     * @return Worker
     */
    protected function getTestSubject()
    {
        return new Worker();
    }

    /**
     * @test
     * @covers ::getDescriptionTypes
     * @return void
     */
    public function testGetDescriptionTypes()
    {
        $worker = $this->getTestSubject();

        $this->assertTrue(
            is_array($worker->getDescriptionTypes())
        );

        $this->assertContains(
            Description::class,
            $worker->getDescriptionTypes()
        );
    }

    /**
     * @test
     * @covers ::isTaskDescriptionValid
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Delete\Worker::getDescriptionTypes
     * @return void
     */
    public function testIsTaskDescriptionValid()
    {
        //
        // Data
        //
        /** @var Description $description */
        $description = $this
            ->getMockBuilder(Description::class)
            ->disableOriginalConstructor()
            ->getMock();
        $validTask = new Task($description);

        /** @var DescriptionInterface $basicDescription */
        $basicDescription = $this
            ->getMockBuilder(DescriptionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $basicTask = new Task($basicDescription);

        //
        // Set up
        //
        $worker = $this->getTestSubject();
        $isTaskDescriptionValid = $this->getObjectMethod($worker, 'isTaskDescriptionValid');

        //
        // Tests
        //
        $this->assertTrue(
            $isTaskDescriptionValid($validTask)
        );
        $this->assertFalse(
            $isTaskDescriptionValid($basicTask)
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Delete\Description
     * @uses Foundry\Masonry\Builder\Helper\FileSystemTrait
     * @return void
     */
    public function testProcessDeferredSuccess()
    {
        // Set up the deferred so we can see whats happening
        $successMessage = '';
        $failureMessage = '';
        $notifyMessage = '';

        $successClosure = function ($message) use (&$successMessage) {
            $successMessage = $message;
        };
        $failureClosure = function ($message) use (&$failureMessage) {
            $failureMessage = $message;
        };
        $notifyClosure  = function ($message) use (&$notifyMessage) {
            $notifyMessage  = $message;
        };

        $deferred = new Deferred();
        $deferred->promise()->then(
            $successClosure,
            $failureClosure,
            $notifyClosure
        );

        // The rest of test data
        $testFile = 'schema://root/test';

        /** @var FileSystem|\PHPUnit_Framework_MockObject_MockObject $fileSystem */
        $fileSystem = $this->getMock(FileSystem::class);
        $fileSystem
            ->expects($this->once())
            ->method('delete')
            ->with($testFile)
            ->will($this->returnValue(true));

        $description = new Description($testFile);
        $task = new Task($description);
        $worker = $this->getTestSubject();
        $worker->setFileSystem($fileSystem);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        // The tests

        $this->assertTrue(
            $processDeferred($deferred, $task),
            $failureMessage
        );

        // Test messages
        $this->assertSame(
            "Deleted file or directory '{$testFile}'",
            $successMessage
        );

        $this->assertSame(
            "",
            $failureMessage
        );

        $this->assertSame(
            "Deleting file or directory '{$testFile}'",
            $notifyMessage
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Delete\Description
     * @uses Foundry\Masonry\Builder\Helper\FileSystemTrait
     * @return void
     */
    public function testProcessDeferredFailure()
    {
        // Set up the deferred so we can see whats happening
        $successMessage = '';
        $failureMessage = '';
        $notifyMessage = '';

        $successClosure = function ($message) use (&$successMessage) {
            $successMessage = $message;
        };
        $failureClosure = function ($message) use (&$failureMessage) {
            $failureMessage = $message;
        };
        $notifyClosure  = function ($message) use (&$notifyMessage) {
            $notifyMessage  = $message;
        };

        $deferred = new Deferred();
        $deferred->promise()->then(
            $successClosure,
            $failureClosure,
            $notifyClosure
        );

        // The rest of test data
        $testFile = 'schema://root/test';

        /** @var FileSystem|\PHPUnit_Framework_MockObject_MockObject $fileSystem */
        $fileSystem = $this->getMock(FileSystem::class);
        $fileSystem
            ->expects($this->once())
            ->method('delete')
            ->with($testFile)
            ->will($this->returnValue(false));

        $description = new Description($testFile);
        $task = new Task($description);
        $worker = $this->getTestSubject();
        $worker->setFileSystem($fileSystem);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        // The tests
        $this->assertFalse(
            $processDeferred($deferred, $task),
            $successMessage
        );
        // Test messages
        $this->assertSame(
            "",
            $successMessage
        );

        $this->assertSame(
            "File or directory '{$testFile}' could not be deleted",
            $failureMessage
        );

        $this->assertSame(
            "Deleting file or directory '{$testFile}'",
            $notifyMessage
        );
    }
}
