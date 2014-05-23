<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if(!isset($_SESSION)){
	session_name('UntuSoft');
	session_start();
	session_cache_limiter('private');
}
ob_start();
require_once (dirname(__FILE__) . '/classes/MatchaHelper.php');
include_once(dirname(__FILE__) . '/dataProvider/i18nRouter.php');
include_once(dirname(__FILE__) . '/dataProvider/Globals.php');
header('Content-Type: text/javascript');

$_SESSION['version'] = '0.6.185';
$_SESSION['extjs'] = 'extjs-4.1.1a';

// check if is emergency access....
/*
if(isset($_SESSION['user']) && isset($_SESSION['user']['emergencyAccess']) && $_SESSION['user']['emergencyAccess']){
	$isEmerAccess = 1;
}else{
	$isEmerAccess = 0;
}
print 'isEmerAccess = '.$isEmerAccess.';';
*/

// Output the translation selected by the user.
$i18n = i18nRouter::getTranslation();
print 'lang = '. json_encode( $i18n ).';';

// Output all the globals settings on the database.
$global = Globals::setGlobals();
$global['root'] = dirname(__FILE__);
$global['url']  = $_SESSION['url'];
$global['site']  = $_SESSION['site']['dir'];

print 'globals = '. json_encode( $global ).';';

if(!isset($_SESSION['site']['error']) && (isset($_SESSION['user']) && $_SESSION['user']['auth'] == true))
{
	include_once(dirname(__FILE__) . '/dataProvider/ACL.php');
	include_once(dirname(__FILE__) . '/dataProvider/User.php');

	$acl = new ACL();
	$perms = array();
	/*
	 * Look for user permissions and pass it to a PHP variable.
	 * This variable will be used in JavaScript code
	 * look at it as a PHP to JavaScript variable conversion.
	 */
	foreach($acl->getAllUserPermsAccess() AS $perm){
		$perms[$perm['perm']] = $perm['value'];
	}
	unset($acl);
	$user = new User();
	$userData = $user->getCurrentUserBasicData();
	$userData['token'] = $_SESSION['user']['token'];
//	$userData['facility'] = $_SESSION['user']['facility'];
	$userData['localization'] = $_SESSION['user']['localization'];
	unset($user);
//	Globals::setGlobals();
	/*
	 * Pass all the PHP to JavaScript
	 */
	print 'acl = '. json_encode($perms).';';
	print 'user = '. json_encode($userData).';';
	print 'settings.site_url = "'. $_SESSION['site']['url'] .'";';
}

