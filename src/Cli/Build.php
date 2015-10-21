<?php
/**
 * Build.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Cli;

use Foundry\Masonry\Builder\Coroutine\Factory as CoroutineFactory;
use Foundry\Masonry\Builder\Helper\ClassRegistry;
use Foundry\Masonry\Builder\Logging\EchoLogger;
use Foundry\Masonry\Builder\Logging\MultiLogger;
use Foundry\Masonry\Builder\Logging\SymfonyOutputLogger;
use Foundry\Masonry\Builder\Pools\YamlQueue;
use Foundry\Masonry\Builder\Processor\BlockingProcessor;
use Foundry\Masonry\Core\Mediator;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\MediatorInterface;
use Foundry\Masonry\Interfaces\Pool\StatusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;


/**
 * Class Build
 * Command for CLI
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class Build extends Command
{

    /**
     * @var Yaml
     */
    protected $yamlReader;

    /**
     * Build constructor.
     * @param null $name
     */
    public function __construct($name = null)
    {
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
                'script',
                's',
                InputArgument::OPTIONAL,
                'The name of the build script file',
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
        $scriptFile = $input->getOption('script');

        if(!is_file($scriptFile)) {
            throw new \InvalidArgumentException("Script file '$scriptFile' not found");
        }

        $taskArray    = $this->getYaml($scriptFile);
        $descriptions = $this->createDescriptionRegistry();

        $pool = new YamlQueue($taskArray, $descriptions);

        $multiLogger = new MultiLogger();
        $multiLogger->addLogger(
            new SymfonyOutputLogger($output)
        );

        $processor = new BlockingProcessor();
        $processor
            ->setLogger($multiLogger)
            ->setMediator($this->createMediator());

        return $processor->run($pool) ? 0 : 1;
    }

    /**
     *
     * @param ClassRegistry|null $descriptions
     * @return ClassRegistry
     */
    protected function createDescriptionRegistry(ClassRegistry $descriptions = null)
    {
        if(!$descriptions) {
            $descriptions = new ClassRegistry();
        }
        $config = $this->getConfiguration();
        if(!array_key_exists('Descriptions', $config)) {
            throw new \RuntimeException('No descriptions have been configured');
        }
        $descriptions->addClassNames($config['Descriptions']);

        return $descriptions;
    }

    /**
     *
     * @param Mediator|null $mediator
     * @return Mediator
     */
    protected function createMediator(Mediator $mediator = null)
    {
        if(!$mediator) {
            $mediator = new Mediator();
        }
        $config = $this->getConfiguration();
        if(!array_key_exists('Workers', $config)) {
            throw new \RuntimeException('No workers have been configured');
        }
        foreach($config['Workers'] as $worker) {
            $mediator->addWorker(new $worker);
        }

        return $mediator;
    }

    /**
     * @param string|null $customConfigFile
     * @return array
     */
    protected function getConfiguration($customConfigFile = null)
    {
        static $configuration = [];
        if(!$configuration) {
            $default = $this->getYaml(__DIR__.'/../../configuration/default.yml');
            $customConfig = [];
            if($customConfigFile) {
                $customConfig = $this->getYaml($customConfigFile);
            }
            $configuration = array_merge($default, $customConfig);
        }
        return $configuration;
    }

    /**
     * @param $filename
     * @return array
     */
    protected function getYaml($filename)
    {
        if(!$filename) {
            throw new \RuntimeException("File '$filename' not found");
        }
        return Yaml::parse(file_get_contents($filename));
    }
}