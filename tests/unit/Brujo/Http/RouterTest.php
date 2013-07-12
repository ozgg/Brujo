<?php
/**
 * Test case for router
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Test\Unit\Brujo\Http
 */

namespace Test\Unit\Brujo\Http;

use Brujo\Http;
use Brujo\Test\TestCase;

/**
 * Tests for Router
 *
 * @covers \Brujo\Http\Router
 */
class RouterTest extends TestCase
{
    /**
     * Tested instance
     *
     * @var Http\Router
     */
    private $router;

    /**
     * Set up
     *
     * Create tested instance of Router
     */
    public function setUp()
    {
        $this->router = new Http\Router;
    }

    /**
     * Test importing routes configuration
     *
     * @covers \Brujo\Http\Router::import
     */
    public function testImport()
    {
        $config = $this->getSample('config/routes');

        $this->router->import($config);
        $this->assertEquals(count($config), count($this->router->getRoutes()));
    }

    /**
     * Test successful matching request URI
     *
     * @covers \Brujo\Http\Router::matchRequest
     */
    public function testMatchRequestSuccess()
    {
        $this->router->addRoute((new Http\Route\StaticRoute)->setUri('/foo'));
        $this->router->addRoute((new Http\Route\PatternRoute)->setUri('/bar'));

        $route = $this->router->matchRequest('/bar');

        $this->assertTrue($route instanceof Http\Route\PatternRoute);
    }

    /**
     * Test failing matching request URI
     *
     * @expectedException \Brujo\Http\Error\NotFound
     * @covers \Brujo\Http\Router::matchRequest
     */
    public function testMatchRequestFailure()
    {
        $this->router->addRoute((new Http\Route\StaticRoute)->setUri('/foo'));
        $this->router->matchRequest('/bar');
    }
}
