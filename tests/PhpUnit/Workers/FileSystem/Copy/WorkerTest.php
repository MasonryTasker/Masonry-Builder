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

use Foundry\Masonry\Builder\Tests\PhpUnit\Workers\GenericWorkerTestCase;
use Foundry\Masonry\Builder\Workers\FileSystem\Copy\Worker;
use Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;
use org\bovigo\vfs\vfsStream;
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
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Worker::recursiveCopy
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
        $testFrom = 'root/test';
        $testTo = 'root/test-copy';

        $root = vfsStream::setup('root', 0777);
        $root->addChild(vfsStream::create(['test' => ['file' => 'test file']]));
        $description = new Description(
            vfsStream::url($testFrom),
            vfsStream::url($testTo)
        );
        $task = new Task($description);
        $worker = new Worker();

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        // The tests
        $this->assertTrue(
            $root->hasChild($testFrom . '/file')
        );

        $this->assertFalse(
            $root->hasChild($testTo . '/file')
        );

        $this->assertTrue(
            $processDeferred($deferred, $task),
            $failureMessage
        );

        $this->assertTrue(
            $root->hasChild($testFrom . '/file')
        );

        $this->assertTrue(
            $root->hasChild($testTo . '/file')
        );


        // Test messages
        $this->assertSame(
            "Copied 'vfs://{$testFrom}' to 'vfs://{$testTo}'",
            $successMessage
        );

        $this->assertSame(
            "",
            $failureMessage
        );

        $this->assertSame(
            "Copying 'vfs://{$testFrom}' to 'vfs://{$testTo}'",
            $notifyMessage
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Worker::recursiveCopy
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
        $testFrom = 'root/test';
        $testTo = 'root/test-copy';

        $root = vfsStream::setup('root', 0000);
        $root->addChild(vfsStream::create(['test' => ['file' => 'test file']]));
        $description = new Description(
            vfsStream::url($testFrom),
            vfsStream::url($testTo)
        );
        $task = new Task($description);
        $worker = new Worker();

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        // The tests
        $this->assertTrue(
            $root->hasChild($testFrom . '/file')
        );

        $this->assertFalse(
            $root->hasChild($testTo . '/file')
        );

        $this->assertFalse(
            $processDeferred($deferred, $task),
            $successMessage
        );

        $this->assertTrue(
            $root->hasChild($testFrom . '/file')
        );

        $this->assertFalse(
            $root->hasChild($testTo . '/file')
        );


        // Test messages
        $this->assertSame(
            "",
            $successMessage
        );

        $this->assertSame(
            "Could not copy 'vfs://{$testFrom}' to 'vfs://{$testTo}'",
            $failureMessage
        );

        $this->assertSame(
            "Copying 'vfs://{$testFrom}' to 'vfs://{$testTo}'",
            $notifyMessage
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Worker::recursiveCopy
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
        $testFrom = 'root/test';
        $testTo = 'root/test-copy';

        $root = vfsStream::setup('root', 0777);
        $root->addChild(vfsStream::create(['test' => ['file' => 'test file']]));
        $description = new Description(
            vfsStream::url($testFrom),
            vfsStream::url($testTo)
        );
        $task = new Task($description);

        // Mock the worker and replace _just_ the recursiveCopy because I don't know how to make that return false
        $worker = $this->getMock(Worker::class, ['recursiveCopy']);
        $worker
            ->expects($this->once())
            ->method('recursiveCopy')
            ->with(
                vfsStream::url($testFrom),
                vfsStream::url($testTo)
            )
            ->will($this->returnValue(false));

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        $this->assertTrue(
            $processDeferred($deferred, $task),
            $failureMessage
        );

        // Test messages
        $this->assertSame(
            "Copied 'vfs://{$testFrom}' to 'vfs://{$testTo}'",
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

    /**
     * @test
     * @covers ::recursiveCopy
     * @return void
     */
    public function testRecursiveCopy()
    {

        $root = vfsStream::setup('root', 0755);
        $root->addChild(vfsStream::create([
            'from' => [
                'file' => 'file contents',
                'subdirectory' => [
                    'another-file' => 'more file contents',
                ],
            ],
            'to' => [

            ],
        ]));

        $worker = new Worker();

        $this->assertTrue(
            $root->hasChild('from/subdirectory/another-file')
        );
        $this->assertFalse(
            $root->hasChild('to/subdirectory/another-file')
        );

        $recursiveCopy = $this->getObjectMethod($worker, 'recursiveCopy');
        $this->assertTrue(
            $recursiveCopy(
                vfsStream::url('root/from'),
                vfsStream::url('root/to')
            )
        );

        $this->assertTrue(
            $root->hasChild('from/subdirectory/another-file')
        );
        $this->assertTrue(
            $root->hasChild('to/subdirectory/another-file')
        );

    }

    /**
     * @test
     * @covers ::recursiveCopy
     * @expectedException \Exception
     * @expectedExceptionMessage Could not copy file
     * @return void
     */
    public function testRecursiveCopyFailFile()
    {

        $root = vfsStream::setup('root', 0755);
        $root->addChild(vfsStream::create([
            'from' => [
                'file' => 'file contents',
            ],
            'to' => [],
        ]));

        $root->getChild('to')->chmod('0000');

        $worker = new Worker();

        $this->assertTrue(
            $root->hasChild('from/file')
        );
        $this->assertFalse(
            $root->hasChild('to/file')
        );

        $recursiveCopy = $this->getObjectMethod($worker, 'recursiveCopy');

        $recursiveCopy(
            vfsStream::url('root/from'),
            vfsStream::url('root/to')
        );
    }

    /**
     * @test
     * @covers ::recursiveCopy
     * @expectedException \Exception
     * @expectedExceptionMessage Could not create directory
     * @return void
     */
    public function testRecursiveCopyFailDirectory()
    {

        $root = vfsStream::setup('root', 0755);
        $root->addChild(vfsStream::create([
            'from' => [
                'subdirectory' => [
                    'another-file' => 'more file contents',
                ],
            ],
            'to' => [],
        ]));

        $root->getChild('to')->chmod('0000');

        $worker = new Worker();

        $this->assertTrue(
            $root->hasChild('from/subdirectory/another-file')
        );
        $this->assertFalse(
            $root->hasChild('to/subdirectory/another-file')
        );

        $recursiveCopy = $this->getObjectMethod($worker, 'recursiveCopy');

        $recursiveCopy(
            vfsStream::url('root/from'),
            vfsStream::url('root/to')
        );

    }

    /**
     * @test
     * @covers ::recursiveCopy
     * @expectedException \Exception
     * @expectedExceptionMessage does not exist or is not accessible
     * @return void
     */
    public function testRecursiveCopyFailNotExist()
    {

        $root = vfsStream::setup('root', 0755);
        $root->addChild(vfsStream::create([
            'to' => [],
        ]));

        $root->getChild('to')->chmod('0000');

        $worker = new Worker();

        $recursiveCopy = $this->getObjectMethod($worker, 'recursiveCopy');
        $this->assertTrue(
            $recursiveCopy(
                vfsStream::url('root/from'),
                vfsStream::url('root/to')
            )
        );

    }
}
