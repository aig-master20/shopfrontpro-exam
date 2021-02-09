<?php

function autoloader($class_name) {
    require_once $class_name . '.php';
}

spl_autoload_register('autoloader');

//DirectoryParser::printPaths(5, 2);
echo "<pre>";
var_dump(DirectoryParser::generateRandomPaths('/home/users/', 5, 3, 3));