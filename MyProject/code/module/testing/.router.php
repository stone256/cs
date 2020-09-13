<?php
/**
 * this router info used for none standard pathway other than normal controller located
 * rule of router path
 * 	1> standard router map to /modules. e.g. /x/a/b  => will resolve to /modules/[x]/[a]Controller.php :: [b]Action
 *	2> customized routers,
 * 		 * 		'/yrouter'=>'topview/ycon@zact' will map y to modules/[topview]/[ycon]Controller::[zact]Action()
 * 	 3> custom router always overwrites defaultone
 */

//testing
$routers['/testing'] = '/testing/index@index';
$routers['/testing/myip'] = '/testing/index@myip';

