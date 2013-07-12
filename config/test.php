<?php
/**
 * Test environment
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 */

$parent = include __DIR__ . DIRECTORY_SEPARATOR . 'development.php';
$config = [];

return \Brujo\Configuration::merge($parent, $config);