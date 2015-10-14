<?php
/**
 * FileSystemTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license   MIT
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Helper;

use Foundry\Masonry\Builder\Helper\FileSystem;
use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * Class FileSystemTest
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/Masonry-Builder
 * @coversDefaultClass Foundry\Masonry\Builder\Helper\FileSystem
 */
class FileSystemTest extends TestCase
{

    /**
     * @covers ::copy
     * @uses Foundry\Masonry\Builder\Helper\FileSystem::makeDirectory
     * @uses Foundry\Masonry\Builder\Helper\FileSystem::isDirectory
     * @throws \Exception
     * @return void
     */
    public function testCopy()
    {
        $root = 'root';
        $from = 'testDir';
        $to = 'testDirCopy';

        $mockFileSystem = vfsStream::setup($root, 0777);
        $mockFileSystem->addChild(vfsStream::create([
            $from => [
                'secondLevelDir' => [
                    'secondLevelFile.txt' => 'second level file contents'
                ],
                'file.txt' => 'file contents'
            ]
        ]));

        $fromUrl = vfsStream::url('root/' . $from);
        $toUrl = vfsStream::url('root/' . $to);

        $fileSystemHelper = new FileSystem();

        $this->assertTrue(
            is_file($fromUrl . '/file.txt')
        );
        $this->assertFalse(
            is_file($toUrl . '/file.txt')
        );
        $this->assertTrue(
            is_file($fromUrl . '/secondLevelDir/secondLevelFile.txt')
        );
        $this->assertFalse(
            is_file($toUrl . '/secondLevelDir/secondLevelFile.txt')
        );

        $this->assertTrue(
            $fileSystemHelper->copy(
                $fromUrl,
                $toUrl
            )
        );

        $this->assertTrue(
            is_file($fromUrl . '/file.txt')
        );
        $this->assertTrue(
            is_file($toUrl . '/file.txt')
        );
        $this->assertTrue(
            is_file($fromUrl . '/secondLevelDir/secondLevelFile.txt')
        );
        $this->assertTrue(
            is_file($toUrl . '/secondLevelDir/secondLevelFile.txt')
        );
    }

    /**
     * @covers ::copy
     * @uses Foundry\Masonry\Builder\Helper\FileSystem::makeDirectory
     * @uses Foundry\Masonry\Builder\Helper\FileSystem::isDirectory
     * @throws \Exception
     * @expectedException \Exception
     * @expectedExceptionMessage Could not copy file
     * @return void
     */
    public function testCopyFileException()
    {
        $root = 'root';
        $from = 'testDir';
        $to = 'testDirCopy';

        $mockFileSystem = vfsStream::setup($root, 0000);
        $mockFileSystem->addChild(vfsStream::create([
            $from => [
                'file.txt' => 'file contents'
            ]
        ]));

        $fromUrl = vfsStream::url('root/' . $from);
        $toUrl = vfsStream::url('root/' . $to);

        $fileSystemHelper = new FileSystem();

        $this->assertTrue(
            is_file($fromUrl . '/file.txt')
        );

        // This should throw an exception
        $fileSystemHelper->copy(
            $fromUrl . '/file.txt',
            $toUrl . '/file.txt'
        );
    }

    /**
     * @covers ::copy
     * @uses Foundry\Masonry\Builder\Helper\FileSystem::makeDirectory
     * @uses Foundry\Masonry\Builder\Helper\FileSystem::isDirectory
     * @throws \Exception
     * @expectedException \Exception
     * @expectedExceptionMessage Could not create directory
     * @return void
     */
    public function testCopyDirException()
    {
        $root = 'root';
        $from = 'testDir';
        $to = 'testDirCopy';

        $mockFileSystem = vfsStream::setup($root, 0000);
        $mockFileSystem->addChild(vfsStream::create([
            $from => [
                'file.txt' => 'file contents'
            ]
        ]));

        $fromUrl = vfsStream::url('root/' . $from);
        $toUrl = vfsStream::url('root/' . $to);

        $fileSystemHelper = new FileSystem();

        $this->assertTrue(
            is_file($fromUrl . '/file.txt')
        );

        // This should throw an exception
        $fileSystemHelper->copy(
            $fromUrl,
            $toUrl
        );
    }

    /**
     * @covers ::copy
     * @uses Foundry\Masonry\Builder\Helper\FileSystem::makeDirectory
     * @uses Foundry\Masonry\Builder\Helper\FileSystem::isDirectory
     * @throws \Exception
     * @expectedException \Exception
     * @expectedExceptionMessage does not exist or is not accessible
     * @return void
     */
    public function testCopyNotExistsException()
    {
        $root = 'root';
        $from = 'testDir';
        $to = 'testDirCopy';

        $mockFileSystem = vfsStream::setup($root, 0000);

        $fromUrl = vfsStream::url('root/' . $from);
        $toUrl = vfsStream::url('root/' . $to);

        $fileSystemHelper = new FileSystem();

        // This should throw an exception
        $fileSystemHelper->copy(
            $fromUrl,
            $toUrl
        );
    }

