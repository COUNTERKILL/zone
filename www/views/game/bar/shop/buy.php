<div id="content">
<div class="header">
<img src="/images/barmans/<?=$Player->Location->Barman->GetParam('image')?>" alt="." />
</div>
<div class="title">������</div>
<ul class="navigation">
<?php
	if($Categories=='')
	{
		echo '<div class="title2">��� �������!</div>';
	}
	else
	{
		foreach($Categories as $key=>$value)
		{
			echo '<li><a href="/game/bar/shop/buy/?category='.$value.'"><span class="body"><img width="16" height="16" src="/images/'.$value.'.png" />';
			switch($value)
			{
				case 'weapons': echo '������'; break;
				case 'alcohol': echo '�������'; break;
				//case 'eat': echo '���'; break;
				case 'medkits': echo '�����������'; break;
				case 'equipment': echo '����������'; break;
				case 'backpacks': echo '�������'; break;
				default: echo '������'; break;
			}
			echo '</span></a></li>';
		}
	}

?>
<li><a href="/game/bar/shop/"><span class="body"><img width="16" height="16" src="/images/korzina.png" />��������</span></a></li>
</ul>
<div class="content_separator"></div>

