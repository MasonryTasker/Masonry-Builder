<?php
/**
 * Build.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Commands;

use Foundry\Masonry\Builder\Helper\ClassRegistry;
use Foundry\Masonry\Builder\Pools\ArrayQueue;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\MediatorInterface;
use Foundry\Masonry\Interfaces\Pool\StatusInterface;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;


/**
 * Class Build
 * ${CARET}
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class Build extends Command
{

    /**
     * @var ClassRegistry
     */
    protected $classRegistry;

    /**
     * @var MediatorInterface
     */
    protected $mediator;

    /**
     * Build constructor.
     * @param MediatorInterface $mediator
     * @param ClassRegistry     $classRegistry
     * @param null $name
     */
    public function __construct(MediatorInterface $mediator, ClassRegistry $classRegistry, $name = null)
    {
        $this->mediator = $mediator;
        $this->classRegistry = $classRegistry;
        parent::__construct($name);
    }

    /**
     * Configures the current command.
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('build')
            ->setDescription('Run the build process.')
            ->addOption(
                'configuration',
                'c',
                InputArgument::OPTIONAL,
                'The name of the configuration file',
                'build.yml'
            );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     * @throws \Exception
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = $input->getOption('configuration');

        if(!is_file($configFile)) {
            throw new \InvalidArgumentException("Configuration file '$configFile' not found");
        }

        $taskReader = new Yaml();
        $taskArray = $taskReader->parse(file_get_contents($configFile));

        $pool = new ArrayQueue();

        foreach($taskArray as $task) {
            $descriptionClassName = key($task);
            $descriptionParameters = current($task);
            $className = $this->classRegistry->getClass($descriptionClassName);

            $descriptionReflection = new \ReflectionClass($className);
            $description = $descriptionReflection->newInstanceArgs($descriptionParameters);

            if(!$description instanceof DescriptionInterface) {
                throw new \UnexpectedValueException("'$descriptionClassName' is not a Description");
            }
            $pool->addTask(new Task($description));
        }

        while($pool->getStatus() != StatusInterface::STATUS_EMPTY) {
            $task = $pool->getTask();
            $this->mediator->process($task)
                ->then(function($result) use ($output) {
                    $output->writeln("<info>Success</>: $result");
                })
                ->otherwise(function($result) use ($output) {
                    $output->writeln("<error>Failure</>: $result");
                    throw new \RuntimeException('Could not complete the build due to failure');
                })
                ->progress(function($result) use ($output) {
                    $output->writeln("<comment>Notice</>: $result");
                })
                ;
        }

    }
}