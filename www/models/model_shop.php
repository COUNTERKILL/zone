<?php
// модель
Class Model_shop{
	public function GetGoods($Player, $category)
	{
		global $SQLobj;
		$barman_id = $Player->Location->Barman->GetParam('id');
		$qGoods = $SQLobj->Query('SELECT * FROM items_'.$category.' WHERE id IN (SELECT `item_id` FROM `barmans_items` WHERE `item_type`="'.$category.'" AND `barman_id`='.$barman_id.');');
		if(mysql_num_rows($qGoods)==0) return '';
		$i = 0;
		while($a=mysql_fetch_assoc($qGoods))
		{
			$cell_coeff = $Player->Location->Barman->GetParam('cell_coeff');
			$a['cost']=$a['cost']*$cell_coeff;
			$res[$i]=$a;
			$i++;
		}
		return $res;
	}	
	
	public function GetItems($Player)
	{
		global $SQLobj;
		$barman_id = $Player->Location->Barman->GetParam('id');
		/*$qItems = $SQLobj->Query('SELECT `users_inventory`.`id` AS `id`, `users_inventory`.inventory_type AS `category`, `items_artefacts`.cost AS `cost`, `items_artefacts`.`name` AS `name`, `items_artefacts`.`description` AS `description`, `items_artefacts`.`image` AS `image`, ROUND(`items_artefacts`.`weight`,2) AS `weight` FROM `items_artefacts` INNER JOIN `users_inventory` ON ((`users_inventory`.inventory_id = `items_artefacts`.id) AND (`users_inventory`.inventory_type="artefacts")) WHERE (`users_inventory`.`dressed` = 0) AND (`users_inventory`.`user_id` = '.($Player->id).')
			UNION ALL SELECT  `users_inventory`.`id` AS `id`, `users_inventory`.inventory_type AS `category`, `items_equipment`.cost AS `cost`, `items_equipment`.`name` AS `name`, `items_equipment`.`description` AS `description`, `items_equipment`.`image` AS `image`, ROUND(`items_equipment`.`weight`,2) AS `weight` FROM `items_equipment` INNER JOIN `users_inventory` ON ((`users_inventory`.inventory_id = `items_equipment`.id) AND (`users_inventory`.inventory_type="equipment")) WHERE (`users_inventory`.`dressed` = 0) AND (`users_inventory`.`user_id` = '.($Player->id).')
			UNION ALL  SELECT  `users_inventory`.`id` AS `id`, `users_inventory`.inventory_type AS `category`, `items_medkits`.cost AS `cost`, `items_medkits`.`name` AS `name`, `items_medkits`.`description` AS `description`, `items_medkits`.`image` AS `image`, ROUND(`items_medkits`.`weight`,2) AS `weight` FROM `items_medkits` INNER JOIN `users_inventory` ON ((`users_inventory`.inventory_id = `items_medkits`.id) AND (`users_inventory`.inventory_type="medkits")) WHERE (`users_inventory`.`dressed` = 0) AND (`users_inventory`.`user_id` = '.($Player->id).') 
			UNION ALL SELECT `users_inventory`.`id` AS `id`, `users_inventory`.inventory_type AS `category`, `items_backpacks`.cost AS `cost`, `items_backpacks`.`name` AS `name`, `items_backpacks`.`description` AS `description`, `items_backpacks`.`image` AS `image`, ROUND(`items_backpacks`.`weight`,2) AS `weight` FROM `items_backpacks` INNER JOIN `users_inventory` ON ((`users_inventory`.inventory_id = `items_backpacks`.id) AND (`users_inventory`.inventory_type="backpacks")) WHERE (`users_inventory`.`dressed` = 0) AND (`users_inventory`.`user_id` = '.($Player->id).') ORDER BY `category`');*/
		/*$qItems = $SQLobj->Query('SELECT * FROM `items_artefacts` WHERE `id` IN (SELECT `inventory_id` FROM `users_inventory` WHERE `inventory_type` = "artefacts" AND `dressed` = 0 AND `user_id` = '.(($Player->id)).')');
		$i = 0;
		if(mysql_num_rows($qItems)!=0)
		{
			while($a=mysql_fetch_assoc($qItems))
			{
				$buy_coeff = $Player->Location->Barman->GetParam('buy_coeff');
				$a['cost']=$a['cost']*$buy_coeff;
				$a['category'] = 'artefacts';
				$res[$i]=$a;
				$i++;
			}
		}
		$qItems = $SQLobj->Query('SELECT * FROM `items_equipment` WHERE `id` IN (SELECT `inventory_id` FROM `users_inventory` WHERE `inventory_type`="equipment" AND `dressed` = 0 AND `user_id` = '.(($Player->id)).')');
		echo mysql_error();
		if(mysql_num_rows($qItems)!=0)
		{
			while($a=mysql_fetch_assoc($qItems))
			{
				$buy_coeff = $Player->Location->Barman->GetParam('buy_coeff');
				$a['cost']=$a['cost']*$buy_coeff;
				$a['category'] = 'equipment';
				$res[$i]=$a;
				$i++;
			}
		}
		$qItems = $SQLobj->Query('SELECT * FROM `items_medkits` WHERE `id` IN (SELECT `inventory_id` FROM `users_inventory` WHERE `inventory_type`="medkits" AND `dressed` = 0 AND `user_id` = '.(($Player->id)).')');
		if(mysql_num_rows($qItems)!=0)
		{
			while($a=mysql_fetch_assoc($qItems))
			{
				$buy_coeff = $Player->Location->Barman->GetParam('buy_coeff');
				$a['cost']=$a['cost']*$buy_coeff;
				$a['category'] = 'medkits';
				$res[$i]=$a;
				$i++;
			}
		}
		$qItems = $SQLobj->Query('SELECT * FROM `items_backpacks` WHERE `id` IN (SELECT `inventory_id` FROM `users_inventory` WHERE `inventory_type`="backpacks" AND `dressed` = 0 AND `user_id` = '.(($Player->id)).')');
		if(mysql_num_rows($qItems)!=0)
		{
			while($a=mysql_fetch_assoc($qItems))
			{
				$buy_coeff = $Player->Location->Barman->GetParam('buy_coeff');
				$a['cost']=$a['cost']*$buy_coeff;
				$a['category'] = 'backpacks';
				$res[$i]=$a;
				$i++;
			}
		}
		*/
		$buy_coeff = $Player->Location->Barman->GetParam('buy_coeff');
		$qInventory = $SQLobj->Query('SELECT * FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `dressed`=0');
		if(mysql_num_rows($qInventory)==0) return '';
		$i = 0;
		while($inventory=mysql_fetch_assoc($qInventory))
		{
			$qItem = $SQLobj->Query('SELECT * FROM `items_'.($inventory['inventory_type']).'` WHERE `id`='.($inventory['inventory_id']));
			$item = mysql_fetch_assoc($qItem);
			$item['type'] = $inventory['inventory_type'];
			$item['inventory_id'] = $inventory['id'];
			$item['category'] = $inventory['inventory_type'];
			$item['cost'] = $item['cost'] * $buy_coeff;
			$res[$i]=$item;
			$i++;
		}
		return $res;
	}	
	public function CellGood($Player, $category, $id)
	{
		global $SQLobj;
		if($id==0) return '';
		$barman_id = $Player->Location->Barman->GetParam('id');
		$qGood = $SQLobj->Query('SELECT * FROM items_'.$category.' WHERE id IN (SELECT `inventory_id` FROM `users_inventory` WHERE `inventory_type`="'.$category.'" AND `user_id`='.($Player->id).' AND `id`='.$id.' AND `dressed` = 0);');
		if(mysql_num_rows($qGood)==0) return 'У меня нет такой вещи!';
		$good = mysql_fetch_assoc($qGood);
		$buy_coeff = $Player->Location->Barman->GetParam('buy_coeff');
		if(!($Player->GiveMoney($buy_coeff*$good['cost'])))  return 'Мне плохо!';
		$q = $SQLobj->Query('DELETE FROM `users_inventory` WHERE `user_id`='.($Player->id).' AND `id`='.$id);
		if($q) return 'Спасибо за продажу '.(htmlspecialchars($good['name'])); else return 'Что-то у меня голова болит. Зайди позже.';
	}
	
	public function BuyGood($Player, $category, $id)
	{
		global $SQLobj;
		if($id==0) return '';
		$barman_id = $Player->Location->Barman->GetParam('id');
		$qGood = $SQLobj->Query('SELECT * FROM items_'.$category.' WHERE id IN (SELECT `item_id` FROM `barmans_items` WHERE `item_type`="'.$category.'" AND `barman_id`='.$barman_id.' AND `item_id`='.$id.');');
		if(mysql_num_rows($qGood)==0) return 'У меня нет такой вещи!';
		$good = mysql_fetch_assoc($qGood);
		$weight = $Player->GetWeight();
		if($Player->GetParam2('weight')<$weight+$good['weight']) return 'А ты это унесешь? Купи рюкзак повместительнее!';
		$cell_coeff = $Player->Location->Barman->GetParam('cell_coeff');
		if(!($Player->GetMoney($cell_coeff*$good['cost'])))  return 'Эээ, нет! '.(htmlspecialchars($good['name'])).' стоит '.($good['cost']).'. Заходи, когда разбогатеешь.';
		$q = $SQLobj->Query('INSERT INTO `users_inventory`(`user_id`,`inventory_type`,`inventory_id`, `weight`) VALUES ('.($Player->id).',"'.$category.'",'.($good['id']).', '.($good['weight']).')');
		if($q) return 'Поздравляю с покупкой '.(htmlspecialchars($good['name'])); else return 'Что-то у меня голова болит. Зайди позже.';
	}
	function GetCategories($barman_id)
	{
		global $SQLobj;
		$qCategories = $SQLobj->Query('SELECT distinct `item_type`  FROM `barmans_items` WHERE `barman_id`='.$barman_id);
		if(mysql_num_rows($qCategories)==0) return '';
		$i=0;
		while($category = mysql_fetch_assoc($qCategories))
		{
		$categories[$i] = $category['item_type'];
		$i++;
		}
		return $categories;
	}
}
?>