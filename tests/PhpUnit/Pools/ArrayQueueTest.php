<?php
/**
 * ArrayQueueTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Pools;

use Foundry\Masonry\Builder\Pools\ArrayQueue;
use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Pool\StatusInterface;


/**
 * Class ArrayQueueTest
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 * @coversDefaultClass Foundry\Masonry\Builder\Pools\ArrayQueue
 */
class ArrayQueueTest extends TestCase
{

    /**
     * If we put no tasks into the queue, the queue should still instantiate but it will be empty
     * @test
     * @covers ::__construct
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::addTasks
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::addTask
     * @return void
     */
    public function testConstructEmpty()
    {
        $arrayQueue = new ArrayQueue();

        /** @var \SplQueue $splQueue */
        $splQueue = $this->getObjectAttribute($arrayQueue, 'queue');

        $this->assertInstanceOf(
            \SplQueue::class,
            $splQueue
        );

        $this->assertTrue(
            $splQueue->isEmpty()
        );
    }

    /**
     * If we put two tasks in, the first task in the array should be the first out
     * @test
     * @covers ::__construct
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::addTasks
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::addTask
     * @return void
     */
    public function testConstructItems()
    {
        $task1 = $this
            ->getMockBuilder(Task::class)
            ->disableOriginalConstructor()
            ->getMock();
        $task2 = $this
            ->getMockBuilder(Task::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tasks = [
            $task1,
            $task2,
        ];

        $arrayQueue = new ArrayQueue($tasks);

        /** @var \SplQueue $splQueue */
        $splQueue = $this->getObjectAttribute($arrayQueue, 'queue');

        $this->assertInstanceOf(
            \SplQueue::class,
            $splQueue
        );

        $this->assertFalse(
            $splQueue->isEmpty()
        );

        $firstTask = $splQueue->dequeue();

        $this->assertSame(
            $task1,
            $firstTask
        );

        $this->assertNotSame(
            $task2,
            $firstTask
        );
    }

    /**
     * @test
     * @covers ::addTasks
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::addTask
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::__construct
     * @return void
     */
    public function testAddTasks()
    {
        $task1 = $this
            ->getMockBuilder(Task::class)
            ->disableOriginalConstructor()
            ->getMock();
        $task2 = $this
            ->getMockBuilder(Task::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tasks = [
            $task1,
            $task2,
        ];

        $arrayQueue = new ArrayQueue();

        /** @var \SplQueue $splQueue */
        $splQueue = $this->getObjectAttribute($arrayQueue, 'queue');

        $this->assertTrue(
            $splQueue->isEmpty()
        );

        $arrayQueue->addTasks($tasks);

        $this->assertFalse(
            $splQueue->isEmpty()
        );
    }

    /**
     * @test
     * @covers ::addTasks
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::__construct
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Array contains non-task element at index '0'
     * @return void
     */
    public function testAddTasksException()
    {
        $arrayQueue = new ArrayQueue();

        $tasks = ['Not a task'];

        $arrayQueue->addTasks($tasks);
    }

    /**
     * @test
     * @covers ::addTask
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::addTasks
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::__construct
     * @return void
     */
    public function testAddTask()
    {
        /** @var Task|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this
            ->getMockBuilder(Task::class)
            ->disableOriginalConstructor()
            ->getMock();

        $arrayQueue = new ArrayQueue();

        /** @var \SplQueue $splQueue */
        $splQueue = $this->getObjectAttribute($arrayQueue, 'queue');

        $this->assertTrue(
            $splQueue->isEmpty()
        );

        $arrayQueue->addTask($task);

        $this->assertFalse(
            $splQueue->isEmpty()
        );
    }

    /**
     * @test
     * @covers ::getTask
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::addTask
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::addTasks
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::__construct
     * @return void
     */
    public function testGetTask()
    {
        $task1 = $this
            ->getMockBuilder(Task::class)
            ->disableOriginalConstructor()
            ->getMock();
        $task2 = $this
            ->getMockBuilder(Task::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tasks = [
            $task1,
            $task2,
        ];

        $arrayQueue = new ArrayQueue($tasks);

        $firstTask = $arrayQueue->getTask();

        $this->assertSame(
            $task1,
            $firstTask
        );
        $this->assertNotSame(
            $task2,
            $firstTask
        );

        $secondTask = $arrayQueue->getTask();

        $this->assertSame(
            $task2,
            $secondTask
        );
        $this->assertNotSame(
            $task1,
            $secondTask
        );
    }

    /**
     * @task
     * @covers ::getStatus
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::addTask
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::addTasks
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::getTask
     * @uses Foundry\Masonry\Builder\Pools\ArrayQueue::__construct
     * @return void
     */
    public function testGetStatus()
    {
        /** @var Task|\PHPUnit_Framework_MockObject_MockObject $task */
        $task = $this
            ->getMockBuilder(Task::class)
            ->disableOriginalConstructor()
            ->getMock();

        $arrayQueue = new ArrayQueue();

        $this->assertEquals(
            StatusInterface::STATUS_EMPTY,
            $arrayQueue->getStatus()
        );

        // Add a task
        $arrayQueue->addTask($task);

        $this->assertEquals(
            StatusInterface::STATUS_PENDING,
            $arrayQueue->getStatus()
        );

        // Remove the task again
        $arrayQueue->getTask();

        $this->assertEquals(
            StatusInterface::STATUS_EMPTY,
            $arrayQueue->getStatus()
        );
    }
}