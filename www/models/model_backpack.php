<?php
// модель
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
		if(mysql_num_rows($qInventory)==0) return 'Уже глюки начались. Надо бросать пить...';
		$inventory=mysql_fetch_assoc($qInventory);
		switch($inventory['inventory_type'])
		{
			//экипировка
			case 'equipment':
				// проверка, что такой тип экипировки еще не одет
				$qInventoryOdet = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `dressed`=1 AND `inventory_type`="equipment"');
				if(mysql_num_rows($qInventoryOdet)!=0) return 'Такая штуковина уже на мне.';
				//одеть
				$dressed = $SQLobj->Query('UPDATE `users_inventory` SET `dressed`=1 WHERE `user_id`='.($Player->id).' AND `id`='.$id);
				if(!$dressed) return 'Уже глюки начались. Надо бросать пить...';
				// выбираем итем
				$qItem = $SQLobj->Query('SELECT * FROM `items_equipment` WHERE `id`='.($inventory['inventory_id']));
				if(!$qItem) return 'Уже глюки начались. Надо бросать пить...';
				$item = mysql_fetch_assoc($qItem);
				// обновляем параметры ирока
				$upd = $SQLobj->Query('UPDATE `users_params` SET `acid`=`acid`+'.($item['acid']).', `gap`=`gap`+'.($item['gap']).', `heat`=`heat`+'.($item['heat']).', `gravitation`=`gravitation`+'.($item['gravitation']).', `psi`=`psi`+'.($item['psi']).', `bulletproof`=`bulletproof`+'.($item['bulletproof']).', `elec`=`elec`+'.($item['elec']).', `radiation`=`radiation`+'.($item['radiation']).' WHERE `user_id`='.($Player->id));
				if(!$upd) return 'Уже глюки начались. Надо бросать пить...';
				return 'Я одел '.($item['name']);
				break;
			// рюкзаки
			case 'backpacks':
				$qDressed = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `inventory_type`="backpacks" AND `dressed`=1');
				// если уже одет
				if(mysql_num_rows($qDressed)!=0)
				{
					$dressed = mysql_fetch_assoc($qDressed);
					// параметры одеваемого
					$qItem = $SQLobj->Query('SELECT * FROM `items_backpacks` WHERE `id`='.($inventory['inventory_id']));
					if(!$qItem) return 'Уже глюки начались. Надо бросать пить...';
					// параметры одетого ранее
					$qItemDressed = $SQLobj->Query('SELECT * FROM `items_backpacks` WHERE `id`='.($dressed['inventory_id']));
					if(!$qItemDressed) return 'Уже глюки начались. Надо бросать пить...';
					$item = mysql_fetch_assoc($qItem);
					$itemDressed = mysql_fetch_assoc($qItemDressed);
					// если новый рюкзак не вместит все вещи
					if($item['weight_up']<($Player->GetWeight()-$item['weight']+$itemDressed['weight'])) return 'Нет. В этот рюкзак все не поместится.';
					// снять старый
					$undressed = $SQLobj->Query('UPDATE `users_inventory` SET `dressed`=0 WHERE `user_id`='.($Player->id).' AND `inventory_type`="backpacks"');
				}
				if(!$undressed) return 'Уже глюки начались. Надо бросать пить...';
				//одеть рюкзак
				$qDressed = $SQLobj->Query('UPDATE `users_inventory` SET `dressed`=1 WHERE `user_id`='.($Player->id).' AND `id`='.$id);
				if(!$qDressed) return 'Уже глюки начались. Надо бросать пить...';
				$upd = $SQLobj->Query('UPDATE `users_params` SET `weight`=`weight`+'.($item['weight_up']).'-'.($itemDressed['weight_up']).' WHERE `user_id`='.($Player->id));
				if(!$upd) return 'Уже глюки начались. Надо бросать пить...';
				return 'Я одел '.($item['name']);
				break;
			// медикаменты
			case 'medkits':
				$qItem = $SQLobj->Query('SELECT * FROM `items_medkits` WHERE `id`='.($inventory['inventory_id']));
				if(!$qItem) return 'Уже глюки начались. Надо бросать пить...';
				$item = mysql_fetch_assoc($qItem);
				$qDel = $SQLobj->Query('DELETE FROM `users_inventory` WHERE `id`='.$id);
				if(!$qDel) return 'Уже глюки начались. Надо бросать пить...';
				$qUpd = $SQLobj->Query('UPDATE `users` SET `health`=IF((`health`+'.($item['health']).')>100,100,(`health`+'.($item['health']).')), `radiation`=IF((`radiation`-'.($item['unradiation']).')<0,0,(`radiation`-'.($item['unradiation']).')), `blood`=IF((`blood`-'.($item['blood']).')<0,0,(`blood`+-'.($item['blood']).')) WHERE `id`='.($Player->id));
				if(!$qUpd) return 'Уже глюки начались. Надо бросать пить...';
				return 'Я использовал '.($item['name']).'.';
				break;
			case 'artefacts':
				$qEquipment = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `inventory_type` = "equipment" AND `dressed`=1 AND `user_id` = '.($Player->id));
				if(mysql_num_rows($qEquipment)==0) return 'Сначала мне нужно одеть экипировку с контейнером';
				$equipment = mysql_fetch_assoc($qEquipment);
				$item = new TItem('equipment', $equipment['inventory_id']);
				$cells = $item->GetParam('art_cells');
				$qItem = $SQLobj->Query('SELECT * FROM `items_artefacts` WHERE `id`='.($inventory['inventory_id']));
				$item = mysql_fetch_assoc($qItem);
				$qArt_dressed = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `inventory_type` = "artefacts" AND `dressed`=1 AND `user_id` = '.($Player->id));
				$art_dressed = mysql_num_rows($qArt_dressed);
				if($cells<=$art_dressed) return 'В моей экипировке нет свободных ячеек для артефактов.';
				$q = $SQLobj->Query('UPDATE `users_inventory` SET `dressed` = 1 WHERE `id` = '.$id.' AND `user_id` = '.($Player->id));
				// обновить параметры
				$upd = $SQLobj->Query('UPDATE `users_params` SET `acid`=`acid`+'.($item['acid']).', `gap`=`gap`+'.($item['gap']).', `heat`=`heat`+'.($item['heat']).', `gravitation`=`gravitation`+'.($item['gravitation']).', `psi`=`psi`+'.($item['psi']).', `bulletproof`=`bulletproof`+'.($item['bulletproof']).', `elec`=`elec`+'.($item['elec']).', `radiation_res`=`radiation_res`+'.($item['radiation_res']).', `woundhealing` = `woundhealing`+'.($item['woundhealing']).' WHERE `user_id`='.($Player->id));
				if(!$upd) return 'Уже глюки начались. Надо бросать пить...';
				return 'Я повесил артефакт '.($item['name']);
				break;
			default: return 'Уже глюки начались. Надо бросать пить...';
			break;
		}
	}
	
}
?>