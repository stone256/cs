<?php
/**
 * @name 	: cli local config
 * @author 	: peter<stone256@hotmail.com>
 *
 * this file will be executed just before dispatch from router to controller only place
 *   hooks here concern your system init
 */



/**** /
 //enable session module and use mysql as session storage
_factory('session_model_default');
register_shutdown_function('session_write_close');
/****/
