<?php
/**
 * ClassRegistry.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Helper;

/**
 * Class ClassRegistry
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class ClassRegistry
{

    protected $classNames = [];

    /**
     * Add a group of class names to the registry
     * @param array $fullyQualifiedNames
     * @return $this
     */
    public function addClassNames(array $fullyQualifiedNames = [])
    {
        foreach($fullyQualifiedNames as $fullyQualifiedName) {
            $this->addClassName($fullyQualifiedName);
        }
        return $this;
    }

    /**
     * Add a class name to the registry
     * @param $fullyQualifiedName
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function addClassName($fullyQualifiedName)
    {
        if(!is_string($fullyQualifiedName)) {
            throw new \InvalidArgumentException('Class names must be strings');
        }
        $this->classNames[$fullyQualifiedName] = $fullyQualifiedName;
        return $this;
    }

    /**
     * Get a fully qualified class name from a partial class name
     * @param $name
     * @return string
     */
    public function getClass($name)
    {
        $fullyQualifiedName = $this->classNameLookup($name);
        if(class_exists($fullyQualifiedName)) {
            return $fullyQualifiedName;
        }
        throw new \UnexpectedValueException("Class '$fullyQualifiedName' was registered but could not be found");
    }

    /**
     * Find a class in the list of class names
     * @param $name
     * @throws \UnexpectedValueException
     * @return string
     */
    protected function classNameLookup($name)
    {
        // Option 1, the class name is already correct
        if(array_key_exists($name, $this->classNames)) {
            return $name;
        }
        // Option 2, the class name is a partial match at the end
        foreach($this->classNames as $fullyQualifiedName) {
            if(preg_match("/{$name}$/", $fullyQualifiedName)) {
                return $fullyQualifiedName;
            }
        }
        // Option 3, the class name is a partial match... somewhere
        foreach($this->classNames as $fullyQualifiedName) {
            if(preg_match("/{$name}/", $fullyQualifiedName)) {
                return $fullyQualifiedName;
            }
        }
        throw new \UnexpectedValueException("Could not find class matching '{$name}'");
    }

}