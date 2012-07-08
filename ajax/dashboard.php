<?php
session_start();
header('Content-type: text/html;charset=utf-8');
require_once('../lib/library.php');
?>
<div class="box" style="width:80%">
	<h3>Howdy <?php echo $_SESSION['user']['username']; ?>! - Update your profile settings here </h3>
	<div>
		<p class="pb10"> Send Notifications to <?php echo $_SESSION['user']['email']; ?>? 
			<a class="fb_btn" href="javascript:void(0);" id="notify_link" >
				<?php echo show_mail_status($_SESSION['user']['id']); ?>
			</a>
		</p>
		<p class="pb10"> My Reputation Points : <?php echo $_SESSION['user']['rep']; ?> <a href="javascript:void(0);" title="Take tests, Invite more friends to earn more reputation points!">?</a>	</p>
		<p class="pb10" >Current Language : <?php echo ucwords($_SESSION['user']['user_language']); ?><br />
					Default Language : <select id="change_default" style="float:none;"> 
						<?php $langs = get_languages(); 
							foreach($langs as $lang){
								if(strcmp($lang, $_SESSION['user']['default_language']) == 0)
									$selected = 'selected=selected';
								else
									$selected = "";
								echo '<option value="'.$lang.'" '.$selected.'>'.ucwords($lang).'</option>';
							}
						?></p>
		<p class="pb10" ><a class="fb_btn" href="javascript:void(0);" id="sendRequest" style="padding-left: 15px;padding-right: 15px;" >Invite Friends</a>
		<p class="s11"> Last login : <?php echo $_SESSION['user']['last_login']; ?></p>
		
	</div>
</div>
