<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

/**
 * Admin logs library
 *
 * PHP versions 4 > 5
 *
 * @category	library
 * @author	Luc Thibault <luc@suhali.net>
 *
 */


// logCheckFiles
/**
 * Create log directory and files if needed
 *
 * @param	String	$file		the log file name
 * @return	Void
 */
function logCheckFileExists ($file) {
	if (!is_dir($_SERVER['DOCUMENT_ROOT'].'/log')) {
		mkdir($_SERVER['DOCUMENT_ROOT'].'/log');
		chmod($_SERVER['DOCUMENT_ROOT'].'/log', 0777);
	}
	if (!file_exists($file)) {
		touch($file);
		chmod($file, 0777);
	}
}


// logAdminActivity
/**
 * Log data access activity from an administrator 
 *
 * @param	String	$type		the type of action executed
 * @param	String	$action		the executed action(s);
 * @return	Void
 */
function logAdminActivity ($type, $action) {
	logCheckFileExists($_SERVER['DOCUMENT_ROOT'].'/log/admin_event.log');
	$f = fopen($_SERVER['DOCUMENT_ROOT'].'/log/admin_event.log', 'a+');
	if (isset($_SESSION['user']))
		$ident = "User {$_SESSION['user']} (ID:{$_SESSION['userid']})";
	else	$ident = "Application";
	if (is_array($action)) {
		foreach ($action as $done)
			fwrite($f, "\n[".date('j/m/Y H:i:s')."] - {$ident} executed successfully the following {$type} action : {$done}");
	} else	fwrite($f, "\n[".date('j/m/Y H:i:s')."] - {$ident} executed successfully the following {$type} action : {$action}");
	fclose($f);
}


// logAdminActivity
/**
 * Log errors while using the administration tool
 *
 * @param	String	$type		the type of action executed
 * @param	String	$action		the executed action(s);
 * @return	Void
 */
function logAdminError ($type, $action) {
	logCheckFileExists($_SERVER['DOCUMENT_ROOT'].'/log/admin_error.log');
	$f = fopen($_SERVER['DOCUMENT_ROOT'].'/log/admin_error.log', 'a+');
	if (isset($_SESSION['user']))
		$ident = "User {$_SESSION['user']} (ID:{$_SESSION['userid']})";
	else	$ident = "Application";
	if (is_array($action)) {
		foreach ($action as $done)
			fwrite($f, "\n[".date('j/m/Y H:i:s')."] - {$ident} recieved an error on the following {$type} action : {$done}");
	} else	fwrite($f, "\n[".date('j/m/Y H:i:s')."] - {$ident} recieved an error on the following {$type} action : {$action}");
	fclose($f);
}


?>
