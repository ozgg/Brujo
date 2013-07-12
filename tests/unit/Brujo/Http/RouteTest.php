<?php
/**
 * Test case for abstract route
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Test\Unit\Brujo\Http
 */

namespace Test\Unit\Brujo\Http;
 
use Brujo\Http\Route;
use Brujo\Test\TestCase;

/**
 * Test of abstract route
 *
 * @covers \Brujo\Http\Route
 */
class RouteTest extends TestCase
{
    /**
     * Data provider for testFactorySuccess
     *
     * @return array
     */
    public function factorySuccessProvider()
    {
        return [
            [Route::TYPE_STATIC,  '\\Brujo\\Http\\Route\\StaticRoute'],
            [Route::TYPE_REGEX,   '\\Brujo\\Http\\Route\\RegexRoute'],
            [Route::TYPE_REST,    '\\Brujo\\Http\\Route\\RestRoute'],
            [Route::TYPE_PATTERN, '\\Brujo\\Http\\Route\\PatternRoute'],
        ];
    }

    /**
     * Test factory with successful result
     *
     * @param string $type       route type
     * @param string $className  expected class name
     * @dataProvider factorySuccessProvider
     * @covers \Brujo\Http\Route::factory
     */
    public function testFactorySuccess($type, $className)
    {
        $route = Route::factory($type);

        $this->assertTrue($route instanceof $className);
    }

    /**
     * Test factory failure with unknown route type
     *
     * @expectedException \InvalidArgumentException
     * @covers \Brujo\Http\Route::factory
     */
    public function testFactoryFailure()
    {
        Route::factory('non-existent');
    }

    /**
     * Test initializing from array
     *
     * @covers \Brujo\Http\Route::initFromArray
     */
    public function testInitFromArray()
    {
        /** @var Route $route */
        $route = $this->getMockForAbstractClass('\\Brujo\\Http\\Route');
        $data  = [
            'name'       => 'testRoute',
            'methods'    => [Route::METHOD_GET, Route::METHOD_PUT],
            'reverse'    => '/yummy',
            'controller' => 'test',
            'action'     => 'tested',
        ];

        $route->initFromArray($data);

        $this->assertEquals($data['name'], $route->getName());
        $this->assertEquals($data['methods'], $route->getMethods());
        $this->assertEquals($data['reverse'], $route->getReverse());
        $this->assertEquals($data['controller'], $route->getControllerName());
        $this->assertEquals($data['action'], $route->getActionName());
    }
}
