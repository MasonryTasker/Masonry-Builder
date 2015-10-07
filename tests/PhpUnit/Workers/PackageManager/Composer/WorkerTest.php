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

use Foundry\Masonry\Builder\Helpers\Environment;
use Foundry\Masonry\Builder\Tests\PhpUnit\Workers\GenericWorkerTestCase;
use Foundry\Masonry\Builder\Workers\PackageManager\Composer\Worker;
use Foundry\Masonry\Builder\Workers\PackageManager\Composer\Description;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;
use Composer\Console\Application as Composer;
use org\bovigo\vfs\vfsStream;
use React\Promise\Deferred;

/**
 * Class WorkerTest
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\PackageManager\Composer\Worker
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
     * @uses Foundry\Masonry\Builder\Workers\PackageManager\Composer\Worker::getDescriptionTypes
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
     * @covers ::setComposerHome
     * @uses Foundry\Masonry\Builder\Helpers\Environment
     * @uses Foundry\Masonry\Builder\Helpers\EnvironmentTrait
     * @uses Foundry\Masonry\Builder\Workers\PackageManager\Composer\Worker::getEnvironment
     * @uses Foundry\Masonry\Builder\Workers\PackageManager\Composer\Worker::setEnvironment
     * @return void
     */
    public function testSetComposerHome()
    {
        $variableName = 'COMPOSER_HOME';

        $worker = new Worker();
        $setComposerHome = $this->getObjectMethod($worker, 'setComposerHome');

        // Test default
        $this->assertSame(
            $worker,
            $setComposerHome()
        );

        $this->assertSame(
            sys_get_temp_dir() . '/composer_home',
            getenv($variableName)
        );

        // Test manually configuring
        $fileSystem = vfsStream::setup('root');

        $this->assertSame(
            $worker,
            $setComposerHome($fileSystem->url())
        );

        $this->assertSame(
            $fileSystem->url(),
            getenv($variableName)
        );
    }

    /**
     * @test
     * @covers ::setComposerHome
     * @uses Foundry\Masonry\Builder\Helpers\Environment
     * @uses Foundry\Masonry\Builder\Helpers\EnvironmentTrait
     * @uses Foundry\Masonry\Builder\Workers\PackageManager\Composer\Worker::getEnvironment
     * @uses Foundry\Masonry\Builder\Workers\PackageManager\Composer\Worker::setEnvironment
     * @expectedException \Exception
     * @expectedExceptionMessage Could not set COMPOSER_HOME environment variable.
     * @return void
     */
    public function testSetComposerHomeException()
    {
        $variableName = 'COMPOSER_HOME';

        $worker = new Worker();
        $setComposerHome = $this->getObjectMethod($worker, 'setComposerHome');

        $fileSystem = vfsStream::setup('root');

        /** @var Environment|\PHPUnit_Framework_MockObject_MockObject $environment */
        $environment = $this->getMock(Environment::class);
        $environment
            ->expects($this->once())
            ->method('set')
            ->with($variableName, $fileSystem->url())
            ->will($this->returnValue(false));
        $worker->setEnvironment($environment);

        // Test exception
        $setComposerHome($fileSystem->url());
    }

    /**
     * @test
     * @covers ::setComposerHome
     * @expectedException \Exception
     * @expectedExceptionMessage Could not create composer home
     * @return void
     */
    public function testSetComposerHomeDirectoryException()
    {
        $worker = new Worker();
        $setComposerHome = $this->getObjectMethod($worker, 'setComposerHome');

        // Test exception
        $fileSystem = vfsStream::setup('root', 0000);
        $setComposerHome($fileSystem->url().'/not-a-directory');
    }

    /**
     * @test
     * @covers ::setComposer
     * @return void
     */
    public function testSetComposer()
    {
        $worker = $this->getWorker();

        $composer = new Composer();

        $worker->setComposer($composer);

        $this->assertSame(
            $composer,
            $this->getObjectAttribute($worker, 'composer')
        );
    }

    /**
     * @test
     * @covers ::getComposer
     * @uses Foundry\Masonry\Builder\Workers\PackageManager\Composer\Worker::setComposer
     * @return void
     */
    public function testGetComposer()
    {
        $worker = $this->getWorker();

        $composer = new Composer();
        $getComposer = $this->getObjectMethod($worker, 'getComposer');

        // Default
        $this->assertInstanceOf(
            Composer::class,
            $getComposer()
        );

        $this->assertNotSame(
            $composer,
            $getComposer()
        );

        // Inject
        $worker->setComposer($composer);

        $this->assertInstanceOf(
            Composer::class,
            $getComposer()
        );

        $this->assertSame(
            $composer,
            $getComposer()
        );
    }

//    public function testProcessDeferred()
//    {
//        // Set up the deferred so we can see whats happening
//        $successMessage = '';
//        $failureMessage = '';
//        $notifyMessage = '';
//
//        $successClosure = function ($message) use (&$successMessage) {
//            $successMessage = $message;
//        };
//        $failureClosure = function ($message) use (&$failureMessage) {
//            $failureMessage = $message;
//        };
//        $notifyClosure = function ($message) use (&$notifyMessage) {
//            $notifyMessage = $message;
//        };
//
//        $deferred = new Deferred();
//        $deferred->promise()->then(
//            $successClosure,
//            $failureClosure,
//            $notifyClosure
//        );
//
//        // Mocks
//        $worker = new Worker();
//
//        $composer = $this
//            ->getMockBuilder(Composer::class)
//            ->disableOriginalConstructor()
//            ->disableProxyingToOriginalMethods()
//            ->getMockForAbstractClass();
//        $composer
//            ->expects($this->any())
//            ->method('run')
//            ->with($this->anything())
//            ->will($this->returnValue(true));
//        $worker->setComposer($composer);
//
//        $fileSystem = vfsStream::setup('root');
//        $fileSystem->addChild(vfsStream::create(['composer.json' => '']));
//
//        $task = new Task(
//            new Description(
//                'install',
//                $fileSystem->url()
//            )
//        );
//
//
//        // Tests
//        $processDeferred = $this->getObjectMethod($worker, 'processDeferred');
//        $this->assertTrue(
//            $processDeferred($deferred, $task),
//            $failureMessage
//        );
//    }
}
