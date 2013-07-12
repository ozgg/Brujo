<?php
/**
 * Entry point for web application
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 */

require __DIR__ . '/../../../bootstrap.php';

$application = new \Brujo\Http\Application(realpath(__DIR__ . '/../'));
$application->run();
