<?php
	session_start();
	require_once('../AppInfo.php');
	require_once('../lib/library.php');
	$status = get_mail_status($_SESSION['user']['id']);
	if(toggle_mail_notify($_SESSION['user']['id']) === 1){
		if($status==0){
		  echo "No";
		}
		else if($status==1){
		  echo "Yes";
		}
	}
?>