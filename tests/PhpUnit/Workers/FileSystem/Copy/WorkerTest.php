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


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\FileSystem\Copy;

use Foundry\Masonry\Builder\Helper\FileSystem;
use Foundry\Masonry\Builder\Tests\PhpUnit\Workers\GenericWorkerTestCase;
use Foundry\Masonry\Builder\Workers\FileSystem\Copy\Worker;
use Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;
use React\Promise\Deferred;

/**
 * Class WorkerTest
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\FileSystem\Copy\Worker
 * @package Foundry\Masonry-Website-Builder
 */
class WorkerTest extends GenericWorkerTestCase
{

    /**
     * @return Worker
     */
    protected function getWorker()
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
        $worker = $this->getWorker();

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
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Worker::getDescriptionTypes
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
        $worker = $this->getWorker();
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
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description
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
        $notifyClosure = function ($message) use (&$notifyMessage) {
            $notifyMessage = $message;

        };

        $deferred = new Deferred();
        $deferred->promise()->then(
            $successClosure,
            $failureClosure,
            $notifyClosure
        );

        // The rest of test data
        $testFrom = 'schema://root/test';
        $testTo = 'schema://root/test-copy';

        /** @var FileSystem|\PHPUnit_Framework_MockObject_MockObject $fileSystem */
        $fileSystem = $this->getMock(FileSystem::class);
        $fileSystem
            ->expects($this->once())
            ->method('copy')
            ->with($testFrom, $testTo)
            ->will($this->returnValue(true));

        $description = new Description($testFrom, $testTo);

        $task = new Task($description);
        $worker = new Worker();
        $worker->setFileSystem($fileSystem);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');


        $this->assertTrue(
            $processDeferred($deferred, $task),
            $failureMessage
        );


        // Test messages
        $this->assertSame(
            "Copied '{$testFrom}' to '{$testTo}'",
            $successMessage
        );

        $this->assertSame(
            "",
            $failureMessage
        );

        $this->assertSame(
            "Copying '{$testFrom}' to '{$testTo}'",
            $notifyMessage
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description
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
        $notifyClosure = function ($message) use (&$notifyMessage) {
            $notifyMessage = $message;

        };

        $deferred = new Deferred();
        $deferred->promise()->then(
            $successClosure,
            $failureClosure,
            $notifyClosure
        );

        // The rest of test data
        $testFrom = 'schema://root/test';
        $testTo = 'schema://root/test-copy';

        /** @var FileSystem|\PHPUnit_Framework_MockObject_MockObject $fileSystem */
        $fileSystem = $this->getMock(FileSystem::class);
        $fileSystem
            ->expects($this->once())
            ->method('copy')
            ->with($testFrom, $testTo)
            ->will($this->throwException(new \Exception()));

        $description = new Description($testFrom, $testTo);

        $task = new Task($description);
        $worker = new Worker();
        $worker->setFileSystem($fileSystem);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');


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
            "Could not copy '{$testFrom}' to '{$testTo}'",
            $failureMessage
        );

        $this->assertSame(
            "Copying '{$testFrom}' to '{$testTo}'",
            $notifyMessage
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description
     * @uses Foundry\Masonry\Builder\Helper\FileSystemTrait
     * @return void
     */
    public function testProcessDeferredPartialSuccess()
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
        $notifyClosure = function ($message) use (&$notifyMessage) {
            $notifyMessage = $message;
        };

        $deferred = new Deferred();
        $deferred->promise()->then(
            $successClosure,
            $failureClosure,
            $notifyClosure
        );

        // The rest of test data
        $testFrom = 'schema://root/test';
        $testTo = 'schema://root/test-copy';

        /** @var FileSystem|\PHPUnit_Framework_MockObject_MockObject $fileSystem */
        $fileSystem = $this->getMock(FileSystem::class);
        $fileSystem
            ->expects($this->once())
            ->method('copy')
            ->with($testFrom, $testTo)
            ->will($this->returnValue(false));

        $description = new Description($testFrom, $testTo);

        $task = new Task($description);
        $worker = new Worker();
        $worker->setFileSystem($fileSystem);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        $this->assertTrue(
            $processDeferred($deferred, $task),
            $failureMessage
        );

        // Test messages
        $this->assertSame(
            "Copied '{$testFrom}' to '{$testTo}'",
            $successMessage
        );

        $this->assertSame(
            "",
            $failureMessage
        );

        $this->assertSame(
            "Directory permissions were not applied correctly",
            $notifyMessage
        );
    }
}
