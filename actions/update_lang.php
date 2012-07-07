<?php
	session_start();
	require_once('../AppInfo.php');
	require_once('../lib/library.php');
	$_SESSION['user']['user_language'] = $_GET['id'];
	$user_id = $_SESSION['user']['id'];
	update_language_beacon($user_id, $_GET['id']);
	header('Location: '.AppInfo::getUrl());
?>