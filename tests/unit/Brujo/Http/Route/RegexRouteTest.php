<?php
/**
 * Test case for regex route
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Test\Unit\Brujo\Http\Route
 */

namespace Test\Unit\Brujo\Http\Route;

use Brujo\Http\Route\RegexRoute;
use Brujo\Test\TestCase;

/**
 * Test of regex route
 *
 * @covers \Brujo\Http\Route\RegexRoute
 */
class RegexRouteTest extends TestCase
{
    /**
     * Tested route instance
     *
     * @var RegexRoute
     */
    private $route;

    /**
     * Create instance of tested route
     */
    protected function setUp()
    {
        $this->route = new RegexRoute;
    }

    /**
     * Data provider for testAssembleSuccess
     *
     * @see testAssembleSuccess()
     *
     * @return array
     */
    public function assembleSuccessProvider()
    {
        return [
            ['/foo/(\s+)', '/foo/%s', ['bar'], '/foo/bar'],
            ['/u(\d+)/a/(\s+)', '/u%u/a/%s', [123, 'yay'], '/u123/a/yay'],
            ['/u(\d+)/a/(\s+)', '/u%u/a/%s', [[123, 'yay']], '/u123/a/yay'],
        ];
    }

    /**
     * Data provider for testAssembleFailureReverse
     *
     * @see testAssembleFailureReverse()
     *
     * @return array
     */
    public function assembleFailureReverseProvider()
    {
        return [
            ['/u(\d+)', '', [1]],
            ['/u(\d+)/foo/(\s+)', '', [1, 'yay']],
        ];
    }

    /**
     * Data provider for testAssembleFailure
     *
     * @see testAssembleFailure()
     *
     * @return array
     */
    public function assembleFailureProvider()
    {
        return [
            ['/u(\d+)/foo/(\s+)', '/u%u/foo/%s', [10]],
            ['/u(\d+)/foo/(\s+)', '/u%u/foo/%s', [10, 20, 30]],
        ];
    }

    /**
     * Data provider for testRequestSuccess
     *
     * @see testRequestSuccess()
     *
     * @return array
     */
    public function requestSuccessProvider()
    {
        return [
            ['/u(\d+)/foo\d+', '/u123/foo456', [0 => 123]],
            ['/u/(?P<u>\d+)/foo/(\d+)', '/u/12/foo/34', ['u' => 12, 1 => 34]],
            [
                '/(?P<a>\d+)/(?P<b>\d+)/(\d+)', '/1/2/3',
                ['a' => 1, 'b' => 2, 2 => 3]
            ],
            [
                '/(?P<a>\d+)/(\d+)/(?P<b>\d+)', '/1/2/3',
                ['a' => 1, 1 => 2, 'b' => 3]
            ],
        ];
    }

    /**
     * Test assembling route
     *
     * @param string $uri
     * @param string $reverse
     * @param array  $params
     * @param string $expect
     * @dataProvider assembleSuccessProvider
     * @covers       \Brujo\Http\Route\RegexRoute::assemble
     */
    public function testAssembleSuccess($uri, $reverse, array $params, $expect)
    {
        $this->route->setUri($uri);
        $this->route->setReverse($reverse);
        $result = call_user_func_array([$this->route, 'assemble'], $params);
        $this->assertEquals($expect, $result);
    }

    /**
     * Test assembling without reverse pattern
     *
     * @param string $uri
     * @param string $reverse
     * @param array  $parameters
     * @expectedException \RuntimeException
     * @dataProvider assembleFailureReverseProvider
     * @covers       \Brujo\Http\Route\RegexRoute::assemble
     */
    public function testAssembleFailureReverse(
        $uri, $reverse, array $parameters
    )
    {
        $this->route->setUri($uri);
        $this->route->setReverse($reverse);
        $this->route->assemble($parameters);
    }

    /**
     * Test assembling with pattern/arguments mismatch
     *
     * @param string $uri
     * @param string $reverse
     * @param array  $parameters
     * @expectedException \BadMethodCallException
     * @dataProvider assembleFailureProvider
     * @covers       \Brujo\Http\Route\RegexRoute::assemble
     */
    public function testAssembleFailure($uri, $reverse, array $parameters)
    {
        $this->route->setUri($uri);
        $this->route->setReverse($reverse);
        $this->route->assemble($parameters);
    }

    /**
     * Test successful route request
     *
     * @param string $pattern
     * @param string $uri
     * @param array  $expect
     * @dataProvider requestSuccessProvider
     * @covers       \Brujo\Http\Route\RegexRoute::request
     */
    public function testRequestSuccess($pattern, $uri, array $expect)
    {
        $this->route->setUri($pattern);
        $this->route->setMethods([RegexRoute::METHOD_GET]);
        $this->route->request(RegexRoute::METHOD_GET, $uri);

        $this->assertEquals($expect, $this->route->getParameters());
    }

    /**
     * Test getting exception requesting not allowed method
     *
     * @expectedException \Brujo\Http\Error\MethodNotAllowed
     * @covers \Brujo\Http\Route\RegexRoute::request
     */
    public function testRequestFailure()
    {
        $allowedMethods = [RegexRoute::METHOD_GET, RegexRoute::METHOD_PUT];
        $this->route->setMethods($allowedMethods);
        $this->route->request(RegexRoute::METHOD_POST, '/');
    }

    /**
     * Test getting regEx match pattern
     *
     * @covers \Brujo\Http\Route\RegexRoute::getMatch
     */
    public function testGetMatch()
    {
        $uri = '/foo/u(\d+)/bar/(\s+)';
        $this->route->setUri($uri);
        $this->assertEquals($uri, $this->route->getMatch());
    }
}
