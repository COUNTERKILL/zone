<div id="content">
<div class="header">

</div>
<div class="title">�������</div>
<?php
	if(($Equipment=='') and ($Weapon==''))
	{
		echo '<div class="title2">��... ����� ��� � ����� �������� �� �������...</div><br/>';
	}
	else
	{
	
		echo '<ul class="items">';
		$buf[0] = $Weapon;
		$buf[1] = $Equipment;
		$i = 2;
		if($Artefacts!='')
		{
			foreach($Artefacts as $key=>$value)
			{
				$buf[$i] = $value;
				$i++;
			}
		}
		foreach($buf as $key=>$value)
		{
			if($value!='')
			{
				if($value['type'] == 'weapons')
				{
					echo '<li class="header">������</li>';
				}
				elseif($value['type'] == 'equipment')
				{
					echo '<li class="header">����������</li>';
				}
				else
				{
					echo '<li class="header">��������</li>';
				}
				echo '<li><img class="image" src="/images/'.($value['type']).'/'.($value['image']).'" alt="."/>';
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
				echo '</div></div>';
				echo '<div class="links"><a class="button button12" href="/game/equipment/uset?id='.($value['inventory_id']).'">�����</a></div></li>';
			}
		}
		echo '</ul>';
	}
?>
<div class="content_separator"></div>
