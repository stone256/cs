<?php
/**
 * this router info used for none standard pathway other than normal controller located
 * rule of router path
 * 	1> standard router map to /modules. e.g. /x/a/b  => will resolve to /modules/[x]/[a]Controller.php :: [b]Action
 *	2> customized routers,
 * 		 * 		'/router'=>'topview/ycon@zact' will map y to modules/[topview]/[ycon]Controller::[zact]Action()
 * 	 3> custom router always overwrites defaultone
 */
// * note :  this module relay on sitemin module
##
##examples
##
#!!!change this to your own

$routers['/'] = '/examples/examples@index';
$routers['/examples'] = '/examples/examples@index';
$routers['/examples/blade'] = '/examples/examples@blade';
$routers['/examples/batch'] = '/examples/examples@batch';

$routers['/examples/pdo'] = '/examples/examples@pdo';
