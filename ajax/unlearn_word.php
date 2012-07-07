<?php
session_start();
require_once('../lib/library.php');
$word_id = $_GET['id'];
$user_id = $_SESSION['user']['id'];
$lang = $_SESSION['user']['user_language'];

unlearn_word($word_id, $lang, $user_id);
//unlearnt_word_beacon($word_id, $lang, $user_id);
?>