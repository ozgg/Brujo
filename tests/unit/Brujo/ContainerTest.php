<?php
/**
 * Test case for DI container
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Test\Unit\Brujo
 */

namespace Test\Unit\Brujo;

use Brujo\Container;
use Brujo\Test\TestCase;

/**
 * Test for DI container
 *
 * @covers \Brujo\Container
 */
class ContainerTest extends TestCase
{
    /**
     * Tested object
     *
     * @var Container
     */
    private $container;

    /**
     * Create instance of tested object
     */
    protected function setUp()
    {
        $this->container = new Container;
    }

    /**
     * Test extracting existing element
     *
     * @covers \Brujo\Container::extract
     */
    public function testExtractExisting()
    {
        $element = new \stdClass;

        $element->foo = 'bar';
        $this->container->inject('foo', $element);

        $extracted = $this->container->extract('foo');
        $this->assertEquals($element, $extracted);
    }

    /**
     * Test extracting non-existent element
     *
     * @covers \Brujo\Container::extract
     */
    public function testExtractEmpty()
    {
        $element = $this->container->extract('foo');

        $this->assertNull($element);
    }

    /**
     * Test checking existence of elements in container
     *
     * @covers \Brujo\Container::check
     */
    public function testCheck()
    {
        $element = ['foo' => 'bar'];
        $this->container->inject('foo', $element);

        $this->assertTrue($this->container->contains('foo'));
        $this->assertFalse($this->container->contains('bar'));
    }
}
