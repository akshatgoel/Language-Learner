<?php
session_start();
header('Content-type: text/html;charset=utf-8');
require_once('../lib/library.php');
$lang = $_SESSION['user']['user_language'];
$words = browse_words($_SESSION['user']['id'],$lang,0);

foreach($words as $word){ ?>
	
	<div class="box">
		<h3> <?php echo $word['word']; ?></h3>
		<div>
			
			<em class="s13 mt7"><?php echo $word['translation']; ?></em>
		
			<p class="s13 mt7">Learnt on : <?php echo $word['timestamp'];?></p>
				<p class="s11 mt10">
				<a href="javascript:void(0);" title="<?php echo $word['lesson_name']; ?>" class="lesson_link"><?php echo $word['lesson_name']; ?></a>
			</p>
		</div>
	</div>
<?php } ?>