<?php
// ������
Class Model_backpack{
	public function GetInventory($Player)
	{
		global $SQLobj;
		$qInventory = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `dressed`=0');
		if(mysql_num_rows($qInventory)==0) return '';
		$i = 0;
		while($inventory=mysql_fetch_assoc($qInventory))
		{
			$qItem = $SQLobj->Query('SELECT * FROM `items_'.($inventory['inventory_type']).'` WHERE `id`='.($inventory['inventory_id']));
			$item = mysql_fetch_assoc($qItem);
			$item['type'] = $inventory['inventory_type'];
			$item['inventory_id'] = $inventory['id'];
			$res[$i]=$item;
			$i++;
		}
		return $res;
	}
	function GetBackpackImage($Player)
	{
		global $SQLobj;
		$qInventory = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `inventory_type`="backpacks" AND `dressed`=1');
		if(mysql_num_rows($qInventory)==0) return 'backpack1.gif';
		$inventory = mysql_fetch_assoc($qInventory);
		$id = $inventory['inventory_id'];
		$qBackpack = $SQLobj->Query('SELECT * FROM `items_backpacks` WHERE `id`='.$id);
		if(mysql_num_rows($qBackpack)==0) return 'backpack1.gif';
		$backpack = mysql_fetch_assoc($qBackpack);
		return $backpack['image'];
	}
	function Set($Player, $id)
	{
		global $SQLobj;
		$qInventory = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `dressed`=0 AND `id`='.$id);
		if(mysql_num_rows($qInventory)==0) return '��� ����� ��������. ���� ������� ����...';
		$inventory=mysql_fetch_assoc($qInventory);
		switch($inventory['inventory_type'])
		{
			//����������
			case 'equipment':
				// ��������, ��� ����� ��� ���������� ��� �� ����
				$qInventoryOdet = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `dressed`=1 AND `inventory_type`="equipment"');
				if(mysql_num_rows($qInventoryOdet)!=0) return '����� ��������� ��� �� ���.';
				//�����
				$dressed = $SQLobj->Query('UPDATE `users_inventory` SET `dressed`=1 WHERE `user_id`='.($Player->id).' AND `id`='.$id);
				if(!$dressed) return '��� ����� ��������. ���� ������� ����...';
				// �������� ����
				$qItem = $SQLobj->Query('SELECT * FROM `items_equipment` WHERE `id`='.($inventory['inventory_id']));
				if(!$qItem) return '��� ����� ��������. ���� ������� ����...';
				$item = mysql_fetch_assoc($qItem);
				// ��������� ��������� �����
				$upd = $SQLobj->Query('UPDATE `users_params` SET `acid`=`acid`+'.($item['acid']).', `gap`=`gap`+'.($item['gap']).', `heat`=`heat`+'.($item['heat']).', `gravitation`=`gravitation`+'.($item['gravitation']).', `psi`=`psi`+'.($item['psi']).', `bulletproof`=`bulletproof`+'.($item['bulletproof']).', `elec`=`elec`+'.($item['elec']).', `radiation`=`radiation`+'.($item['radiation']).' WHERE `user_id`='.($Player->id));
				if(!$upd) return '��� ����� ��������. ���� ������� ����...';
				return '� ���� '.($item['name']);
				break;
			// �������
			case 'backpacks':
				$qDressed = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `inventory_type`="backpacks" AND `dressed`=1');
				// ���� ��� ����
				if(mysql_num_rows($qDressed)!=0)
				{
					$dressed = mysql_fetch_assoc($qDressed);
					// ��������� ����������
					$qItem = $SQLobj->Query('SELECT * FROM `items_backpacks` WHERE `id`='.($inventory['inventory_id']));
					if(!$qItem) return '��� ����� ��������. ���� ������� ����...';
					// ��������� ������� �����
					$qItemDressed = $SQLobj->Query('SELECT * FROM `items_backpacks` WHERE `id`='.($dressed['inventory_id']));
					if(!$qItemDressed) return '��� ����� ��������. ���� ������� ����...';
					$item = mysql_fetch_assoc($qItem);
					$itemDressed = mysql_fetch_assoc($qItemDressed);
					// ���� ����� ������ �� ������� ��� ����
					if($item['weight_up']<($Player->GetWeight()-$item['weight']+$itemDressed['weight'])) return '���. � ���� ������ ��� �� ����������.';
					// ����� ������
					$undressed = $SQLobj->Query('UPDATE `users_inventory` SET `dressed`=0 WHERE `user_id`='.($Player->id).' AND `inventory_type`="backpacks"');
				}
				if(!$undressed) return '��� ����� ��������. ���� ������� ����...';
				//����� ������
				$qDressed = $SQLobj->Query('UPDATE `users_inventory` SET `dressed`=1 WHERE `user_id`='.($Player->id).' AND `id`='.$id);
				if(!$qDressed) return '��� ����� ��������. ���� ������� ����...';
				$upd = $SQLobj->Query('UPDATE `users_params` SET `weight`=`weight`+'.($item['weight_up']).'-'.($itemDressed['weight_up']).' WHERE `user_id`='.($Player->id));
				if(!$upd) return '��� ����� ��������. ���� ������� ����...';
				return '� ���� '.($item['name']);
				break;
			// �����������
			case 'medkits':
				$qItem = $SQLobj->Query('SELECT * FROM `items_medkits` WHERE `id`='.($inventory['inventory_id']));
				if(!$qItem) return '��� ����� ��������. ���� ������� ����...';
				$item = mysql_fetch_assoc($qItem);
				$qDel = $SQLobj->Query('DELETE FROM `users_inventory` WHERE `id`='.$id);
				if(!$qDel) return '��� ����� ��������. ���� ������� ����...';
				$qUpd = $SQLobj->Query('UPDATE `users` SET `health`=IF((`health`+'.($item['health']).')>100,100,(`health`+'.($item['health']).')), `radiation`=IF((`radiation`-'.($item['unradiation']).')<0,0,(`radiation`-'.($item['unradiation']).')), `blood`=IF((`blood`-'.($item['blood']).')<0,0,(`blood`+-'.($item['blood']).')) WHERE `id`='.($Player->id));
				if(!$qUpd) return '��� ����� ��������. ���� ������� ����...';
				return '� ����������� '.($item['name']).'.';
				break;
			case 'artefacts':
				$qEquipment = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `inventory_type` = "equipment" AND `dressed`=1 AND `user_id` = '.($Player->id));
				if(mysql_num_rows($qEquipment)==0) return '������� ��� ����� ����� ���������� � �����������';
				$equipment = mysql_fetch_assoc($qEquipment);
				$item = new TItem('equipment', $equipment['inventory_id']);
				$cells = $item->GetParam('art_cells');
				$qItem = $SQLobj->Query('SELECT * FROM `items_artefacts` WHERE `id`='.($inventory['inventory_id']));
				$item = mysql_fetch_assoc($qItem);
				$qArt_dressed = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `inventory_type` = "artefacts" AND `dressed`=1 AND `user_id` = '.($Player->id));
				$art_dressed = mysql_num_rows($qArt_dressed);
				if($cells<=$art_dressed) return '� ���� ���������� ��� ��������� ����� ��� ����������.';
				$q = $SQLobj->Query('UPDATE `users_inventory` SET `dressed` = 1 WHERE `id` = '.$id.' AND `user_id` = '.($Player->id));
				// �������� ���������
				$upd = $SQLobj->Query('UPDATE `users_params` SET `acid`=`acid`+'.($item['acid']).', `gap`=`gap`+'.($item['gap']).', `heat`=`heat`+'.($item['heat']).', `gravitation`=`gravitation`+'.($item['gravitation']).', `psi`=`psi`+'.($item['psi']).', `bulletproof`=`bulletproof`+'.($item['bulletproof']).', `elec`=`elec`+'.($item['elec']).', `radiation_res`=`radiation_res`+'.($item['radiation_res']).', `woundhealing` = `woundhealing`+'.($item['woundhealing']).' WHERE `user_id`='.($Player->id));
				if(!$upd) return '��� ����� ��������. ���� ������� ����...';
				return '� ������� �������� '.($item['name']);
				break;
			default: return '��� ����� ��������. ���� ������� ����...';
			break;
		}
	}
	
}
?>