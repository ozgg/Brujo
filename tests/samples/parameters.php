<?php
/**
 * Samples for testing HasParameters trait
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 */

return [
    'first'  => 'some_string',
    'second' => [
        'second_number' => 42,
        'second_array'  => ['a', 'b'],
        'second_string' => 'another_string',
    ],
    'third'  => [
        'third_array' => ['c', 'd'],
        'third_deep'  => [
            'simple' => 'value',
            'nested' => ['e', 'f'],
        ],
    ],
];
