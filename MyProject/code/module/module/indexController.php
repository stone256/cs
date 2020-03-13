<?php
/**
 * depend on sitemin module
 */
class module_indexController extends sitemin_indexController {
        function installAction() {
                $q = $this->q;
                $token_name = 'module,install';
                $_token = defaultHelper::page_hash_get($token_name);


                $b = shell_exec("unzip --version 2>&1");
                $rs['unzip'] = preg_match('/not\s+found/ims', $b) ? 0 : 1;

                if ($_FILES['name']/* && $q['_token'] == $_token */) {
                        //uploading
                        $rs['msg'] = _factory('module_model')->upload('name');

                }
                $rs['_token'] = defaultHelper::page_hash_set($token_name);
                $rs['tpl'] = '/module/view/_install.phtml';
                $rs['TITLE'] = 'MODULE INSTALL';
                return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
        }
    function controlAction() {
        $q = $this->q;
        $token_name = 'module,control';
        $_token = defaultHelper::page_hash_get($token_name);
        if ($q['cmd'] && $q['_token'] == $_token) {
            switch ($q['cmd']) {
                case 'list':
                    $r = _factory('module_model')->gets();
                    die(json_encode($r));
                case "status":
                    $r = _factory('module_model')->status($q);
                    die(json_encode($r));
            }
        }
        $rs['_token'] = defaultHelper::page_hash_set($token_name);
        $rs['tpl'] = '/module/view/_control.phtml';
        $rs['TITLE'] = 'MODULE CONTROL';
        return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
    }
    function beautifyAction() {
        $q = $_REQUEST;
        switch ($q['cmd']) {
            case 'apply':
                $p = preg_replace('/[^A-Za-z0-9\+\-\_\s\(\)\.\/]/ims', ' ', $q['v']);
                if (!preg_match('/\.php$/ims', $p)) die("$p is not php file");
                if (preg_match('/\s/ims', $p)) die("$p, space is not allowed");
                $cmd = "php_beautifier -f {$p}";
                $con = shell_exec($cmd);
                file_put_contents($p, $con);
                die($p);
            }
            //this function need PHP_Beautifier
            //test PHP_Beautifier
            $a = shell_exec("php_beautifier --version 2>&1");
            $rs['beautifier'] = preg_match('/not\s+found/ims', $a) ? 0 : 1;
            //quick tree build
            $rs['tree'] = xpAS::tree_with_tick(xpFile::dir_tree(_X_ROOT), 'root', 50);
            //_d($rs, 1);
            $rs['_token'] = defaultHelper::page_hash_set($token_name);
            $rs['tpl'] = '/module/view/_beautfy.phtml';
            $rs['TITLE'] = 'MODULE CONTROL';
            return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
        }
}
