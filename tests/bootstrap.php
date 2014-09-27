<?php

$autoloader = __DIR__ . '/../vendor/autoload.php';

$loader = require $autoloader;
$loader->add('Cron', __DIR__ . '/src');
