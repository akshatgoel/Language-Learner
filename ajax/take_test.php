<?php
session_start();
header('Content-type: text/html;charset=utf-8');
require_once('../lib/library.php');
$lang = $_SESSION['user']['user_language'];
?>
<?php
$questions = test_eligible($_SESSION['user']['id'],$_SESSION['user']['user_language']);
if($questions == -1){ 
test_fail_beacon($_SESSION['user']['id']);?>
	<div class="box" style="width:80%">
		<h3>Oh snap!! </h3>
		<div>
			<p class="pb10"> Looks like you haven't learnt enough words now. Nothing to worry. You can always learn more words and get back here later.</p>
			<p class="pb10"> Happy Learning! </p>
		</div>
	</div>
<?php } else { 
	test_view_beacon($_SESSION['user']['id']);
?>
<form method="post" action="submit_test.php" >
<?php 
	foreach($questions as $question) {
		
		$opts = test_generate($question[0]['id'],$lang,1);
		$opts[] = $question[0]['translation'];
		shuffle($opts);
	?>
	<div class="box" style="width:80%">
		<h3><?php echo $question[0]['word']; ?></h3>
		<div>
			
			<?php foreach($opts as $opt) { ?>
				<input type="radio" name="<?php echo $question[0]['id']; ?>" value="<?php echo $opt; ?>" class="options" title="<?php echo $question[0]['id']; ?>"> <?php echo $opt; ?> <br />
			<?php } ?>
		</div>
	</div>
	<?php	} ?>
	<div class="box" style="width:80%">
		<div><input type="submit" value="Complete Test" style="float:none;"></div>
	</div>
			</form>
	<?php
	} ?>