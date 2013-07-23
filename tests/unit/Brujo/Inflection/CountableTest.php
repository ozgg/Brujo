<?php
/**
 * Test case for pluralization and singularization of countable nouns
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Test\Unit\Brujo\Inflection
 */

namespace Test\Unit\Brujo\Inflection;

use Brujo\Inflection\Countable;
use Brujo\Test\TestCase;

/**
 * Test case for pluralization/singularization
 *
 * @covers \Brujo\Inflection\Countable
 */
class CountableTest extends TestCase
{
    /**
     * Instance of tested class
     *
     * @var Countable
     */
    private $countable;

    /**
     * Data provider: plural form of words
     *
     * @return array
     */
    public function pluralFormProvider()
    {
        return [
            ['things', 'thing'],
            ['foos', 'foo'],
            ['flies', 'fly'],
            ['people', 'person'],
            ['pitches', 'pitch'],
            ['carcasses', 'carcass'],
            ['potatoes', 'potato'],
            ['booboo', 'booboo'],
            ['corpses', 'corpse'],
            ['guys', 'guy'],
            ['soliloquies', 'soliloquy'],
        ];
    }

    /**
     * Data provider: singular form of words
     *
     * @return array
     */
    public function singularFormProvider()
    {
        return [
            ['thing', 'things'],
            ['foo', 'foos'],
            ['fly', 'flies'],
            ['person', 'people'],
            ['pitch', 'pitches'],
            ['carcass', 'carcasses'],
            ['potato', 'potatoes'],
            ['corpse', 'corpses'],
            ['guy', 'guys'],
            ['soliloquy', 'soliloquies'],
        ];
    }

    /**
     * Test pluralization
     *
     * @param string $singular
     * @param string $expected
     * @dataProvider singularFormProvider
     * @covers \Brujo\Inflection\Countable::pluralize
     */
    public function testPluralize($singular, $expected)
    {
        $plural = $this->countable->pluralize($singular);
        $this->assertEquals($expected, $plural);
    }

    /**
     * Test singularization
     *
     * @param string $plural
     * @param string $expected
     * @dataProvider pluralFormProvider
     * @covers \Brujo\Inflection\Countable::singularize
     */
    public function testSingularize($plural, $expected)
    {
        $singular = $this->countable->singularize($plural);
        $this->assertEquals($expected, $singular);
    }

    /**
     * Make instance of tested class
     */
    protected function setUp()
    {
        $this->countable = new Countable;
    }
}
 