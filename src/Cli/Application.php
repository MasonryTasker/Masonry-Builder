<?php
/**
 * Application.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Cli;

use Foundry\Masonry\Builder\Commands\Build;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Command\Command;
use Foundry\Masonry\Builder\Helper\ClassRegistry;
use Foundry\Masonry\Builder\Workers;
use Foundry\Masonry\Core\Mediator;

/**
 * Class Application
 * ${CARET}
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class Application extends SymfonyApplication
{

    /**
     * Gets the name of the command based on input.
     * @param InputInterface $input The input interface
     * @return string The command name
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'build';
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return Command[] An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option
        $defaultCommands = parent::getDefaultCommands();

        $classRegistry = new ClassRegistry([
            'Copy'            => Workers\FileSystem\Copy\Description::class,
            'Delete'          => Workers\FileSystem\Delete\Description::class,
            'MakeDirectory'   => Workers\FileSystem\MakeDirectory\Description::class,
            'Move'            => Workers\FileSystem\Move\Description::class,
            'Composer'        => Workers\PackageManager\Composer\Description::class,
            'Exec'            => Workers\System\Exec\Description::class,
            'CloneRepository' => Workers\VersionControl\Git\CloneRepository\Description::class,
        ]);

        $mediator = new Mediator();
        $mediator
            ->addWorker(new Workers\FileSystem\Copy\Worker())
            ->addWorker(new Workers\FileSystem\Delete\Worker())
            ->addWorker(new Workers\FileSystem\MakeDirectory\Worker())
            ->addWorker(new Workers\FileSystem\Move\Worker())
            ->addWorker(new Workers\PackageManager\Composer\Worker())
            ->addWorker(new Workers\System\Exec\Worker())
            ->addWorker(new Workers\VersionControl\Git\CloneRepository\Worker())
        ;

        $defaultCommands[] = new Build($mediator, $classRegistry);

        return $defaultCommands;
    }

    /**
     * Gets the InputDefinition related to this Application.
     *
     * @return InputDefinition The InputDefinition instance
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

}