<div id="content">
<?php
	if($location===0)
	{	
		echo '<div class="title">��� ����� �������!</div>';
	}
	else
	{
		echo '<div class="header">';
		echo '<img src="/images/locations/'.($Player->Location->GetParamById($location['loc_id'],'image')).'" alt="." />';
		echo '</div>';
		echo '<div class="title">� ��� �� ������� ';
		echo $location['loc_name'];
		echo "</div>";
		echo '<div class="text">';
		echo $location['description'];
		echo '<br/></div>';
	}
?>


<div class="content_separator"></div>

