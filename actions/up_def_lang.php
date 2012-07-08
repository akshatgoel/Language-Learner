<?php
	session_start();
	require_once('../AppInfo.php');
	require_once('../lib/library.php');
	$_SESSION['user']['default_language'] = $_POST['lang'];
	$user_id = $_SESSION['user']['id'];
	echo change_default_lang($user_id,strtolower($_POST['lang']));
	update_def_language_beacon($user_id, strtolower($_POST['lang']));
?>