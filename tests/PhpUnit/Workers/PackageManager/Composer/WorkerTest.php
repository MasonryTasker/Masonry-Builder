<?php
/**
 * WorkerTest.php
 * PHP version 5.4
 * 2015-10-05
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace PhpUnit\Workers\PackageManager\Composer;

use Foundry\Masonry\Builder\Tests\PhpUnit\Workers\GenericWorkerTestCase;
use Foundry\Masonry\Builder\Workers\FileSystem\Copy\Worker;
use Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;

/**
 * Class WorkerTest
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\FileSystem\Copy\Worker
 * @package PhpUnit\Workers\PackageManager\Composer
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
}
