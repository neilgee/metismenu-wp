<div class="wrap">
	<h2>metisMenu Plugin Options</h2>
	<form method="post" action="options.php">
<?php 
	settings_fields('ng_metismenu_settings_group'); //passing in the settings group as defined register settings
	do_settings_sections('metismenu'); //page that it appears on
	submit_button('Update');
?>
</form></div>



 
 