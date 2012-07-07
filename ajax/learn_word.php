<?php
session_start();
require_once('../lib/library.php');
$word_id = $_POST['word_id'];
$user_id = $_SESSION['user']['id'];
$lang = $_SESSION['user']['default_language'];

learn_word($word_id, $lang, $user_id);
learnt_word_beacon($word_id, $lang, $user_id);
?>
Learnt