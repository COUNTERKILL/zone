<div id="content">
<div class="header">
<img src="/images/barmans/<?=$Player->Location->Barman->GetParam('image')?>" alt="." />
</div>
<div class="title">Купить</div>
<?php
	if($Goods!='')
	{
		echo '<ul class="items">';
		foreach($Goods as $key=>$value)
		{
			echo '<li><img class="image" src="/images/'.$category.'/'.($value['image']).'" alt="."/>';
			echo '<h4>'.(htmlspecialchars($value['name'])).'</h4>';
			echo '<div class="description">'.(htmlspecialchars($value['description'])).'<br/>';
			echo '<div class="props_title">Характеристики:</div>';
			echo '<div class="params">';
			if(isset($value['acid']) AND ($value['acid']<>0)) echo 'Химзащита: '.($value['acid']).'%<br/>';
			if(isset($value['heat']) AND ($value['heat']<>0)) echo 'Теплозащита: '.($value['heat']).'%<br/>';
			if(isset($value['gravitation']) AND ($value['gravitation']<>0)) echo 'Гравизащита: '.($value['gravitation']).'%<br/>';
			if(isset($value['psi']) AND ($value['psi']<>0)) echo 'Пси-защита: '.($values['psi']).'%<br/>';
			if(isset($value['elec']) AND($value['elec']<>0)) echo 'Электрозащита: '.($value['elec']).'%<br/>';
			if(isset($value['radiation']) AND ($value['radiation']<>0)) echo 'Радиозащита: '.($value['radiation']).'%<br/>';
			if(isset($value['bulletproof']) AND ($value['bulletproof']<>0)) echo 'Пулестойкость: '.($value['bulletproof']).'%<br/>';
			if(isset($value['gap']) AND ($value['gap']<>0)) echo 'Разрыв: '.($value['gap']).'%<br/>';
			if(isset($value['weight_up']) AND ($value['weight_up']<>0)) echo 'Переносимый вес: + '.($value['weight_up']).' кг<br/>';
			if(isset($value['unradiation']) AND ($value['unradiation']<>0)) echo 'Выводимая доза радиации: - '.($value['unradiation']).' мР<br/>';
			if(isset($value['health']) AND ($value['health']<>0)) echo 'Излечение: + '.($value['health']).' %<br/>';
			if(isset($value['blood']) AND ($value['blood']<>0)) echo 'Кровотечение: - '.($value['blood']).'<br/>';
			if(isset($value['radiation_res']) AND ($value['radiation_res']<>0)) echo 'Радиация: '.($value['radiation_res']).' мР/ч<br/>';
			if(isset($value['art_cells'])) echo 'Ячеек под артефакты: '.($value['art_cells']).'<br/>';
			echo 'Вес: '.($value['weight']).' кг<br/>';
			echo 'Стоимость: '.($value['cost']).' руб.';
			echo '</div></div>';
			echo '<div class="links"><a class="button" href="/game/bar/shop/buy/?id='.($value['id']).'&category='.$category.'">Купить</a></div></li>';
		}
		echo '</ul>';
	}
	else
	{
		echo '<div class="title2">Нет товаров!</div>';
		
	}
	
?>
<ul class="navigation">
	<li><a href="/game/bar/shop/"><span class="body"><img width="16" height="16" src="/images/korzina.png" />Торговля</span></a></li>
<div class="content_separator"></div>
