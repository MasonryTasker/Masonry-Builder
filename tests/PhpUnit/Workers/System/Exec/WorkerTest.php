<?php
/**
 * WorkerTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\System\Exec;

use Foundry\Masonry\Builder\Helper\System;
use Foundry\Masonry\Builder\Helper\System\ExecProcess;
use Foundry\Masonry\Builder\Tests\PhpUnit\Helper\SystemTestTrait;
use Foundry\Masonry\Builder\Tests\PhpUnit\Workers\GenericWorkerTestCase;
use Foundry\Masonry\Builder\Workers\System\Exec\Description;
use Foundry\Masonry\Builder\Workers\System\Exec\Worker;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;
use React\Promise\Deferred;


/**
 * Class WorkerTest
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\System\Exec\Worker
 */
class WorkerTest extends GenericWorkerTestCase
{
    use SystemTestTrait;

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
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Worker::getDescriptionTypes
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
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description
     * @uses Foundry\Masonry\Builder\Helper\SystemTrait
     * @uses Foundry\Masonry\Builder\Notification\Notification
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Worker::debugNotifyProcess
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
        $command = 'command';
        $argument = 'argument';

        /** @var ExecProcess|\PHPUnit_Framework_MockObject_MockObject $process */
        $process = $this
            ->getMockBuilder(ExecProcess::class)
            ->disableOriginalConstructor()
            ->getMock();
        $process
            ->expects($this->once())
            ->method('getOutputArray')
            ->will($this->returnValue([]));
        $process
            ->expects($this->once())
            ->method('getErrorArray')
            ->will($this->returnValue([]));
        $process
            ->expects($this->once())
            ->method('getExitCode')
            ->will($this->returnValue(0));

        /** @var System|\PHPUnit_Framework_MockObject_MockObject $system */
        $system = $this->getMock(System::class);
        $system
            ->expects($this->once())
            ->method('execAsynchronous')
            ->with($this->fixShellArgumentQuotes('command "argument"'))
            ->will($this->returnValue($process)); // exit code

        $description = new Description("$command $argument");

        $task = new Task($description);
        $worker = $this->getTestSubject();
        $worker->setSystem($system);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        /** @var \Generator $generator */
        $generator = $processDeferred($deferred, $task);
        while($generator->valid()) {
            $generator->next();
        }

        // Test messages
        $this->assertSame(
            "Executed '{$description}'",
            (string)$successMessage
        );

        $this->assertSame(
            "",
            (string)$failureMessage
        );

        $this->assertSame(
            "Executing '{$description}'",
            (string)$notifyMessage
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description
     * @uses Foundry\Masonry\Builder\Helper\SystemTrait
     * @uses Foundry\Masonry\Builder\Notification\Notification
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Worker::debugNotifyProcess
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
        $command = 'command';
        $argument = 'argument';

        /** @var ExecProcess|\PHPUnit_Framework_MockObject_MockObject $process */
        $process = $this
            ->getMockBuilder(ExecProcess::class)
            ->disableOriginalConstructor()
            ->getMock();
        $process
            ->expects($this->once())
            ->method('getOutputArray')
            ->will($this->returnValue([]));
        $process
            ->expects($this->once())
            ->method('getErrorArray')
            ->will($this->returnValue([]));
        $process
            ->expects($this->once())
            ->method('getExitCode')
            ->will($this->returnValue(1));

        /** @var System|\PHPUnit_Framework_MockObject_MockObject $system */
        $system = $this->getMock(System::class);
        $system
            ->expects($this->once())
            ->method('execAsynchronous')
            ->with($this->fixShellArgumentQuotes('command "argument"'))
            ->will($this->returnValue($process)); // exit code

        $description = new Description("$command $argument");

        $task = new Task($description);
        $worker = $this->getTestSubject();
        $worker->setSystem($system);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        /** @var \Generator $generator */
        $generator = $processDeferred($deferred, $task);
        while($generator->valid()) {
            $generator->next();
        }

        // Test messages
        $this->assertSame(
            "",
            (string)$successMessage
        );

        $this->assertSame(
            "Failed to execute '{$description}'",
            (string)$failureMessage
        );

        $this->assertSame(
            "Executing '{$description}'",
            (string)$notifyMessage
        );
    }
}
