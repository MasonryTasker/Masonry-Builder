<?php
/**
 * GenericWorkerTestCase.php
 * PHP version 5.4
 * 2015-10-01
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers;

use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use Foundry\Masonry\Builder\Workers\GenericWorker;
use Foundry\Masonry\Core\Task;
use React\Promise\Promise;

/**
 * Class GenericWorkerTestCase
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\GenericWorker
 * @package PhpUnit\Workers
 */
abstract class GenericWorkerTestCase extends TestCase
{

    public function testProcess()
    {
        /** @var Task|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this
            ->getMockBuilder(Task::class)
            ->disableOriginalConstructor()
            ->getMock();

        $generator = function() { yield; };

        /** @var GenericWorker|\PHPUnit_Framework_MockObject_MockObject $worker */
        $worker = $this
            ->getMockBuilder(GenericWorker::class)
            ->setMethods([
                'isTaskDescriptionValid',
                'processDeferred'
            ])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $worker
            ->expects($this->exactly(2))
            ->method('isTaskDescriptionValid')
            ->with($task)
            ->will($this->onConsecutiveCalls(true, false));
        $worker
            ->expects($this->once())
            ->method('processDeferred')
            ->will($this->returnValue($generator()));

        // This should not succeed, but not fail either
        $promise = $worker->process($task);
        $this->assertInstanceOf(
            Promise::class,
            $promise
        );

        $isSuccess = false;
        $isFailure = false;

        $successCallback = function () use (&$isSuccess) {
            $isSuccess = true;
        };

        $failureCallback = function () use (&$isFailure) {
            $isFailure = true;
        };

        $promise->then(
            $successCallback,
            $failureCallback
        );

        $this->assertFalse(
            $isSuccess
        );
        $this->assertFalse(
            $isFailure
        );

        // This should fail
        $promise = $worker->process($task);
        $this->assertInstanceOf(
            Promise::class,
            $promise
        );

        $isSuccess = false;
        $isFailure = false;

        $successCallback = function () use (&$isSuccess) {
            $isSuccess = true;

        };
        $failureCallback = function () use (&$isFailure) {
            $isFailure = true;

        };

        $promise->then(
            $successCallback,
            $failureCallback
        );

        $this->assertFalse(
            $isSuccess
        );
        $this->assertTrue(
            $isFailure
        );
    }
}
