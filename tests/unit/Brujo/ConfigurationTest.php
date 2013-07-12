<?php
/**
 * Configuration test
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Test\Unit\Brujo
 */

namespace Test\Unit\Brujo;

use Brujo\Configuration;
use Brujo\Test\TestCase;

/**
 * Configuration test
 *
 * @covers \Brujo\Configuration
 */
class ConfigurationTest extends TestCase
{
    /**
     * Tested object
     *
     * @var Configuration
     */
    private $configuration;

    /**
     * Create tested object
     */
    protected function setUp()
    {
        $path = __DIR__ . '/../../samples/configuration';

        $this->configuration = new Configuration($path);
    }

    /**
     * Data for successful environment setting
     */
    public function environmentProvider()
    {
        return [
            ['production', ['a' => 0, 'b' => ['c' => 'd']]],
            ['development', ['a' => 0, 'b' => ['c' => 'e']]],
            ['test', ['a' => 1, 'b' => ['c' => 'e', 'f' => 'g'], 'h' => 'i']],
        ];
    }

    /**
     * Test successful environment setting
     *
     * @dataProvider environmentProvider
     * @covers       \Brujo\Configuration::setEnvironment
     */
    public function testSetEnvironmentSuccess($environment, array $expect)
    {
        $this->configuration->setEnvironment($environment);
        $this->assertEquals($expect, $this->configuration->getParameters());
    }

    /**
     * Test setting non-existent environment
     *
     * @expectedException \RuntimeException
     * @covers \Brujo\Configuration::setEnvironment
     */
    public function testSetEnvironmentFail()
    {
        $this->configuration->setEnvironment('invalid');
    }
}
