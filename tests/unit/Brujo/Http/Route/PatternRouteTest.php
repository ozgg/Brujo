<?php
/**
 * Test case for pattern-based route
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Test\Unit\Brujo\Http\Route
 */

namespace Test\Unit\Brujo\Http\Route;
 
use Brujo\Http\Route\PatternRoute;
use Brujo\Test\TestCase;

/**
 * Tests for PatternRoute
 *
 * @covers \Brujo\Http\Route\PatternRoute
 */
class PatternRouteTest extends TestCase
{
    /**
     * Tested route
     *
     * @var PatternRoute
     */
    private $route;

    /**
     * Create instance of tested route
     */
    protected function setUp()
    {
        $this->route = new PatternRoute;
    }

    /**
     * Data provider for testAssembleFailure
     *
     * @return array
     */
    public function assembleFailureProvider()
    {
        return [
            ['/foo/:foo', [['bar' => 'baz']]],
            ['/foo/:foo/bar/:bar', [['bar' => 'baz']]],
            ['/foo/:foo/bar/:bar', ['foo']],
        ];
    }

    /**
     * Data provider for testAssembleSuccess
     *
     * @return array
     */
    public function assembleSuccessProvider()
    {
        return [
            [[1, 2]],
            [[[1, 2]]],
            [[['a' => 1, 'b' => 2]]],
        ];
    }

    /**
     * Data provider for testGetMatch
     *
     * @return array
     */
    public function getMatchProvider()
    {
        return [
            [['/foo/:foo'], ['/foo/[^/]+']],
            [['/foo/:foo/:bar'], ['/foo/[^/]+/[^/]+']],
            [['/foo/:foo/:bar/baz'], ['/foo/[^/]+/[^/]+/baz']],
        ];
    }

    /**
     * Data provider for testRequestSuccess
     *
     * @return array
     */
    public function requestSuccessProvider()
    {
        return [
            ['/foo/:foo', '/foo/1', ['foo' => 1]],
            ['/foo/:foo/:bar', '/foo/1/2', ['foo' => 1, 'bar' => 2]],
        ];
    }

    /**
     * Test assembling URI with correct parameters
     *
     * Method accepts arbitrary arguments or array (regular and associative)
     *
     * @param array $arguments
     *
     * @covers \Brujo\Http\Route\PatternRoute::assemble
     * @dataProvider assembleSuccessProvider
     */
    public function testAssembleSuccess(array $arguments)
    {
        $uri    = '/foo/:a/bar/:b';
        $expect = '/foo/1/bar/2';
        $this->route->setUri($uri);

        $result = call_user_func_array([$this->route, 'assemble'], $arguments);
        $this->assertEquals($expect, $result);
    }

    /**
     * Test assembling URI throws exception on invalid parameters
     *
     * @param string $uri        URI to use in route pattern
     * @param array  $arguments  Arguments to pass to assemble()
     *
     * @expectedException \BadMethodCallException
     * @dataProvider assembleFailureProvider
     * @covers \Brujo\Http\Route\PatternRoute::assemble
     */
    public function testAssembleFailure($uri, array $arguments)
    {
        $this->route->setUri($uri);
        call_user_func_array([$this->route, 'assemble'], $arguments);
    }

    /**
     * Test requesting URI with parameters
     *
     * @param string $pattern  URI pattern for route
     * @param string $uri      URI to request
     * @param array  $expect   expected route parameters taken from URI
     *
     * @covers \Brujo\Http\Route\PatternRoute::request
     * @dataProvider requestSuccessProvider
     */
    public function testRequestSuccess($pattern, $uri, array $expect)
    {
        $this->route->setUri($pattern);
        $this->route->setMethods([PatternRoute::METHOD_GET]);
        $this->route->request(PatternRoute::METHOD_GET, $uri);

        $this->assertEquals($expect, $this->route->getParameters());
    }

    /**
     * Test requesting with not allowed method
     *
     * @expectedException \Brujo\Http\Error\MethodNotAllowed
     * @covers \Brujo\Http\Route\PatternRoute::request
     */
    public function testRequestFailure()
    {
        $this->route->setMethods([PatternRoute::METHOD_GET]);
        $this->route->request(PatternRoute::METHOD_POST, '');
    }

    /**
     * Test getting regEx match
     *
     * @param string $uri
     * @param string $pattern
     * @covers \Brujo\Http\Route\PatternRoute::getMatch
     * @dataProvider getMatchProvider
     */
    public function testGetMatch($uri, $pattern)
    {
        $this->route->setUri($uri);
        $this->assertEquals($pattern, $this->route->getMatch());
    }
}
