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

        /** @var System|\PHPUnit_Framework_MockObject_MockObject $git */
        $git = $this->getMock(System::class);
        $git->expects($this->once())
            ->method('exec')
            ->with('command "argument"')
            ->will($this->returnValue(0)); // exit code

        $description = new Description("$command $argument");

        $task = new Task($description);
        $worker = $this->getTestSubject();
        $worker->setSystem($git);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        $this->assertTrue(
            $processDeferred($deferred, $task),
            $failureMessage
        );


        // Test messages
        $this->assertSame(
            "Executed '{$description}'",
            $successMessage
        );

        $this->assertSame(
            "",
            $failureMessage
        );

        $this->assertSame(
            "Executing '{$description}'",
            $notifyMessage
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description
     * @uses Foundry\Masonry\Builder\Helper\SystemTrait
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

        /** @var System|\PHPUnit_Framework_MockObject_MockObject $git */
        $git = $this->getMock(System::class);
        $git->expects($this->once())
            ->method('exec')
            ->with('command "argument"')
            ->will($this->returnValue(1)); // exit code

        $description = new Description("$command $argument");

        $task = new Task($description);
        $worker = $this->getTestSubject();
        $worker->setSystem($git);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        $this->assertFalse(
            $processDeferred($deferred, $task),
            $failureMessage
        );


        // Test messages
        $this->assertSame(
            "",
            $successMessage
        );

        $this->assertSame(
            "Failed to execute '{$description}'",
            $failureMessage
        );

        $this->assertSame(
            "Executing '{$description}'",
            $notifyMessage
        );
    }
}