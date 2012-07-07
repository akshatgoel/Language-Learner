<?php
session_start();
header('Content-type: text/html;charset=utf-8');
require_once('../lib/library.php');
$lesson = $_POST['lessonName'];
$lang = $_SESSION['user']['user_language'];
$words = get_words($lang,$lesson);

foreach($words as $word){ ?>
	
	<div class="box">
		<h3> <?php echo $word['word']; ?></h3>
		<div>
			
			<em class="s13 mt7"><?php echo $word['translation']; ?></em>
			<p class="s11 mt10">Total Learns : <?php echo $word['hits']; ?><?php
		switch(is_learnt($word['id'],$_SESSION['user']['user_language'], $_SESSION['user']['id'])){
			case 1 : echo '<span class="unlearn_btn" id="word'.$word['id'].'">Unlearn';
						break;
			case 0 : 
			default: echo '<span class="learn_btn" id="word'.$word['id'].'">Learn';
						break;
		}
		?></span></p>
		</div>
	</div>
<?php } ?>