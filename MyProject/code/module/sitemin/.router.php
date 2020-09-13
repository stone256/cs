<?php
/**
 * this router info used for none standard pathway other than normal controller located
 * rule of router path
 * 	1> standard router map to /modules. e.g. /x/a/b  => will resolve to /modules/[x]/[a]Controller.php :: [b]Action
 *	2> customized routers,
 * 		 * 		'/router'=>'topview/ycon@zact' will map y to modules/[topview]/[ycon]Controller::[zact]Action()
 * 	 3> custom router always overwrites defaultone
 */
define('_X_SITEMIN', true);
//error_reporting(E_ALL);

//for test controller
$routers['/sitemin/test'] = '/sitemin/login@test';
$routers['/sitemin/helper/vcode'] = '/sitemin/helper@vcode';
$routers['/sitemin/helper/checkmyip'] = '/sitemin/helper@checkmyip';
$routers['/sitemin/helper/passwordgenerator'] = '/sitemin/helper@passwordgenerator';
$routers['/sitemin/helper/qrcode'] = '/sitemin/helper@qrcode';
$routers['/sitemin/helper/tidy'] = '/sitemin/helper@tidy';
$routers['/sitemin/helper/beautify'] = '/sitemin/helper@beautify';
$routers['/sitemin/helper/timer'] = '/sitemin/helper@timer';
$routers['/sitemin/helper/alarm'] = '/sitemin/helper@alarm';
$routers['/sitemin/helper/stopwatch'] = '/sitemin/helper@stopwatch'; 

$routers['/sitemin/keepalive'] = '/sitemin/index@keepalive';
$routers['/sitemin/mail/queue'] = '/sitemin/mail@queue';
$routers['/sitemin/mail/cron'] = '/sitemin/mail@cron';
$routers['/sitemin/requestpassword'] = '/sitemin/login@requestpassword';
$routers['/sitemin/resetpassword'] = '/sitemin/login@resetpassword';
$routers['/sitemin/login'] = '/sitemin/login@login';
$routers['/sitemin/logout'] = '/sitemin/login@logout';
$routers['/sitemin/dashboard'] = '/sitemin/login@dashboard'; 
$routers['/sitemin'] = '/sitemin/login@dashboard';
$routers['/sitemin/user/profile'] = '/sitemin/user@profile';
$routers['/sitemin/user/message'] = '/sitemin/user@message';
$routers['/sitemin/user'] = '/sitemin/user@list';
$routers['/sitemin/user/password'] = '/sitemin/user@password';
$routers['/sitemin/user/edit'] = '/sitemin/user@edit';
$routers['/sitemin/user/active'] = '/sitemin/user@active';
$routers['/sitemin/user/suspend'] = '/sitemin/user@suspend';
$routers['/sitemin/user/search'] = '/sitemin/user@search';
$routers['/sitemin/acl/menutree'] = '/sitemin/acl@menutree';
$routers['/sitemin/acl/menutree/item_move_1'] = '/sitemin/acl@menutreeitemmove1';
$routers['/sitemin/acl/menutree/item_save'] = '/sitemin/acl@menutreeitemsave';
$routers['/sitemin/acl/menutree/item_delete'] = '/sitemin/acl@menutreeitemdelete';
$routers['/sitemin/acl/menutree/item_move_2'] = '/sitemin/acl@menutreeitemmove2';
$routers['/sitemin/acl/router'] = '/sitemin/acl@router';
$routers['/sitemin/acl/router/change'] = '/sitemin/acl@routerchange';
$routers['/sitemin/acl/router/search'] = '/sitemin/acl@routersearch';
$routers['/sitemin/acl/role'] = '/sitemin/acl@role';
$routers['/sitemin/acl/role/edit'] = '/sitemin/acl@roleedit';
$routers['/sitemin/acl/role/delete'] = '/sitemin/acl@roledelete';
$routers['/sitemin/acl/role/search'] = '/sitemin/acl@rolesearch';
//sitemin config var
$routers['/sitemin/var'] = '/sitemin/var@index';
$routers['/sitemin/constant'] = '/sitemin/var@constant';
$routers['/sitemin/status'] = '/sitemin/index@status';
$routers['/sitemin/cron'] = '/sitemin/cron@index';
//called by system cron or external cron
$routers['/sitemin/cron/call'] = '/sitemin/cron@run';
//called by internal cron (via cron@run and sitemin_crontab table!)
$routers['/sitemin/housekeeping/logarchive'] = '/sitemin/housekeeping@logarchive';
$routers['/sitemin/housekeeping/backup'] = '/sitemin/housekeeping@backup';
/** api gateway/webservice first contact point **/
$routers['/api'] = '/sitemin/api/_api@dispatch';
$routers['/api/login'] = '/sitemin/api/_api@login';
/** list apis **/
$routers['/api/list'] = '/sitemin/api/api@list';
$routers['/api/search'] = '/sitemin/api/api@search';
/** api users **/
$routers['/api/user'] = '/sitemin/api/user@index';
$routers['/api/user/password'] = '/sitemin/api/user@password';
$routers['/api/user/edit'] = '/sitemin/api/user@edit';
$routers['/api/user/idcheck'] = '/sitemin/api/user@idcheck';
$routers['/api/user/search'] = '/sitemin/api/user@search';
$routers['/api/user/status_change'] = '/sitemin/api/user@status_change';
/** api acl **/
$routers['/api/acl'] = '/sitemin/api/acl@index';
$routers['/api/acl/edit'] = '/sitemin/api/acl@edit';
//first time will start install screen
if (!file_exists(__DIR__ . '/.install.done')) include "installer.php";
