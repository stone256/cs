<?php
class module_model {

    //check module 
    var $access_file = [ _X_MODULE ];
    function test_permission(){
        foreach ($this->access_file as $f) {
            $p[is_writable($f) ? 'ok' : 'failed'][] = $f;
        }
        return $p;
    }
    function test_name($q) {
        $name = preg_replace('/[^a-z0-9_]/ms', '', $q['name']);
        $name = preg_replace('/^[^a-z]+/ms', '', $name);
        //check if already existed
        $has_module = file_exists(_X_MODULE . "/{$name}");
        $has_enable0 = file_exists(_X_MODULE_ENABLED . "/{$name}");
        $has_enable1 = file_exists(_X_MODULE_ENABLED . "/{$name}.php");

        //any about , failed!
        return $has_module  ||   $has_enable0 || $has_enable1  ?  false : $name;
    }

    function create($q){

        $name = preg_replace('/[^a-z0-9_]/ms', '', $q['name']);
        $name = preg_replace('/^[^a-z]+/ms', '', $name);

        //create module folder
        $p = _X_MODULE . "/{$name}";
        if(!mkdir($p, 0775,1))
            $msh[] = 'module create failed';

        //create controller (index)
        $con = "<";
        $con .= "?php
        //to extends sitemin_indexController, you will also change user->router->alc
        class {$name}_indexController  /* extends sitemin_indexController */{
            function indexAction(){
                return array('data' => array('rs' => ['controller'=>__FILE__, 'model'=>_factory('{$name}_model_default')->get()]));      
            }
        }
        ";
        if(!file_put_contents("{$p}/indexController.php", $con))
            $msg[] = "write to controll file failed\n - {$path}/indexController";
        


        //create default view
        $con ='
            <h1>default view</h1>
            
            <h3>Controller file : <?=_rp($rs["controller"]);?></h3>
            <h3>Model file : <?=_rp($rs["model"]);?></h3>
            <h3>View file : <?=_rp(__FILE__);?></h3>

        ';
        $p = _X_MODULE . "/{$name}/view/index";
        if(!mkdir($p, 0775,1))
            $msh[] = 'view folder create failed';
        if(!file_put_contents("{$p}/index.phtml", $con))
            $msg[] = 'write to default view file failed';

        //create default model
        $con = '<';
        $con .= "?php

                class {$name}_model_default{
                    
                    function get(){
                        return __FILE__;
                    }
                }
                ";
        $p = _X_MODULE . "/{$name}/model";
        if(!mkdir($p, 0775,1))
            $msh[] = 'model folder create failed';
        if(!file_put_contents("{$p}/default.php", $con))
            $msg[] = 'write to model file failed';

        //create default router
        $con ="<";
        $con .= '?php
            $routers = [
                "/'.$name.'/index" => "/'.$name.'/index@index",
            ];
        ';
        $p = _X_MODULE . "/{$name}/.router.php";
        if(!file_put_contents("{$p}", $con))
            $msg[] = 'write to .reouer.php file failed';

        return $msg;
    }

    function upload($f){

// 1..........[
// 2................name=>b.zip
// 2................type=>application/x-zip-compressed
// 2................tmp_name=>/tmp/phpoAVnl5
// 2................error=>0
// 2................size=>2386
// 1..........]

                //move to pool to ubzip it
                $tmp = "m_".xpAS::random_string(5);
                $path =  _X_TMP . '/'. $tmp;
                $new_filename = 'module.zip';
                xpFile::upload($f, $path, $new_filename);
                $zip = new ZipArchive();
                $zip->open($path .'/'.$new_filename);
                $zip->extractTo($path);
                $zip->close();
                unlink($path.'/'.$new_filename);
                $m = glob("$path/*");
                if(count($m) != 1 ){
                        $err[] = "* Module must be zipped with module folder!";
                        $err[] = " - the folder name will be used as module's name";
                }
                if(!$err){
                        $name = $m[0];
                        $_n = basename($m[0]);
                }
                if(!$err){
                        if(file_exists(_X_MODULE.'/'.$_n)){
                                $err[] = '* module already existed!';
                                $err[] = ' - installation stopped!';
                        }
                }
                if(!$err){
                        //moving the module
                        rename($name, _X_MODULE.'/'.$_n);
                        $note[] =  "The module: \"$_n\" installed.";
                        $note[] =  "- Please use 'setting->module->enable disable' to enable the module";
                        if(file_exists(_X_MODULE.'/'.$_n.'/installation.txt')){
                                $nn = explode("\n", file_get_contents(_X_MODULE.'/'.$_n.'/installation.txt') );
                                $mm[] = '---';
                                $note = xpAS::extend($note,$mm);
                                $note = xpAS::extend($note,$nn);
                        }
                }
                rmdir($path);
                return $err ? $err : $note;

    }
    function gets() {
        $do_not_touch = ['module', 'sitemin']; //not control here
        $enrr = $this->_enabled_modules();
        //all modules
        foreach (xpFile::file_in_dir(_X_MODULE, array('level' => 10, 'path' => 1)) as $k => $v) {
            if (!preg_match('/\.router\.php$/ims', $v)) continue;
            $path = str_replace(array(_X_MODULE . '/', '/.router.php'), array('', ''), $v);
            $arr = array('name' => $enrr['/' . $path] ? $enrr['/' . $path] : str_replace('/', '-', $path), 'path' => '/' . $path, 'enabled' => $enrr['/' . $path] ? 'YES' : 'NO',);
            if (in_array($arr['name'], $do_not_touch)) {
                $arr['enabled'] = 'Manual control!';
            }
            $crr[] = $arr;
        }
        return $crr;
    }

    function status($q) {
        $enrr = $this->_enabled_modules();
        //remove backpoint
        $path = str_replace('../', '', $q['p']);
        $_p = _X_MODULE_ENABLED . $path;
        $_n = preg_replace('/^\-/', '', str_replace('/', '-', $path));
        if ($enrr[$path]) { //enabled module
            //disabled it by remove enabled xx.php
            $this->_disable_module($path);
        } else {
            //enabled it if the module existed by adding enabled.php to config/enabled
            //create path;
            mkdir($_p, 0777, 1);
            $enable = "<?php" . "\n\n\n" . '$modules["' . $_n . '"] = "' . $path . '";' . "\n\n\n";
            $file = "$_p/$_n.php";
            file_put_contents($file, $enable);
            chmod($file, 0664);
        }
        return true;
    }
    function _disable_module($path) {
        //find the file and remove it, also remove folder if is empty
        foreach (xpFile::file_in_dir(_X_MODULE_ENABLED, array('level' => 15, 'path' => true)) as $k => $v) {
            $modules = null;
            include $v;
            if (!in_array($path, $modules)) continue;
            //remove the file
            unlink($v);
            //remove empty folder until _X_MODULE_ENABLED
            $this->__remove_empty_folder(dirname($v), _X_MODULE_ENABLED);
        }
    }
    function __remove_empty_folder($path, $until = null) {
        if ($path == $until) return; //  done!
        if (xpFile::file_in_dir($path)) rmdir($path);
        $this->__remove_empty_folder(dirname($path), $until);
    }
    function _enabled_modules() {
        global $modules;
        foreach ($modules as $k => $v) {
            $enrr[$v] = !is_numeric($k) ? $k : xpAS::preg_get($v, '/[^\/]+$/ims');
        }
        return $enrr;
    }
}
