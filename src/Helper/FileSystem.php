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
        if (is_file($from)) {
            if (@copy($from, $to)) {
                return true;
            }
            throw new \Exception("Could not copy file '$from' to '$to'");
        }

        if (is_dir($from)) {
            $returnValue = true;

            // Does the "to" directory need to be created
            $makingDirectory = !is_dir($to);
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
     * @param string $fileOrDirectory The file or directory to be deleted
     * @return bool
     */
    public function delete($fileOrDirectory)
    {
        if(is_file($fileOrDirectory)){
            return unlink($fileOrDirectory);
        }
        if(is_dir($fileOrDirectory)) {

            $directory = opendir($fileOrDirectory);
            while (false !== ($file = readdir($directory))) {
                if (($file != '.') && ($file != '..')) {
                    if(!$this->delete("$fileOrDirectory/$file")) {
                        return false;
                    }
                }
            }
            closedir($directory);

            return @rmdir($fileOrDirectory);
        }
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
        return mkdir($directory, $mode, $recursive);
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

}