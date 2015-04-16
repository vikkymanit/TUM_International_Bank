<?php

function isSessionActive() {
	session_start();
	$lastActivity=$_SESSION['lastActivity'];
	if ($lastActivity!=null && ($lastActivity+(10*60) > time())) {
		$_SESSION['lastActivity']=time();
		return true;	
	} else {
		deleteSession();
		return false;
	}
}

function deleteSession() {
	session_start();
	setcookie("PHPSESSID", "", time() - 3600, '/');
	session_unset();
	session_destroy();
}

function enforceRBAC($role) {
	session_start();
	if($_SESSION['role']!=$role) {
		deleteSession();
		return false;
	} else {
		return true;
	}
}

function enforceRBACmulti($roles) {
	session_start();
	foreach ($roles as $role) {
		if($_SESSION['role']==$role) {
			return true;
		} 
	}
	deleteSession();
	return false;
}
?>