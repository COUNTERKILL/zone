<?php
// ������
Class Model_Equipment{
	public function GetEquipment($Player)
	{
		global $SQLobj;
		$qInventory = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `dressed`=1 AND `inventory_type`="equipment"');
		if(mysql_num_rows($qInventory)==0) return '';
		$inventory=mysql_fetch_assoc($qInventory);
		$qItem = $SQLobj->Query('SELECT * FROM `items_'.($inventory['inventory_type']).'` WHERE `id`='.($inventory['inventory_id']));
		$item = mysql_fetch_assoc($qItem);
		$item['type'] = $inventory['inventory_type'];
		$item['inventory_id'] = $inventory['id'];
		return $item;
	}
	public function GetWeapon($Player)
	{
		global $SQLobj;
		$qInventory = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `dressed`=1 AND `inventory_type`="weapons"');
		if(mysql_num_rows($qInventory)==0) return '';
		$inventory=mysql_fetch_assoc($qInventory);
		$qItem = $SQLobj->Query('SELECT * FROM `items_'.($inventory['inventory_type']).'` WHERE `id`='.($inventory['inventory_id']));
		$item = mysql_fetch_assoc($qItem);
		$item['type'] = $inventory['inventory_type'];
		$item['inventory_id'] = $inventory['id'];
		return $item;
	}
	public function GetArtefacts($Player)
	{
		global $SQLobj;
		$qInventory = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `dressed`=1 AND `inventory_type`="artefacts"');
		if(mysql_num_rows($qInventory)==0) return '';
		$i = 0;
		while ($inventory=mysql_fetch_assoc($qInventory))
		{
			$qItem = $SQLobj->Query('SELECT * FROM `items_'.($inventory['inventory_type']).'` WHERE `id`='.($inventory['inventory_id']));
			$item = mysql_fetch_assoc($qItem);
			$item['type'] = $inventory['inventory_type'];
			$item['inventory_id'] = $inventory['id'];
			$items[$i] = $item;
			$i++;
		}
		return $items;
	}
	function USet($Player, $id)
	{
		global $SQLobj;
		$qInventory = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `dressed`=1 AND `id`='.$id);
		if(mysql_num_rows($qInventory)==0) return '��� ����� ��������. ���� ������� ����...';
		$inventory=mysql_fetch_assoc($qInventory);
		switch($inventory['inventory_type'])
		{
			//����������
			case 'equipment':
				// ��������� ��������� �� ���������
				$qArt_dressed = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `inventory_type` = "artefacts" AND `dressed`=1 AND `user_id` = '.($Player->id));
				$art_dressed = mysql_num_rows($qArt_dressed);
				if($art_dressed!=0) return '������� ��� ����� ����� ���������';
				// �������� ����
				$qItem = $SQLobj->Query('SELECT * FROM `items_equipment` WHERE `id`='.($inventory['inventory_id']));
				if(!$qItem) return '��� ����� ��������. ���� ������� ����...';
				$item = mysql_fetch_assoc($qItem);
				// ���������, ��������� �� � ������
				$weight = $Player->GetWeight();
				if(($weight+$item['weight'])>$Player->GetParam2('weight')) return '���. ��� � ������ �� ������. ���� ���-�� ��������.';
				// ��������� ��������� �����
				$upd = $SQLobj->Query('UPDATE `users_params` SET `acid`=`acid`-'.($item['acid']).', `gap`=`gap`-'.($item['gap']).', `heat`=`heat`-'.($item['heat']).', `gravitation`=`gravitation`-'.($item['gravitation']).', `psi`=`psi`-'.($item['psi']).', `bulletproof`=`bulletproof`-'.($item['bulletproof']).', `elec`=`elec`-'.($item['elec']).', `radiation`=`radiation`-'.($item['radiation']).' WHERE `user_id`='.($Player->id));
				if(!$upd) return '��� ����� ��������. ���� ������� ����...';
				$dressed = $SQLobj->Query('UPDATE `users_inventory` SET `dressed`=0 WHERE `user_id`='.($Player->id).' AND `id`='.$id);
				if(!$dressed) return '��� ����� ��������. ���� ������� ����...';
				return '� ���� '.($item['name']);
				break;
			case 'artefacts':
				$qItem = $SQLobj->Query('SELECT * FROM `items_artefacts` WHERE `id`='.($inventory['inventory_id']));
				if(!$qItem) return '��� ����� ��������. ���� ������� ����...';
				$item = mysql_fetch_assoc($qItem);
				// ���������, ��������� �� � ������
				$weight = $Player->GetWeight();
				if(($weight+$item['weight'])>$Player->GetParam2('weight')) return '���. ��� � ������ �� ������. ���� ���-�� ��������.';
				$upd = $SQLobj->Query('UPDATE `users_params` SET `acid`=`acid`-'.($item['acid']).', `gap`=`gap`-'.($item['gap']).', `heat`=`heat`-'.($item['heat']).', `gravitation`=`gravitation`-'.($item['gravitation']).', `psi`=`psi`-'.($item['psi']).', `bulletproof`=`bulletproof`-'.($item['bulletproof']).', `elec`=`elec`-'.($item['elec']).', `radiation_res`=`radiation_res`-'.($item['radiation_res']).', `woundhealing` = `woundhealing`-'.($item['woundhealing']).' WHERE `user_id`='.($Player->id));
				if(!$upd) return '��� ����� ��������. ���� ������� ����...';
				$dressed = $SQLobj->Query('UPDATE `users_inventory` SET `dressed`=0 WHERE `user_id`='.($Player->id).' AND `id`='.$id);
				if(!$dressed) return '��� ����� ��������. ���� ������� ����...';
				return '� ���� '.($item['name']);
				break;
			default: return '��� ����� ��������. ���� ������� ����...';
			break;
		}
	}
	
}
?>