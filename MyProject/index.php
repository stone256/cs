<?php
/**
 * X enter file
 * @author peter wang <stone256@hotmail.com>
 * @copyright GPL
 * @version 2.1
 *
 */
//fix issue $_SERVER['REQUEST_TIME.. with microtime
define('_X_START_TIME', microtime(1));

// DIRECTORY_SEPARATOR
if(!defined('DS')) define('DS', '/');	// for linux only
if(!defined('PHP_EOL')) define('PHP_EOL', "\n");

//my root
define('_X_ROOT', __DIR__);
define('_X_CODE', _X_ROOT . DS .'code');

//load framework
require_once(_X_CODE . DS . '_system' . DS . 'app.php');

//_ud(1);
//init framework
$app= new app();


$html = $app->run();
//
echo $html;
