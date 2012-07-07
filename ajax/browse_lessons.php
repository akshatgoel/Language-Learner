<?php
session_start();
header('Content-type: text/html;charset=utf-8');
require_once('../lib/library.php');
$lesson = $_POST['lessonName'];
$lang = $_SESSION['user']['user_language'];
$lessons = get_lessons($lang);

foreach($lessons as $lesson){ ?>
	
	<div class="box">
		<h3><?php echo $lesson['lesson_name']; ?></h3>
		<div>
			<em class="s13 mt7">Available Words : <?php echo $lesson['words']; ?></em> <br />
			<p class="s11 mt10"><a href="javascript:void(0);" class="lesson_link" title="<?php echo $lesson['lesson_name']; ?>">Learn Lesson</a></p>
		</div>
	</div>
<?php } ?>