<div id="content">
<div class="header">
<img src="/images/barmans/<?=$Player->Location->Barman->GetParam('image')?>" alt="." />
</div>
<div class="title">Купить</div>
<ul class="navigation">
<?php
	if($Categories=='')
	{
		echo '<div class="title2">Нет товаров!</div>';
	}
	else
	{
		foreach($Categories as $key=>$value)
		{
			echo '<li><a href="/game/bar/shop/buy/?category='.$value.'"><span class="body"><img width="16" height="16" src="/images/'.$value.'.png" />';
			switch($value)
			{
				case 'weapons': echo 'Оружие'; break;
				case 'alcohol': echo 'Выпивка'; break;
				//case 'eat': echo 'Еда'; break;
				case 'medkits': echo 'Медикаменты'; break;
				case 'equipment': echo 'Экипировка'; break;
				case 'backpacks': echo 'Рюкзаки'; break;
				default: echo 'Другое'; break;
			}
			echo '</span></a></li>';
		}
	}

?>
<li><a href="/game/bar/shop/"><span class="body"><img width="16" height="16" src="/images/korzina.png" />Торговля</span></a></li>
</ul>
<div class="content_separator"></div>

