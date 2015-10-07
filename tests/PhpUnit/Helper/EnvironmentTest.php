<?php
/**
 * EnvironmentTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license   MIT
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Helper;

use Foundry\Masonry\Builder\Helper\Environment;
use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;


/**
 * Class EnvironmentTest
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 * @coversDefaultClass Foundry\Masonry\Builder\Helper\Environment
 */
class EnvironmentTest extends TestCase
{
    /**
     * @covers ::set
     * @return void
     */
    public function testSet()
    {
        $key   = 'TEST_ENV_VAR_NAME';
        $value = microtime();

        $environment = new Environment();
        $environment->set($key, $value);

        $this->assertSame(
            $value,
            getenv($key)
        );
    }

    /**
     * @covers ::get
     * @return void
     */
    public function testGet()
    {
        $key   = 'TEST_ENV_VAR_NAME';
        $value = microtime();

        putenv($key.'='.$value);

        $environment = new Environment();
        $this->assertSame(
            $value,
            $environment->get($key)
        );
    }


}