<?php
/**
 * Development configuration
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 */

$parent = include __DIR__ . DIRECTORY_SEPARATOR . 'production.php';
$config = [];

return \Brujo\Configuration::merge($parent, $config);