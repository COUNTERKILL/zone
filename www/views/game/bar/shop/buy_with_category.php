<div id="content">
<div class="header">
<img src="/images/barmans/<?=$Player->Location->Barman->GetParam('image')?>" alt="." />
</div>
<div class="title">������</div>
<?php
	if($Goods!='')
	{
		echo '<ul class="items">';
		foreach($Goods as $key=>$value)
		{
			echo '<li><img class="image" src="/images/'.$category.'/'.($value['image']).'" alt="."/>';
			echo '<h4>'.(htmlspecialchars($value['name'])).'</h4>';
			echo '<div class="description">'.(htmlspecialchars($value['description'])).'<br/>';
			echo '<div class="props_title">��������������:</div>';
			echo '<div class="params">';
			if(isset($value['acid']) AND ($value['acid']<>0)) echo '���������: '.($value['acid']).'%<br/>';
			if(isset($value['heat']) AND ($value['heat']<>0)) echo '�����������: '.($value['heat']).'%<br/>';
			if(isset($value['gravitation']) AND ($value['gravitation']<>0)) echo '�����������: '.($value['gravitation']).'%<br/>';
			if(isset($value['psi']) AND ($value['psi']<>0)) echo '���-������: '.($values['psi']).'%<br/>';
			if(isset($value['elec']) AND($value['elec']<>0)) echo '�������������: '.($value['elec']).'%<br/>';
			if(isset($value['radiation']) AND ($value['radiation']<>0)) echo '�����������: '.($value['radiation']).'%<br/>';
			if(isset($value['bulletproof']) AND ($value['bulletproof']<>0)) echo '�������������: '.($value['bulletproof']).'%<br/>';
			if(isset($value['gap']) AND ($value['gap']<>0)) echo '������: '.($value['gap']).'%<br/>';
			if(isset($value['weight_up']) AND ($value['weight_up']<>0)) echo '����������� ���: + '.($value['weight_up']).' ��<br/>';
			if(isset($value['unradiation']) AND ($value['unradiation']<>0)) echo '��������� ���� ��������: - '.($value['unradiation']).' ��<br/>';
			if(isset($value['health']) AND ($value['health']<>0)) echo '���������: + '.($value['health']).' %<br/>';
			if(isset($value['blood']) AND ($value['blood']<>0)) echo '������������: - '.($value['blood']).'<br/>';
			if(isset($value['radiation_res']) AND ($value['radiation_res']<>0)) echo '��������: '.($value['radiation_res']).' ��/�<br/>';
			if(isset($value['art_cells'])) echo '����� ��� ���������: '.($value['art_cells']).'<br/>';
			echo '���: '.($value['weight']).' ��<br/>';
			echo '���������: '.($value['cost']).' ���.';
			echo '</div></div>';
			echo '<div class="links"><a class="button" href="/game/bar/shop/buy/?id='.($value['id']).'&category='.$category.'">������</a></div></li>';
		}
		echo '</ul>';
	}
	else
	{
		echo '<div class="title2">��� �������!</div>';
		
	}
	
?>
<ul class="navigation">
	<li><a href="/game/bar/shop/"><span class="body"><img width="16" height="16" src="/images/korzina.png" />��������</span></a></li>
<div class="content_separator"></div>
