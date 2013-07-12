<?php
/**
 * Application routes
 * 
 * Date: 12.07.13
 * Time: 17:23
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 */

use Brujo\Http\Route;

return [
    'home'  => [
        'type'       => Route::TYPE_STATIC,
        'uri'        => '/',
        'controller' => 'index',
        'action'     => 'index',
    ],
    'about' => [
        'uri'        => '/about',
        'controller' => 'index',
        'action'     => 'about',
        'methods'    => [Route::METHOD_GET],
    ],
    'demo-post' => [
        'uri'        => '/demo-post',
        'controller' => 'index',
        'action'     => 'demoPost',
        'methods'    => [Route::METHOD_POST],
    ],
];
