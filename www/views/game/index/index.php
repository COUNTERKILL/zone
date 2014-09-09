<div id="content">
<div class="header">
<?php
if($Player->InZone())
{
	echo '<img src="/images/zone.png" alt="." />';
}
else
{
	echo '<img src="/images/locations/'.($Player->Location->GetParam('image')).'" alt="." />';
}
?>
<br/>
<div class="title"> 
<?php
if($Player->InZone()) echo 'я сейчас в «оне'; else echo  $Player->Location->GetParam('loc_name');
?>
</div>
</div>
<ul class="navigation">
<li><a href="/game/zone"><span class="body"><img width="16" height="16" src="/images/x-ray.png" />«она</span></a></li>
<?php
if(!$Player->InZone())
	if($Player->Location->BarmanIsset()) echo '<li><a href="game/bar"><span class="body"><img width="16" height="16" src="/images/bar.png" />Ѕар</span></a></li>';
?>
</ul>
<div class="content_separator"></div>

