<?php
session_start();
header('Content-type: text/html;charset=utf-8');
require_once('../lib/library.php');
$lang = $_SESSION['user']['user_language'];
$words = top_words($lang,10);

foreach($words as $word){ ?>
	
	<div class="box">
		<h3> <?php echo $word['word']; ?><?php
		switch(is_learnt($word['id'],$_SESSION['user']['user_language'], $_SESSION['user']['id'])){
			case 1 : echo '<span class="unlearn_btn" id="word'.$word['id'].'">Unlearn';
						break;
			case 0 : 
			default: echo '<span class="learn_btn" id="word'.$word['id'].'">Learn';
						break;
		}
		?></span></h3>
		<div>
			
			<em class="s13 mt7"><?php echo $word['translation']; ?></em>
			<p class="s11 mt10">Total Learns : <?php echo $word['hits']; ?></p>
		</div>
	</div>
<?php } ?>