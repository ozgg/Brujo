<?php
/**
 * Test case for static route
 * 
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Test\Unit\Brujo\Http\Route
 */

namespace Test\Unit\Brujo\Http\Route;

use Brujo\Test\TestCase;
use Brujo\Http\Route\StaticRoute;

/**
 * Test of static route
 *
 * @covers \Brujo\Http\Route\StaticRoute
 */
class StaticRouteTest extends TestCase
{
    /**
     * Instance of tested class
     *
     * @var StaticRoute
     */
    private $route;

    /**
     * Create instance of tested class
     */
    protected function setUp()
    {
        $this->route = new StaticRoute;
    }

    /**
     * Tests assembling of route
     *
     * Static route always assembles into its URI.
     *
     * @covers \Brujo\Http\Route\StaticRoute::assemble
     */
    public function testAssemble()
    {
        $uri = '/foo/bar/la-la-la';

        $this->route->setUri($uri);

        $this->assertEquals($uri, $this->route->assemble());
    }

    /**
     * Test successful request to route
     *
     * There must be no errors after method call
     *
     * @covers \Brujo\Http\Route\StaticRoute::request
     */
    public function testRequestSuccess()
    {
        $allowedMethods = [StaticRoute::METHOD_GET, StaticRoute::METHOD_POST];
        $this->route->setMethods($allowedMethods);
        $this->route->request(StaticRoute::METHOD_GET, '');
    }

    /**
     * Test failed request to route
     *
     * @expectedException \Brujo\Http\Error\MethodNotAllowed
     * @covers \Brujo\Http\Route\StaticRoute::request
     */
    public function testRequestFailure()
    {
        $this->route->setMethods([StaticRoute::METHOD_GET]);
        $this->route->request(StaticRoute::METHOD_POST, '');
    }

    /**
     * Test getting regEx pattern
     *
     * @covers \Brujo\Http\Route\StaticRoute::getMatch
     */
    public function testGetMatch()
    {
        $this->route->setUri('/foo/bar');
        $this->assertEquals('/foo/bar', $this->route->getMatch());
    }
}
