<?php
session_start();
require_once('../lib/library.php');
?>
<center>
<h3 class="ul p10" >Select a language</h3>
<?php
	$langs = get_languages();
	while($lang = mysql_fetch_assoc($langs)){
?>
	<a href="actions/update_lang.php?id=<?php echo $lang['name']; ?>"  class="nl fc_fff pb7"><?php echo ucwords($lang['name']); ?></a><br />

<?php
	}
?>
</center>