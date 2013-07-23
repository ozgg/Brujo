<?php
/**
 * Compacted REST routes
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 */

return [
    '/' => [
        'users' => [],
        'deeds' => ['types', 'examples'],
    ],
    '/v2' => [
        '_prefix' => 'another',
        'users'   => ['depths'],
    ]
];
