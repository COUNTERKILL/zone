<div id="content">


<?php
if(isset($locations))
{
	echo '<div class="header">';
	echo '<img src="/images/zone.png" alt="." />';
	echo '<div class="locdescription">Зона — место, заселенное аномалиями и мутантами. В Зону не побоятся лезть лишь опытные сталкеры, либо сумасшедшие фанатики. Хорошо подумай, стоит ли туда идти?</div>
	</div>
	<br/>';
	echo '<span class="listtitle">Ближайшие локации:</span>';
	echo '<ul class="list">';
	foreach($locations as $key=>$value)
	{
		echo '<li><a href="/game/zone/?id='.($value['loc_id']).'">'.($value['loc_name']).' ('.($value['map_name']).')</a></li>'."\n";
	};
	echo '</ul>';
}
if(isset($location))
{
	if($location===0)
	{	
		echo '<div class="title">Нет такой локации!</div>';
	}
	else
	{
		echo '<div class="header">';
		echo '<img src="/images/locations/'.($Player->Location->GetParamById($location['loc_id'],'image')).'" alt="." />';
		echo '</div>';
		echo '<div class="title">';
		echo $location['loc_name'];
		echo "</div>";
		echo '<div class="text">';
		echo $location['description'];
		echo '<br/><a href="/game/zone/location/?id='.($location['loc_id']).'" class="button"/>В Зону</a></div>';
	}
	
}
?>
<div class="content_separator"></div>

