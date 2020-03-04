<?php
/**
 * @author 	 peter wang<stone256@hotmail.com>
 * @copyright MIT;
 * system config
 * @// NOTE:  DO NOT change this unless you know what you're doing
 *
 */
error_reporting(E_ERROR);
//error_reporting(E_ALL);
ini_set('display_errors', 1);
//site root definde in index.php at project root of cs
$_root = realpath($_SERVER['DOCUMENT_ROOT']);
$_root = str_replace('\\', '/', $_root);
define('_X_OFFSET', str_replace($_root, '', _X_ROOT));
define('_X_SYSTEM', _X_CODE . DS . '.system');
define('_X_CONFIG', _X_CODE . DS . 'config');
define('_X_MODULE_ENABLED', _X_CONFIG . DS . 'enabled');
define('_X_MODEL_OVERWRITE', _X_CONFIG . DS . 'overwrite');
define('_X_MODULE', _X_CODE . DS . 'module');
define('_X_PACKAGE', _X_CODE . DS . 'package');
define('_X_PACKAGE_LIB', _X_PACKAGE . DS . '_lib');
define('_X_PACKAGE_VENDOR', _X_PACKAGE . DS . '_vendor');
define('_X_DATA',  _X_CODE . DS . 'data');
define('_X_SYSTEM_DATA', _X_DATA . DS . 'system');
define('_X_CACHE', _X_DATA . DS . 'cache');
define('_X_HISTORY', _X_DATA . DS . 'history');
define('_X_BATCH', _X_DATA . DS . 'batch');
define('_X_TMP', _X_DATA . DS . 'tmp');
define('_X_LOG', _X_DATA . DS . 'log');
define('_X_LOG_MYSQL', _X_LOG . DS . 'mysql');
define('_X_VAR', _X_DATA . DS . 'var');
define('_X_POOL', _X_DATA . DS . 'pool');
// session if need
define('_X_SESSION_PATH', _X_DATA . DS . 'session');
define('_X_PUBLIC', _X_ROOT . DS . 'public');
define('_X_MEDIA', _X_PUBLIC . DS . 'media');
define('_X_UPLOAD', _X_MEDIA . DS . 'upload');
define('_X_LAYOUT', _X_CODE . DS . 'layout');
//root of the URL,  e.g. service.page.com.au:85/myclient/project3
// * http:// or https://
define('_X_URL_P', $_SERVER['HTTPS'] ? 'https://' : 'http://');
define('_X_URL_OFFSET', str_replace('\\', '/', _X_OFFSET));

define('_X_URI', $_SERVER['HTTP_HOST'] . _X_URL_OFFSET);
define('_X_URL', _X_URL_P . _X_URI);
define('_X_URL_PUBLIC', _X_URL . '/public');
define('_X_URL_ROUTER', preg_replace('/\/public\/*$/ims', '', _X_URL_PUBLIC));
define('_X_URL_REQUEST', _X_URL . $_SERVER['REQUEST_URI']);
define('_X_URL_CSS', _X_URL_PUBLIC . '/css');
define('_X_URL_JS', _X_URL_PUBLIC . '/js');
define('_X_URL_MEDIA', _X_URL_PUBLIC . '/media');
define('_X_URL_IMAGE', _X_URL_MEDIA . '/image');
define('_X_URL_VIDEO', _X_URL_MEDIA . '/video');
define('_X_URL_UPLOAD', _X_URL_MEDIA . '/upload');

define('_X_FAILED', 'failed');
define('_X_SUCCESS', 'success');
