<?php
/**
 * default api controller
 *
 */
class sitemin_api__apiController extends _system_defaultController {
    function __construct() {
        session_start();
        $this->query = _factory('sitemin_api_model_api')->get_query();
        $this->query['login_id'] = $this->query['id'];
    }
    function loginAction() {
        $q = $this->query;
        if ($u = _factory('sitemin_api_model_user')->login_check($q['login_id'], $q['key'])) {
            $u['token'] = md5(uniqid(), false);
            $u['key'] = $q['key'];
            defaultHelper::data_set('sitemin_api', $u);
        }
        $this->_return(array("status" => $u['token'] ? "success" : "failed", 'token' => $u['token']));
    }
    function dispatchAction() {
        //error_reporting(E_ALL);
        $q = $this->query;
        if ($q['token']) {
            $user = defaultHelper::data_get('sitemin_api');
            if ($user['token'] === $q['token']) {
                $q['login_id'] = $user['login_id'];
                $q['key'] = $user['key'];
            }
        }
        //authentication and authorization
        if (!($u = _factory('sitemin_api_model_user')->AA($q))) {
            $this->_return(array('status' => 'failed', 'value' => 'No More Quota'));
        }
        $u['token'] = md5(uniqid(), false);
        $u['key'] = $q['key'];
        //find gateway
        $url = $_SERVER['REDIRECT_URL'];
        $url = str_replace(_X_URL_OFFSET, '', $url);
        $api = _factory('sitemin_api_model_api')->get_acl_by_url($url);
        //check acl
        if (!_factory('sitemin_api_model_acl')->check_acl($u, $api)) {
            $this->_return(array('status' => 'failed', 'value' => 'sector open failed'));
        }
        $path = $api['path'];
        $p = preg_replace('/(^\/|\.php$)/ims', '', $path);
        $class_name = str_replace('/', '_', $p);
        $action = xpAS::preg_get($url, '/[^\/]+$/ims');
        $r = _factory($class_name)->$action($q);
        $r['token'] = $u['token'];
        //set user session with token and id
        defaultHelper::data_set('sitemin_api', $u);
        $this->_return($r);
    }
    function _return($arr) {
        if ($this->query['debug']) $arr['query'] = $this->query;
        die(json_encode($arr));
    }
}
// * array (
// 'REDIRECT_STATUS' => '200',
// 'HTTP_HOST' => 'data.invigorinsights.com',
// 'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
// 'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,* / *;q=0.8',
// 'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.5',
// 'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
// 'HTTP_COOKIE' => 'PHPSESSID=1pke24nggefti6cc5l4m64vhp3',
// 'HTTP_CONNECTION' => 'keep-alive',
// 'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
// 'HTTP_CACHE_CONTROL' => 'max-age=0',
// 'PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin',
// 'SERVER_SIGNATURE' => 'Apache/2.4.7 (Ubuntu) Server at data.invigorinsights.com Port 80',
// 'SERVER_SOFTWARE' => 'Apache/2.4.7 (Ubuntu)',
// 'SERVER_NAME' => 'data.invigorinsights.com',
// 'SERVER_ADDR' => '172.31.29.28',
// 'SERVER_PORT' => '80',
// 'REMOTE_ADDR' => '61.68.21.86',
// 'DOCUMENT_ROOT' => '/var/www/data/public',
// 'REQUEST_SCHEME' => 'http',
// 'CONTEXT_PREFIX' => '',
// 'CONTEXT_DOCUMENT_ROOT' => '/var/www/data/public',
// 'SERVER_ADMIN' => 'webmaster@localhost',
// 'SCRIPT_FILENAME' => '/var/www/data/public/index.php',
// 'REMOTE_PORT' => '36396',
// 'REDIRECT_QUERY_STRING' => '{%22aaaa%22:0}',
// 'REDIRECT_URL' => '/api/v2/reports/price-history',
// 'GATEWAY_INTERFACE' => 'CGI/1.1',
// 'SERVER_PROTOCOL' => 'HTTP/1.1',
// 'REQUEST_METHOD' => 'GET',
// 'QUERY_STRING' => '{%22aaaa%22:0}',
// 'REQUEST_URI' => '/api/v2/reports/price-history?{%22aaaa%22:0}',
// 'SCRIPT_NAME' => '/index.php',
// 'PHP_SELF' => '/index.php',
// 'REQUEST_TIME_FLOAT' => 1510873170.402,
// 'REQUEST_TIME' => 1510873170,
// )
