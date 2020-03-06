<?php
/**
 * default sitemin controller
 *
 */
//error_reporting(E_ALL);
class sitemin_loginController extends _system_defaultController {
    var $captcha;
   // var $captcha_type = ['off' => 'off', 'googlev2' => 'googlev2', 'local' => 'local', "QR"];
    function __construct() {
        $this->q = $_REQUEST;
        $_p = _url();
        session_start();
        //$this->return_url = defaultHelper::return_url();
        if (_factory('sitemin_model_var')->get('sitemin/log') && $_p != '/sitemin/keepalive') _factory('sitemin_model_log')->insert();
        $this->_captcha();
    }
    function _captcha() {
        $type = _factory('sitemin_model_captcha')->type[_factory('sitemin_model_var')->get('sitemin/captcha') ];
        $type = $type ? : 'off';
        //_d($type, 1);
        $this->captcha = _factory('sitemin_model_captcha_' . $type);
        //testing
        //$this->captcha->test();

    }
    function dashboardAction() {
        $rs['tpl'] = 'user' . DS . '_dashboard.phtml';
        $rs['TITLE'] = 'SITEMIN DASHBOARD';
        return array('view' => DS . 'sitemin' . DS . 'view' . DS . 'index.phtml', 'data' => array('rs' => $rs));
    }
    function testAction() {
        $rs['tpl'] = '_test.phtml';
        $rs['TITLE'] = 'SITEMIN TEST';
        return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
    }
    function _short($r) {
        //return base_convert(array_sum(str_split($r)), 16, 36);
        $ds = str_split($r);
        foreach ((array)$ds as $k => $d) {
            if ($k % 2) unset($ds[$k]);
        }
        $r = implode('', $ds);
        if (strlen($r) > 12) $r = $this->_short($r);
        return $r;
    }
    function requestpasswordAction() {
        $q = $this->q;
        if (!$this->captcha->html() || $l->captcha($q)) $r = _factory('sitemin_model_login')->sendresetpasswordlink($this->q);
    }
    function resetpasswordAction() {
        $q = $this->q;
        // die(asdfasdf);
        if ($q['save']) {
            if (!$this->captcha->html() || $l->captcha($q)) {
                $q['n2048'] = $q['p1'];
                _factory('sitemin_model_user')->update_password($q);
            }
            sleep(3);
            exit;
        } else {
            $hash = key($q);
            //check hash
            $u = _factory('sitemin_model_user')->check_hash($hash);
            if (!$u) $rs['overdue'] = 1;
            $rs['hash'] = $hash;
            $rs['user'] = $u;;
        }
        //check hash
        //if google bot check used
        //f**k the captcha which is not able to work in old firefox: _x_captcha ...com/sitemin/login
        $rs['google_key'] = _factory('sitemin_model_var')->get('sitemin/google/captcha/key');
        $rs['captcha_html'] = $this->captcha->html();
        $rs['no_captcha'] = !$this->captcha;
        $rs['tpl'] = 'user/_resetpassword.phtml';
        $rs['TITLE'] = 'SITEMIN USER';
        return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
    }
    function loginAction() {
        if (!($r = defaultHelper::return_url())) $r = _X_URL . '/sitemin/dashboard';
        $q = $_REQUEST;
        if($q['cmd'] == 'QRcheck'){
                $data= json_decode(base64_decode($q['data']), 1);
                if(!$this->captcha->check($data)) die('Check Failed');
                $q['email'] = $data[0];
                $q['password'] = $data[1];
                $r = $this->_login($q);
                $_SESSION['QRmark'] = $r['status'] == 'failed' ? 0 : 1;
                die($r['status'] == 'failed' ? 'Check Failed' : 'Check OK<script>window.close();</script>');
        }
        if($q['isQRlogin']){
                die($_SESSION['QRmark'] ?  'ok':'failed');
        }
        if ($q['password']) { //try login
            $ret = $this->_login($q);
            if ($ret['status'] == 'ok') {
                $u = _factory('sitemin_model_login')->current();
                $ip = xpAS::get_client_ip();
                $u['ip'] = $ip;
                _factory('sitemin_model_message')->send_to_group('sitemin', 'user ' . $u['id'] . ' ' . ($u['email'] ? '-' . $u['email'] : '@' . base64_decode($u['username'])) . " logged in from $ip", -1);
                $arr = array('user_id' => $u['id'], 'router' => 'logged in', 'data' => $u,);
                _factory('sitemin_model_log')->insert($arr);
            }
            sleep(1); //slow down
            die(json_encode($ret));
        }
        $rs['captcha_html'] = $this->captcha->html();
        $rs['google_key'] = _factory('sitemin_model_var')->get('sitemin/google/captcha/key');
        $rs['ret'] = $r;
        $rs['tpl'] = 'user/_login.phtml';
        $rs['TITLE'] = 'SITEMIN LOGIN';
        return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
    }
    function _login($q) {
        $l = _factory('sitemin_model_login');
        if (!$this->captcha->validate($q)) return array('status' => 'failed', 'msg' => 'Robot check failed', 'msg_type' => 'warning');
        if (!$ret && !($r = $l->login($q))) $ret = array('status' => 'failed', 'msg' => 'login failed, username and password are not match', 'msg_type' => 'warning');
        if (!$ret) $ret = array('status' => 'ok', 'msg' => xpAS::get(defaultHelper::data_get('admin,login'), 'permission,login'), 'msg_type' => xpAS::get(defaultHelper::data_get('admin,login'), 'user,username'));
        return $ret;
    }
    function logoutAction() {
        _factory('sitemin_model_login')->logout();
        xpAS::go(_X_URL . DS . 'sitemin');
    }
}
