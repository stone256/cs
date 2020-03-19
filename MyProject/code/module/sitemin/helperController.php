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
    /**
     * example of batch  method
     */
    function batchAction() {
        $q = $_REQUEST;
        if (!$q['id']) {
            $q['id'] = uniqid();
            $begin = true;
            /**
             * initialize something here !!!
             */
            sleep(2);
        }
        /**
         * define batch size ...
         */
        $this->batch = array('id' => $q['id'], //batch id
        'size' => $q['size'] ? $q['size'] : 5, //block size
        'index' => $begin ? 0 : $q['index'], //block number index
        'end' => 300, //items count
        );
        $start = ($this->batch['index'] * $this->batch['size'] + 1);
        $end = min($this->batch['end'], ($this->batch['index'] + 1) * $this->batch['size']);
        $this->_batch_msg("Block#:" . $this->batch['index'] . " " . ($this->batch['index'] * $this->batch['size'] + 1) . ' = ' . ($this->batch['index'] + 1) * $this->batch['size']);
        /**
         * process something here !!
         *
         */
        $this->batch['status'] = ($this->batch['index'] + 1) * $this->batch['size'] >= $this->batch['end'] ? 1 : 0;
        /**
         * batch index (block number) will auto added one by script
         */
        /**
         * test end
         */
        if ($this->batch['status']) $this->_batch_msg("DONE!");
        echo json_encode($this->batch);
    }
    function _batch_msg($msg = '') {
        if (is_array($msg)) {
            $msg = "<pre>" . print_r($msg, 1) . "</pre>";
        }
        $this->batch['msg'].= $msg . "<br>\n";
    }
    /**** batch testing ***/
}
