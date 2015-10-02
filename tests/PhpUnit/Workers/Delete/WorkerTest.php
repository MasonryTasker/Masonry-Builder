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


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\Delete;

use Foundry\Masonry\Builder\Tests\PhpUnit\Workers\GenericWorkerTestCase;
use Foundry\Masonry\Builder\Workers\Delete\Worker;
use Foundry\Masonry\Builder\Workers\Delete\Description;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;
use org\bovigo\vfs\vfsStream;
use React\Promise\Deferred;

/**
 * Class WorkerTest
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\Delete\Worker
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
     * @uses Foundry\Masonry\Builder\Workers\Delete\Worker::getDescriptionTypes
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
     * @uses Foundry\Masonry\Builder\Workers\Delete\Description
     * @return void
     */
    public function testProcessDeferredSuccess()
    {
        // Set up the deferred so we can see whats happening
        $successMessage = '';
        $failureMessage = '';
        $notifyMessage = '';

        $successClosure = function ($message) use (&$successMessage) { $successMessage = $message; };
        $failureClosure = function ($message) use (&$failureMessage) { $failureMessage = $message; };
        $notifyClosure  = function ($message) use (&$notifyMessage)  { $notifyMessage  = $message; };

        $deferred = new Deferred();
        $deferred->promise()->then(
            $successClosure,
            $failureClosure,
            $notifyClosure
        );

        // The rest of test data
        $testFile = 'root/test/file';

        $root = vfsStream::setup('root', 0777);
        $root->addChild(vfsStream::create([ 'test' => ['file' => 'test file']]));
        $description = new Description(vfsStream::url($testFile));
        $task = new Task($description);
        $worker = new Worker();

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        // The tests
        $this->assertTrue(
            $root->hasChild($testFile)
        );

        $this->assertTrue(
            $processDeferred($deferred, $task),
            $failureMessage
        );

        $this->assertFalse(
            $root->hasChild($testFile)
        );

        // Test messages
        $this->assertSame(
            "Deleted file or directory 'vfs://{$testFile}'",
            $successMessage
        );

        $this->assertSame(
            "",
            $failureMessage
        );

        $this->assertSame(
            "Deleting file or directory 'vfs://{$testFile}'",
            $notifyMessage
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\Delete\Description
     * @return void
     */
    public function testProcessDeferredFailure()
    {
        // Set up the deferred so we can see whats happening
        $successMessage = '';
        $failureMessage = '';
        $notifyMessage = '';

        $successClosure = function ($message) use (&$successMessage) { $successMessage = $message; };
        $failureClosure = function ($message) use (&$failureMessage) { $failureMessage = $message; };
        $notifyClosure  = function ($message) use (&$notifyMessage)  { $notifyMessage  = $message; };

        $deferred = new Deferred();
        $deferred->promise()->then(
            $successClosure,
            $failureClosure,
            $notifyClosure
        );

        // The rest of test data
        $testFile = 'root/test/file';

        $root = vfsStream::setup('root', 0777);
        $root->addChild(vfsStream::create([ 'test' => ['file' => 'test file']]));
        $description = new Description(vfsStream::url($testFile));
        $task = new Task($description);
        $worker = new Worker();

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        // The tests
        $this->assertTrue(
            $root->hasChild($testFile)
        );

        $this->assertTrue(
            $processDeferred($deferred, $task),
            $failureMessage
        );

        $this->assertFalse(
            $root->hasChild($testFile)
        );

        // Test messages
        $this->assertSame(
            "Deleted file or directory 'vfs://{$testFile}'",
            $successMessage
        );

        $this->assertSame(
            "",
            $failureMessage
        );

        $this->assertSame(
            "Deleting file or directory 'vfs://{$testFile}'",
            $notifyMessage
        );
    }

}