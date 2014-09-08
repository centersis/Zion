<?php

require_once 'C:/xampp/htdocs/Zion/Lib/Zion/ClassLoader/UniversalClassLoader.php';

use Zion\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespace("Zion","C:/xampp/htdocs/Zion/Lib/Zion");
$loader->register();