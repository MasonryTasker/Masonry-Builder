<?php
/**
 * WorkerTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\VersionControl\Git\CloneRepository;

use Foundry\Masonry\Builder\Helper\VersionControl\Git;
use Foundry\Masonry\Builder\Tests\PhpUnit\Helper\VersionControl\GitTestTrait;
use Foundry\Masonry\Builder\Tests\PhpUnit\Workers\GenericWorkerTestCase;
use Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository\Description;
use Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository\Worker;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;
use React\Promise\Deferred;

/**
 * Class WorkerTest
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository\Worker
 */
class WorkerTest extends GenericWorkerTestCase
{

    use GitTestTrait;

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
     * @uses Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository\Worker::getDescriptionTypes
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
     * @uses Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository\Description
     * @uses Foundry\Masonry\Builder\Helper\VersionControl\GitTrait
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
        $testFrom = 'schema://root/test.git';
        $testTo = 'schema://root/workingCopy';

        /** @var Git|\PHPUnit_Framework_MockObject_MockObject $git */
        $git = $this->getMock(Git::class);
        $git->expects($this->once())
            ->method('cloneRepository')
            ->with($testFrom, $testTo)
            ->will($this->returnValue(true));

        $description = new Description($testFrom, $testTo);

        $task = new Task($description);
        $worker = $this->getTestSubject();
        $worker->setGit($git);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        /** @var \Generator $generator */
        $generator = $processDeferred($deferred, $task);
        while($generator->valid()) {
            $generator->next();
        }

        // Test messages
        $this->assertSame(
            "Cloned '{$testFrom}' to '{$testTo}'",
            $successMessage
        );

        $this->assertSame(
            "",
            $failureMessage
        );

        $this->assertSame(
            "Cloning '{$testFrom}' to '{$testTo}'",
            $notifyMessage
        );
    }

    /**
     * @test
     * @covers ::processDeferred
     * @uses Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository\Description
     * @uses Foundry\Masonry\Builder\Helper\VersionControl\GitTrait
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
        $testFrom = 'schema://root/test.git';
        $testTo = 'schema://root/workingCopy';

        /** @var Git|\PHPUnit_Framework_MockObject_MockObject $git */
        $git = $this->getMock(Git::class);
        $git->expects($this->once())
            ->method('cloneRepository')
            ->with($testFrom, $testTo)
            ->will($this->throwException(new \Exception()));

        $description = new Description($testFrom, $testTo);

        $task = new Task($description);
        $worker = $this->getTestSubject();
        $worker->setGit($git);

        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');

        /** @var \Generator $generator */
        $generator = $processDeferred($deferred, $task);
        while($generator->valid()) {
            $generator->next();
        }

        // Test messages
        $this->assertSame(
            "",
            $successMessage
        );

        $this->assertSame(
            "Could not clone '{$testFrom}' to '{$testTo}'",
            $failureMessage
        );

        $this->assertSame(
            "Cloning '{$testFrom}' to '{$testTo}'",
            $notifyMessage
        );
    }
}
