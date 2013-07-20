<?php
/**
 * Test case for HasParameters trait
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Test\Unit\Brujo\Traits
 */

namespace Test\Unit\Brujo\Traits;

use Brujo\Test\TestCase;
use Brujo\Traits\HasParameters;

/**
 * Test case for HasParameters trait
 *
 * @covers \Brujo\Traits\HasParameters
 */
class HasParametersTest extends TestCase
{
    /**
     * Tested instance
     *
     * @var HasParameters
     */
    private $trait;

    /**
     * Setup: get instance of object having trait
     *
     * Creates instance of object having trait and sets sample parameters for it
     */
    protected function setUp()
    {
        $traitName   = '\\Brujo\\Traits\\HasParameters';
        $parameters  = $this->getSample('parameters');
        $this->trait = $this->getObjectForTrait($traitName);
        $this->trait->setParameters($parameters);
    }

    /**
     * Data provider for getParameter
     *
     * @return array
     */
    public function getParameterProvider()
    {
        return [
            ['first', 'some_string'],
            ['second.second_number', 42],
            ['third.third_array', ['c', 'd']],
            ['third.third_deep', ['simple' => 'value', 'nested' => ['e', 'f']]],
            ['third.third_deep.nested', ['e', 'f']],
        ];
    }

    /**
     * Test getting parameter
     *
     * @param string $parameter
     * @param mixed $expected
     * @dataProvider getParameterProvider
     * @covers \Brujo\Traits\HasParameters::getParameter
     */
    public function testGetParameter($parameter, $expected)
    {
        $value = $this->trait->getParameter($parameter);
        $this->assertEquals($expected, $value);
    }

    /**
     * Test setting nested parameter
     *
     * @covers \Brujo\Traits\HasParameter::setParameter
     */
    public function testSetParameter()
    {
        $value = rand(0, 500);
        $this->trait->setParameter('some.nested.thing', $value);
        $parameters = $this->trait->getParameters();

        $this->assertTrue(isset($parameters['some']['nested']['thing']));
        $this->assertEquals($value, $parameters['some']['nested']['thing']);
    }
}
