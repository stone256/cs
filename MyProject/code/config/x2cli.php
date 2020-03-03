<?php
/**
 * @name 	: cli local config
 * @author 	: peter<stone256@hotmail.com>
 *
 *  follow value is similated $_SERVER values of web call.
 */
$apache_data = array (
        'REDIRECT_SERVER_ENV' => 'LOCAL_TEST',
        'REDIRECT_STATUS' => '200',
        'SERVER_ENV' => 'LOCAL_TEST',
        'HTTP_HOST' => 'www.mx19',
        'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:73.0) Gecko/20100101 Firefox/73.0',
        'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.5',
        'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
        'HTTP_CONNECTION' => 'keep-alive',
        'HTTP_COOKIE' => 'PHPSESSID=o81afibgdcvgioijorbuf6qgvg',
        'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
        'HTTP_PRAGMA' => 'no-cache',
        'HTTP_CACHE_CONTROL' => 'no-cache',
        'PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin',
        'SERVER_SIGNATURE' => '<address>Apache/2.4.38 (Debian) Server at www.mx19 Port 80</address>',
        'SERVER_SOFTWARE' => 'Apache/2.4.38 (Debian)',
        'SERVER_NAME' => 'www.mx19',
        'SERVER_ADDR' => '192.168.1.51',
        'SERVER_PORT' => '80',
        'REMOTE_ADDR' => '192.168.1.169',
        'DOCUMENT_ROOT' => '/var/www/',
        'REQUEST_SCHEME' => 'http',
        'CONTEXT_PREFIX' => '',
        'CONTEXT_DOCUMENT_ROOT' => '/var/www/',
        'SERVER_ADMIN' => 'webmaster@localhost',
        'SCRIPT_FILENAME' => '/var/www/html/cs/MyProject/index.php',
        'REMOTE_PORT' => '65409',
        'REDIRECT_URL' => '/html/cs/MyProject/sitemin/acl/router',
        'GATEWAY_INTERFACE' => 'CGI/1.1',
        'SERVER_PROTOCOL' => 'HTTP/1.1',
        'REQUEST_METHOD' => 'GET',
        'QUERY_STRING' => '',
        'REQUEST_URI' => '/html/cs/MyProject/sitemin/acl/router',
        'SCRIPT_NAME' => '/html/cs/MyProject/index.php',
        'PHP_SELF' => '/html/cs/MyProject/index.php',
        'REQUEST_TIME_FLOAT' => 1583111060.285,
        'REQUEST_TIME' => 1583111060,
);

/**
 * confirm it's a cli calls
 */
$apache_data['X2CLI_CALL'] = true;

define('__X_DEBUG', true);
