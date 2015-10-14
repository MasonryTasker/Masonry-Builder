<?php
/**
 * build.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license   MIT
 * @see       https://github.com/Visionmongers/
 */

require __DIR__ . '/../vendor/autoload.php';

use Foundry\Masonry\Builder\Commands\Build;
use Symfony\Component\Console\Application;
use Foundry\Masonry\Builder\Helper\ClassRegistry;
use Foundry\Masonry\Builder\Workers;
use Foundry\Masonry\Core\Mediator;

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

$application = new Application();
$application->add(new Build($mediator, $classRegistry));
$application->run();