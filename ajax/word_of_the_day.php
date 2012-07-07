<?php
session_start();
header('Content-type: text/html;charset=utf-8');
require_once('../lib/library.php');
?>

<input type="text" id="date_selector" value="<?php echo date('Y-m-d'); ?>" style="float:left;">
<input type="submit" value="Get word" style="width: 100px;float:left;cursor:pointer;margin-left:14px;padding-top:7px;padding-bottom:9px;" onclick="javascript:getword();">
<br />
<br />
<?php 
	$today = word_of_day_lookup($_SESSION['user']['user_language'], 0); ?>
	<div class="box" style="width:80%;" id="my_word">
	<h3> <?php echo date('j F Y',strtotime($today['used'])); ?></h3>
	<div>
		<h4><?php echo $today['word']; ?></h4>
		<em class="s13 mt7"><?php echo $today['translation']; ?></em>
		<p class="s11 mt10"><a href="javascript:void(0);" title="<?php echo $today['lesson_name']; ?>" class="lesson_link"><?php echo $today['lesson_name']; ?></a><?php
	switch(is_learnt($today['id'],$_SESSION['user']['user_language'], $_SESSION['user']['id'])){
		case 1 : echo '<span class="unlearn_btn" id="word'.$today['id'].'">Unlearn';
					break;
		case 0 : 
		default: echo '<span class="learn_btn" id="word'.$today['id'].'">Learn';
					break;
	}
	?>
	</span></p>
	</div>
	</div>