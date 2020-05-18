<?php


   /*
       @author Wu Xiancheng
       Code structure for PHP 7.0+ only because SessionUpdateTimestampHandlerInterface is introduced in PHP 7.0
       With this class you can validate php session id and update the timestamp of php session data
       with the OOP prototype of session_set_save_handler() in PHP 7.0+
    */
    class session_model_default implements SessionHandlerInterface /*, SessionUpdateTimestampHandlerInterface*/ {
        var $table;

        public function __construct(){
    
            $cfg['db'] = _config('default,db');
            $this->table = xpTable::load('sessions', $cfg);
            // Set handler to overide SESSION
            session_set_save_handler(
                array($this, "open"),
                array($this, "close"),
                array($this, "read"),
                array($this, "write"),
                array($this, "destroy"),
                array($this, "gc")
            );
    
            register_shutdown_function('session_write_close');
            //session_start();
        }

        public function close(){
            return true;
        }
        public function destroy($sessionId){
            $this->table->deletes(['id'=>$sessionId]);
        }
        public function gc($maximumLifetime){
            $old = time() - $maximumLifetime;
            $this->table->deletes(["access < $old "]);
        }
        public function open($sessionSavePath, $sessionName){
            return true;
        }
        public function read($sessionId){
            $r = xpAS::get($this->table->get(['id'=>$sessionId]), 'data');
            return is_null($r) ? '' : $r;
        }
        public function write($sessionId, $sessionData){
            $this->table->write(['id'=>$sessionId, 'access'=>time(), 'data'=>$sessionData],['id'=>$sessionId]);
        }
        public function create_sid(){
            return md5(time(), time());
        }
        public function validateId($sessionId){
            return $this->table->get(['id'=>$sessionId]);
        }
        public function updateTimestamp($sessionId, $sessionData){
            return $this->table->updates(['access'=>time()], ['id'=>$sessionId]);
        }
    }







/**** /
< ? php
//require "path/to/sessionhandler.class.php"; 
new session_model_default();
/***/
