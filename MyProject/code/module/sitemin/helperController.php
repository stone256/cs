<?php
class sitemin_helperController {
    function __construct() {
        session_start();
    }
    function vcodeAction() {
        xpCaptcha::generate(array('length' => 6, 'dot' => array('x' => 8, 'y' => 8)));
    }
    /**
     *utility
     */
    function pongAction() {
        echo ($_REQUEST['mark'] ? $_REQUEST['mark'] : "324118787");
        exit;
    }
    function checkmyipAction() {
        echo xpAS::get_client_ip();
    }
    function beautifyAction() {
        $q = $_REQUEST;
        if($q['save']){
            $tmpfile = tmpfile();       
            $tmpfile_path = stream_get_meta_data($tmpfile)['uri'];
            $tmpfile_content = file_put_contents($tmpfile_path, $q['con']);
            $cmd = "php_beautifier -f {$tmpfile_path} 2>&1";
            $con = shell_exec($cmd);

            die($con);
        }
        $rs['tpl'] = '_php_beautify.phtml';
        $rs['TITLE'] = 'PHP BEAUTIFY';
        return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
    }  
    function tidyAction() {
        $q = $_REQUEST;
        if($q['save']){
            $config = array(
                'indent'         => true,
                'output-xhtml'   => true,
                'wrap'           => 200
            );
            $tidy = new tidy();
            $tidy->ParseString($q['con'], $config, 'utf8');
            $tidy->cleanRepair();
            die($tidy);
        }
        $rs['tpl'] = '_html_tidy.phtml';
        $rs['TITLE'] = 'HTML TIDY';
        return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
    }   
    function passwordgeneratorAction() {
        $q = $_REQUEST;
        if ($q['save']) {
            $r = md5(md5($q['hint']) . xpAS::roller($q['hint']));
            if ($q['short']){
                for($i=0; $i<7; $i++){
                    $r = md5(preg_replace('/^./ims', '', $r));
                    $a .= $r{0};
                }
                $r = $a;
            } 
            die($r);
        }
        $rs['tpl'] = '_password_generator.phtml';
        $rs['TITLE'] = 'PASSWORD GENERATOR';
        return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
    }
    function qrcodeAction() {
        $q = $_REQUEST;
        $rs['tpl'] = '_qrcode_generator.phtml';
        $rs['TITLE'] = 'QRCODE GENERATOR';
        return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
    }
    function module_createAction() {
        //$rs = "aaa";
        $q = $_REQUEST;
        if ($q['save']) {
            $q['msg'] = _factory('helper_model_module')->create($q);
        }
        return array('data' => array('rs' => $q));
    }

}
