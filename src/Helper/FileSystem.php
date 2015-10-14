<?php
/**
 * FileSystem.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/Masonry-Builder
 */


namespace Foundry\Masonry\Builder\Helper;

/**
 * Class FileSystem
 * Wraps file system functionality
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/Masonry-Builder
 */
class FileSystem
{

    /**
     * Wraps copy and is able to copy directories
     * @param string $from Source location
     * @param string $to   Target location
     * @throws \Exception
     * @return bool
     */
    public function copy($from, $to)
    {
        if ($this->isFile($from)) {
            if (@copy($from, $to)) {
                return true;
            }
            throw new \Exception("Could not copy file '$from' to '$to'");
        }

        if ($this->isDirectory($from)) {
            $returnValue = true;

            // Does the "to" directory need to be created
            $makingDirectory = !$this->isDirectory($to);
            if ($makingDirectory) {
                if (!$this->makeDirectory($to, 0777, true)) {
                    throw new \Exception("Could not create directory '$to'");
                }
            }

            // Step through the directory
            $fromDirectory = opendir($from);
            while (false !== ($file = readdir($fromDirectory))) {
                if (($file != '.') && ($file != '..')) {
                    $returnValue = $returnValue && $this->copy("$from/$file", "$to/$file");
                }
            }

            closedir($fromDirectory);

            // Fix permissions
            if ($makingDirectory) {
                $returnValue = $returnValue && chmod($to, fileperms($from));
            }

            return $returnValue;
        }

        throw new \Exception("'$from' does not exist or is not accessible");
    }

    /**
     * Wraps unlink and rmdir to recursively delete a file or directory
     * Warning: This will delete every thing that _can_ be deleted. If a single file can't be deleted, everything but
     * it's containing directories will still be deleted.
     * @param string $fileOrDirectory The file or directory to be deleted
     * @return bool
     */
    public function delete($fileOrDirectory)
    {
        if ($this->isFile($fileOrDirectory)) {
            return unlink($fileOrDirectory);
        }
        if ($this->isDirectory($fileOrDirectory)) {
            $directory = opendir($fileOrDirectory);
            while (false !== ($file = readdir($directory))) {
                if (($file != '.') && ($file != '..')) {
                    // The return of any child deletes doesn't matter as rmdir won't complete below.
                    $this->delete("$fileOrDirectory/$file");
                }
            }
            closedir($directory);

            return @rmdir($fileOrDirectory);
        }
        return false;
    }

    /**
     * Wraps mkdir
     * @param string $directory The name of the directory to be created
     * @param int    $mode      Defaults to 0777
     * @param bool   $recursive Defaults to true, which is different from mkdir
     * @return bool
     */
    public function makeDirectory($directory, $mode = 0777, $recursive = true)
    {
        return @mkdir($directory, $mode, $recursive);
    }

    /**
     * Wraps rename
     * @param string $from Source location
     * @param string $to   Target location
     * @return bool
     */
    public function move($from, $to)
    {
        return @rename($from, $to);
    }

    /**
     * Wraps is_dir
     * @param $directory
     * @return bool
     */
    public function isDirectory($directory)
    {
        return @is_dir($directory);
    }

    /**
     * Wraps is_file
     * @param $file
     * @return bool
     */
    public function isFile($file)
    {
        return @is_file($file);
    }
}
