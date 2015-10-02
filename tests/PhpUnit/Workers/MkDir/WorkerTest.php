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


namespace PhpUnit\Workers\MkDir;

use Foundry\Masonry\Builder\Tests\PhpUnit\Workers\GenericWorkerTestCase;
use Foundry\Masonry\Builder\Workers\MkDir\Worker;
use Foundry\Masonry\Builder\Workers\MkDir\Description;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;
use org\bovigo\vfs\vfsStream;
use React\Promise\Deferred;

/**
 * Class WorkerTest
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\MkDir\Worker
 * @package PhpUnit\Workers\MkDir
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
     * @uses Foundry\Masonry\Builder\Workers\MkDir\Worker::getDescriptionTypes
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
     * @uses Foundry\Masonry\Builder\Workers\MkDir\Description
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
        $testDir = 'root/test/dir';

        $root = vfsStream::setup('root', 0777);
        $description = new Description(vfsStream::url($testDir));
        $task = new Task($description);
        $worker = new Worker();

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        // The tests
        $this->assertFalse(
            $root->hasChild($testDir)
        );

        $this->assertTrue(
            $processDeferred($deferred, $task),
            $failureMessage
        );

        $this->assertTrue(
            $root->hasChild($testDir)
        );

        // Test messages
        $this->assertSame(
            "Created directory 'vfs://{$testDir}'",
            $successMessage
        );

        $this->assertSame(
            "",
            $failureMessage
        );

        $this->assertSame(
            "Creating directory 'vfs://{$testDir}'",
            $notifyMessage
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\MkDir\Description
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
        $testDir = 'root/test/dir';

        $root = vfsStream::setup('root', 0000);
        $description = new Description(vfsStream::url($testDir));
        $task = new Task($description);
        $worker = new Worker();

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        // The tests
        $this->assertFalse(
            $root->hasChild($testDir)
        );

        $this->assertFalse(
            $processDeferred($deferred, $task),
            $successMessage
        );

        $this->assertFalse(
            $root->hasChild($testDir)
        );

        // Test messages
        $this->assertSame(
            "",
            $successMessage
        );

        $this->assertSame(
            "Directory 'vfs://{$testDir}' could not be created",
            $failureMessage
        );

        $this->assertSame(
            "Creating directory 'vfs://{$testDir}'",
            $notifyMessage
        );
    }

}