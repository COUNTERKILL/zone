<div id="content">
<div class="header">

</div>
<div class="title">�������</div>
<?php
	if($Events == 0)
	{
		echo '<br/><div class="title2">������� ����!</div><br/>';
	}
	else
	{
		echo '<ul class="items">';
		foreach($Events as $key=>$value)
		{
			echo '<li>';
			echo '<small><div class="title2">������ �� ';
			$day = date('d',$value['time']);
			$month = date('n',$value['time']);
			$year = date('Y',$value['time']);
			echo $day.' ';
			switch($month)
			{
				case '1': echo '������ ';
					break;
				case '2': echo '������� ';
					break;
				case '3': echo '����� ';
					break;
				case '4': echo '������ ';
					break;
				case '5': echo '��� ';
					break;
				case '6': echo '���� ';
					break;
				case '7': echo '���� ';
					break;
				case '8': echo '������� ';
					break;
				case '9': echo '�������� ';
					break;
				case '10': echo '������� ';
					break;
				case '11': echo '������ ';
					break;
				case '12':echo '������� ';
					break;
				
			}
			echo $year;
			echo date(' (G:i:s)',$value['time']);
			echo '</div></small>';
			if($value['title']!='')
			{
				echo "<h4>";
				if($value['type']==0)
				{
					echo '<span class="yellow">'.($value['title']).'</span>';
				}
				elseif($value['type']==1)
				{
					echo '<span class="green">'.($value['title']).'</span>';
				}
				else
				{
					echo '<span class="black">'.($value['title']).'</span>';
				}
				echo "</h4>";
			}
			echo '<div class="description">';
			echo $value['text'];
			echo '</div>';
			echo '</li>';
		}
		echo '</ul>';
	}
	
?>
<div class="pagination">
<?php
if($LastPage!=0)
{
	if($Page==1)
	{
		echo '<span class="disabled">&nbsp;<&nbsp;</span> ';
	}
	else
	{
		echo '<a class="prev_page" href="/game/notepad/?p=1">&nbsp;<&nbsp;</a> ';
	}
	$displayNextPages = (($LastPage-$Page)>4)?4:$LastPage-$Page;
	$displayPrevPages = (($Page)>4)?4:$Page-1;
	for($i = 1; $i<=$displayPrevPages; $i++)
	{
		echo '<a class="page" href="/game/notepad/?p='.($Page-$displayPrevPages+$i-1).'">&nbsp;'.($Page-$displayPrevPages+$i-1).'&nbsp;</a> ';
	}
	
	echo '<span class="disabled">&nbsp;'.$Page.'&nbsp;</span> ';

	for($i = 1; $i<=$displayNextPages;$i++)
	{
		echo '<a class="page" href="/game/notepad/?p='.($Page+$i).'">&nbsp;'.($Page+$i).'&nbsp;</a> ';
	}
	if($Page==$LastPage)
	{
		echo '<span class="disabled">&nbsp;>&nbsp;</span> ';
	}
	else
	{
		echo '<a class="page" href="/game/notepad/?p='.$LastPage.'">&nbsp;>&nbsp;</a>';
	}
}
?>

</div>


            
<div class="content_separator"></div>