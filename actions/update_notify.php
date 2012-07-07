<?php
	session_start();
	require_once('../AppInfo.php');
	require_once('../lib/library.php');
	echo toggle_mail_notify($_SESSION['user']['id']);
?>