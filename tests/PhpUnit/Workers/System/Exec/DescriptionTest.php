<?php
/**
 * DescriptionTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\System\Exec;

use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use Foundry\Masonry\Builder\Workers\System\Exec\Description;


/**
 * Class DescriptionTest
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\System\Exec\Description
 */
class DescriptionTest extends TestCase
{

    /**
     * @test
     * @covers ::__construct
     * @return void
     */
    public function testConstruct()
    {
        $command = 'simpleCommand';
        $argument1 = 'arg1';
        $argument2 = 'arg2';
        $arguments = [ $argument1, $argument2 ];

        // No arguments
        $description = new Description($command);
        $this->assertSame(
            $command,
            $this->getObjectAttribute($description, 'command')
        );
        $this->assertSame(
            [],
            $this->getObjectAttribute($description, 'arguments')
        );

        // With arguments
        $description = new Description("$command $argument1 $argument2");
        $this->assertSame(
            $command,
            $this->getObjectAttribute($description, 'command')
        );
        $this->assertSame(
            $arguments,
            $this->getObjectAttribute($description, 'arguments')
        );
    }

    /**
     * @test
     * @covers ::getCommand
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description::__construct
     * @return void
     */
    public function testGetCommand()
    {
        $command = 'simpleCommand';
        $argument1 = 'arg1';
        $argument2 = 'arg2';

        // No arguments
        $description = new Description($command);
        $this->assertSame(
            $command,
            $description->getCommand()
        );

        // With arguments
        $description = new Description("$command $argument1 $argument2");
        $this->assertSame(
            $command,
            $description->getCommand()
        );
    }

    /**
     * @test
     * @covers ::getArguments
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description::__construct
     * @return void
     */
    public function testGetArguments()
    {
        $command = 'simpleCommand';
        $argument1 = 'arg1';
        $argument2 = 'arg2';
        $arguments = [ $argument1, $argument2 ];

        // No arguments
        $description = new Description($command);
        $this->assertSame(
            [],
            $description->getArguments()
        );

        // With arguments
        $description = new Description("$command $argument1 $argument2");
        $this->assertSame(
            $arguments,
            $description->getArguments()
        );
    }

    /**
     * @test
     * @covers ::getCommandString
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description::__construct
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description::getCommand
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description::getArguments
     * @return void
     */
    public function testGetCommandString()
    {
        $command = 'simpleCommand';
        $argument1 = 'arg1';
        $argument2 = 'arg2';

        // No arguments
        $description = new Description($command);
        $this->assertSame(
            $command,
            $description->getCommandString()
        );

        // With arguments
        $description = new Description("$command $argument1 $argument2");
        $this->assertSame(
            $this->fixShellArgumentQuotes($command.' "'.$argument1.'" "'.$argument2.'"'),
            $description->getCommandString()
        );
    }

    /**
     * @test
     * @covers ::__toString
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description::__construct
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description::getCommand
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description::getArguments
     * @uses Foundry\Masonry\Builder\Workers\System\Exec\Description::getCommandString
     * @return void
     */
    public function testToString()
    {
        $command = 'simpleCommand';
        $argument1 = 'arg1';
        $argument2 = 'arg2';

        // No arguments
        $description = new Description($command);
        $this->assertSame(
            $command,
            (string)$description
        );

        // With arguments
        $description = new Description("$command $argument1 $argument2");
        $this->assertSame(
            $this->fixShellArgumentQuotes($command.' "'.$argument1.'" "'.$argument2.'"'),
            (string)$description
        );
    }

}