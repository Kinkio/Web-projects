<?php
function autoload($className)
{
    $className = strtolower($className);
	//path to classes to load
    require_once 'backend/classes'.$className . '.php';
}
 
spl_autoload_register('autoload');
