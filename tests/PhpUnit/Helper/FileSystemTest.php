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
 * @see     https://github.com/Visionmongers/
 */
class FileSystemTest extends TestCase
{

    public function testCopyFile()
    {
        $root = 'root';
        $from = 'testFile.txt';
        $to   = 'testFileCopy.txt';

        $fileSystem = vfsStream::setup($root, 0777);
        $fileSystem->addChild(vfsStream::create([
            $from => 'test file contents'
        ]));

        $fromUrl = vfsStream::url($root.'/'.$from);
        $toUrl   = vfsStream::url($root.'/'.$to);

        $fileSystemHelper = new FileSystem();

        $this->assertTrue(
            is_file($fromUrl)
        );
        $this->assertFalse(
            is_file($toUrl)
        );

        $this->assertTrue(
            $fileSystemHelper->copy(
                $fromUrl,
                $toUrl
            )
        );

        $this->assertTrue(
            is_file($fromUrl)
        );
        $this->assertTrue(
            is_file($toUrl)
        );

    }

    public function testCopyDirectory()
    {
        $root = 'root';
        $from = 'testDir';
        $to   = 'testDirCopy';

        $fileSystem = vfsStream::setup($root, 0777);
        $fileSystem->addChild(vfsStream::create([
            $from => [
                'secondLevelDir' => [
                    'secondLevelFile.txt' => 'second level file contents'
                ],
                'file.txt' => 'file contents'
            ]
        ]));

        $fileSystemHelper = new FileSystem();

        $fromUrl = vfsStream::url('root/'.$from);
        $toUrl   = vfsStream::url('root/'.$to);

        $this->assertTrue(
            is_file($fromUrl.'/file.txt')
        );
        $this->assertFalse(
            is_file($toUrl.'/file.txt')
        );
        $this->assertTrue(
            is_file($fromUrl.'/secondLevelDir/secondLevelFile.txt')
        );
        $this->assertFalse(
            is_file($toUrl.'/secondLevelDir/secondLevelFile.txt')
        );

        $this->assertTrue(
            $fileSystemHelper->copy(
                $fromUrl,
                $toUrl
            )
        );

        $this->assertTrue(
            is_file($fromUrl.'/file.txt')
        );
        $this->assertTrue(
            is_file($toUrl.'/file.txt')
        );
        $this->assertTrue(
            is_file($fromUrl.'/secondLevelDir/secondLevelFile.txt')
        );
        $this->assertTrue(
            is_file($toUrl.'/secondLevelDir/secondLevelFile.txt')
        );
    }

//    public function testDelete()
//    {
//
//    }
//
//    public function testMakeDirectory()
//    {
//
//    }
//
//    public function testMove()
//    {
//
//    }

}