    /**
     * @test
     * @covers ::delete
     * @uses Foundry\Masonry\Builder\Helper\FileSystem::isDirectory
     * @return void
     */
    public function testDelete()
    {
        $root = 'root';
        $delDir = 'testDir';

        $mockFileSystem = vfsStream::setup($root, 0777);
        $mockFileSystem->addChild(vfsStream::create([
            $delDir => [
                'secondLevelDir' => [
                    'secondLevelFile.txt' => 'second level file contents'
                ],
                'file.txt' => 'file contents'
            ]
        ]));


        $delDirUrl = vfsStream::url('root/' . $delDir);

        $fileSystemHelper = new FileSystem();

        // Test failures
        $this->assertFalse(
            $fileSystemHelper->delete($delDirUrl . '/not-a-file.txt')
        );


        // Test successes
        $this->assertTrue(
            is_dir($delDirUrl)
        );
        $this->assertTrue(
            is_file($delDirUrl . '/file.txt')
        );
        $this->assertTrue(
            is_dir($delDirUrl . '/secondLevelDir')
        );
        $this->assertTrue(
            is_file($delDirUrl . '/secondLevelDir/secondLevelFile.txt')
        );

        $this->assertTrue(
            $fileSystemHelper->delete($delDirUrl)
        );

        $this->assertFalse(
            is_file($delDirUrl . '/secondLevelDir/secondLevelFile.txt')
        );
        $this->assertFalse(
            is_dir($delDirUrl . '/secondLevelDir')
        );
        $this->assertFalse(
            is_file($delDirUrl . '/file.txt')
        );
        $this->assertFalse(
            is_dir($delDirUrl)
        );

    }

    /**
     * @test
     * @covers ::makeDirectory
     * @return void
     */
    public function testMakeDirectory()
    {
        $root = 'root';
        $newDir = 'testDir';

        vfsStream::setup($root, 0777);
        $newDirUrl = vfsStream::url('root/' . $newDir);

        $fileSystemHelper = new FileSystem();

        $this->assertFalse(
            is_dir($newDirUrl)
        );

        $this->assertTrue(
            $fileSystemHelper->makeDirectory($newDirUrl)
        );

        $this->assertTrue(
            is_dir($newDirUrl)
        );
    }

    /**
     * @test
     * @covers ::move
     * @return void
     */
    public function testMove()
    {
        $root = 'root';
        $from = 'testDir';
        $to = 'testDirMoved';

        $mockFileSystem = vfsStream::setup($root, 0777);
        $mockFileSystem->addChild(vfsStream::create([
            $from => [
                'secondLevelDir' => [
                    'secondLevelFile.txt' => 'second level file contents'
                ],
                'file.txt' => 'file contents'
            ]
        ]));

        $fromUrl = vfsStream::url('root/' . $from);
        $toUrl = vfsStream::url('root/' . $to);

        $fileSystemHelper = new FileSystem();

        $this->assertTrue(
            is_file($fromUrl . '/file.txt')
        );
        $this->assertFalse(
            is_file($toUrl . '/file.txt')
        );
        $this->assertTrue(
            is_file($fromUrl . '/secondLevelDir/secondLevelFile.txt')
        );
        $this->assertFalse(
            is_file($toUrl . '/secondLevelDir/secondLevelFile.txt')
        );

        $this->assertTrue(
            $fileSystemHelper->move(
                $fromUrl,
                $toUrl
            )
        );

        $this->assertFalse(
            is_file($fromUrl . '/file.txt')
        );
        $this->assertTrue(
            is_file($toUrl . '/file.txt')
        );
        $this->assertFalse(
            is_file($fromUrl . '/secondLevelDir/secondLevelFile.txt')
        );
        $this->assertTrue(
            is_file($toUrl . '/secondLevelDir/secondLevelFile.txt')
        );
    }

    /**
     * @test
     * @covers ::isDirectory
     * @return void
     */
    public function testIsDirectory()
    {
        $root = 'root';
        $realDir = 'real-dir';
        $fakeDir = 'fake-dir';

        $mockFileSystem = vfsStream::setup($root, 0777);
        $mockFileSystem->addChild(vfsStream::create([
            $realDir => []
        ]));

        $fileSystemHelper = new FileSystem();

        $this->assertTrue(
            $fileSystemHelper->isDirectory(vfsStream::url("$root/$realDir"))
        );
        $this->assertFalse(
            $fileSystemHelper->isDirectory(vfsStream::url("$root/$fakeDir"))
        );
    }


}
