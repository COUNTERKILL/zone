<div id="header">
<img id="logo" src="/images/logoH.png" alt="."></img>

<?php


 if(isset($notification)){
	echo '<div class="info">';
	$this->Notification($notification);
	echo '</div>';
 }
?>

</div>
<div class="content_separator"></div>