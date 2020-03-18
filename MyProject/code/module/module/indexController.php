<?php
/**
 * depend on sitemin module
 */
class module_indexController extends sitemin_indexController {
    function createAction() {
        $q = $this->q;
        $token_name = 'module,create';
        $_token = defaultHelper::page_hash_get($token_name);
        switch ($q['step']) {
            case 'file permission':
                $r = _factory('module_model')->test_permission(_X_MODULE);
                die(json_encode(['status' => $r['failed'] ? 'failed' : 'success', 'data' => $r]));
            break;
            case 'test name':
                $r = _factory('module_model')->test_name($q);
                die(json_encode(['status' => $r ? 'success' : 'failed', 'data' => $r]));
            break;
            case "create":
                $r = _factory('module_model')->create($q);
                die(json_encode(['status' => $r ? 'failed' : 'success', 'data' => $r]));
            break;
        }
        $rs['_token'] = defaultHelper::page_hash_set($token_name);
        $rs['tpl'] = '/module/view/_create.phtml';
        $rs['TITLE'] = 'MODULE CREATE';
        return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
    }
    function installAction() {
        $q = $this->q;
        $token_name = 'module,install';
        $_token = defaultHelper::page_hash_get($token_name);
        if ($q['cmd'] && $q['_token'] == $_token) {
            switch ($q['cmd']) {
                case 'file permission':
                    $r = _factory('module_model')->test_permission([_X_MODULE]);
                    die(json_encode(['status' => $r['failed'] ? 'failed' : 'success', 'data' => $r]));
                break;
            }
        }
        $b = shell_exec("unzip --version 2>&1");
        $rs['unzip'] = preg_match('/not\s+found/ims', $b) ? 0 : 1;
        if ($_FILES['name'] /* && $q['_token'] == $_token */
        ) {
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
                case 'file permission':
                    $r = _factory('module_model')->test_permission([_X_MODULE_ENABLED]);
                    die(json_encode(['status' => $r['failed'] ? 'failed' : 'success', 'data' => $r]));
                break;
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
        $token_name = 'module,beautify';
        $_token = defaultHelper::page_hash_get($token_name);
        switch ($q['cmd']) {
            case 'file permission':
                $dirs = xpFile::dir_in_dir(_X_MODULE, 1);
                $r = _factory('module_model')->test_permission($dirs);
                die(json_encode(['status' => $r['failed'] ? 'failed' : 'success', 'data' => $r]));
            break;
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
            $rs['tree'] = xpAS::tree_with_tick(xpFile::dir_tree(_X_CODE), 'root', 50);
            //_d($rs, 1);
            $rs['_token'] = defaultHelper::page_hash_set($token_name);
            $rs['tpl'] = '/module/view/_beautfy.phtml';
            $rs['TITLE'] = 'PHP BEAUTIFY';
            return array('view' => '/sitemin/view/index.phtml', 'data' => array('rs' => $rs));
        }
}
