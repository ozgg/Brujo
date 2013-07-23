<?php
/**
 * Compacted REST routes for tests
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 */

return [
    '' => [
        'users'  => ['achievements', 'dreams'],
        'dreams' => [],
    ],
    '/v1' => [
        '_prefix' => 'alpha',
        'users'   => ['achievements', 'dreams', 'friends'],
        'dreams'  => ['clouds'],
    ],
    'v2' => [
        'users' => ['dreams'],
    ],
    'deeply/nested/something' => [
        '_prefix'   => 'deep',
        'interests' => [],
    ]
];
