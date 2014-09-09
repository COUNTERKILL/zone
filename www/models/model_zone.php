<?php
// модель
Class Model_zone{
	
	public function GetLocations($Player)
	{
		global $SQLobj;
		$loc=$Player->GetParam('location');
		$q = $SQLobj->Query('SELECT * FROM `ways` WHERE `loc_from`='.$loc.' or `loc_to`='.$loc);
		if(mysql_num_rows($q)==0) return 0;
		$i = 0;
		while($a = mysql_fetch_assoc($q))
		{
			$loc_to = $a['loc_from']==$loc?$a['loc_to']:$a['loc_from'];
			$qLocName = $SQLobj->Query('SELECT * FROM `locations` WHERE `loc_id`='.$loc_to);
			$locNames = mysql_fetch_assoc($qLocName);
			$map = $locNames['map'];
			$res[$i]['loc_id'] = $loc_to;
			$res[$i]['loc_name'] = $locNames['loc_name'];
			$qMapName = $SQLobj->Query('SELECT * FROM `maps` WHERE `map_id`='.$map);
			$mapNames = mysql_fetch_assoc($qMapName);
			$res[$i]['map_name'] = $mapNames['map_name'];
			$i++;
		}
		return $res;
	}	
	public function GetLocation($Player,$id)
	{
		global $SQLobj;
		$locs = $this->GetLocations($Player);
		$issetWay = false;
		foreach($locs as $key=>$value)
		{
			if($value['loc_id']==$id) $issetWay = true;
		}
		if(!$issetWay) return 0;
		$qLocName = $SQLobj->Query('SELECT * FROM `locations` WHERE `loc_id`='.$id);
		$locNames = mysql_fetch_assoc($qLocName);
		return $locNames;
		
	}
	function IsHit($prob)
	{
		$i = rand(1,100)/100;
		if($prob>=$i) return true; else return false;
	}
	function GoToZone($Player, $id)
	{
		global $SQLobj;
		if($Player->InZone()) return 'я в «оне!'; // если уже в «оне, то завершаем
		if(($loc=$this->GetLocation($Player, $id))==0) return 'Ќет. я до этого места не дойду. Ёто верна€ смерть!'; // если нет такого пути выходим
		
		$Player->SetParam('loc_to', $id); // указываем, что игрок в «оне
		$qWays = $SQLobj->Query('SELECT * FROM `ways` WHERE `loc_from`='.($loc['loc_id']).' or `loc_to`='.($loc['loc_id']));
		if(!$qWays) return ''; // провер€ем лишь то, что запрос выполнен, т.к. путь точно есть(т.е. есть результат запроса)
		$Ways = mysql_fetch_assoc($qWays);
		$way_id = $Ways['way_id'];
		
		
		// считаем рассто€ние между локаци€ми
		$dist = $Player->Location->GetDistTo($id);
		
		
		// таблица аномалий на данном пути => id | anomaly | count
		$qWay_anomalies = $SQLobj->Query('SELECT * FROM `way_anomalies` WHERE `way_id`='.$way_id);
		if(mysql_num_rows($qWay_anomalies)==0)
		{
			//нет аномалий
		}
		
		
		
		
		
		// записываем таблицу аномалий на пути в массив, считаем общее число аномалий
		$i = 0;
		$checks_count = 0; // счетчик аномалий
		while($Way_anomaly = mysql_fetch_assoc($qWay_anomalies))
		{
			$Way_anomalies[$i] = $Way_anomaly;
			$checks_count += $Way_anomaly['count'];
			$i++;
		}
		
		
		
		// считаем количество контрольных точек
		$checks_max_anom = ceil(rand(0.4*$checks_count,$checks_count)); // количество контрольных точек дл€ аномалий на пути
		
		
		// добавл€ем пользователю в путь
		$insWay = $SQLobj->Query('INSERT INTO `users_ways` (`user_id`, `way_id`, `anomaly_check_count`, `anomaly_check_max`) VALUES('.($Player->id).', '.($way_id).', 1, '.$checks_max_anom.')');
		if(!$insWay) return 'ѕить надо меньше!1';
		
		
		$i = 0;
		// заполн€ем массив аномали€ми, которые есть на пути
		foreach($Way_anomalies as $key => $value)
		{
			$qAnomaly = $SQLobj->Query('SELECT * FROM `anomalies` WHERE `id` = '.($value['anomaly']));
			$anomalies[$i] = mysql_fetch_assoc($qAnomaly);
			$i++;
		}
		
		
		$flair = $Player->FlairToPercent($Player->GetParam2('flair')); // чутье
		$anomaly = 0;
		// заполн€ем контрольные точки аномалий в Ѕƒ
		for($i = 1; $i<=$checks_max_anom; $i++)
		{
				$anomaly = $anomalies[rand(0, count($anomalies)-1)];
				$visible = $anomaly['visible'];
				$visible_max = $visible*1.5;
				$visible_min = $visible*0.6;
				$visible_max = ($visible_max>90)?90:$visible_max;
				$visible = rand($visible_min, $visible_max);
				$visible = $visible/100.0;
				$hit = $this->IsHit(1-$visible-$flair);

				
				$insCheck = $SQLobj->Query('INSERT INTO `users_ways_checks_anomalies`(`user_id`, `check_id`, `anomaly`, `see`) VALUES ('.($Player->id).','.($i).','.($anomaly['id']).', '.(intval($hit)).')');
				if(!$insCheck) return 'ѕить надо меньше!';
		}
		
		// обновл€ем таблицу апдейтов
		$updateTimeAnom = ($dist/$Player->GetParam2('speed'))/$checks_max_anom+rand(1,12);
	
		$qUpd = $SQLobj->Query('UPDATE `users_updates` SET `anomalies` = '.(time()+$updateTimeAnom).', `next_anomalies`='.$updateTimeAnom.' WHERE `user_id` = '.($Player->id));
		if(!$qUpd) return 'Ѕрошу пить!';
		$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($Player->id).', "—мена локации", 1, "я отправилс€ в «ону", '.(time()).')');
		
		
		return 'я в «оне!';
	}
	
	function GetAnomalyParams($Player)
	{
		global $SQLobj;
		// узнаем id аномалии, в которую попали
		$qAnomaly = $SQLobj->Query('SELECT * FROM `users_ways_checks_anomalies` WHERE `user_id`='.($Player->id).' AND `check_id`=(SELECT `anomaly_check_count` FROM  `users_ways` WHERE `user_id`='.($Player->id).')');
		if(mysql_num_rows($qAnomaly)==0) return 0;
		$anomaly = mysql_fetch_assoc($qAnomaly);
		$Anomaly = new TAnomaly($anomaly['anomaly']);
		$params = $Anomaly->GetParams();
		$SQLobj->Query('SELECT SUM(`count`) FROM `way_artefacts` WHERE `way_id`=(SELECT `way_id` FROM `users_ways` WHERE `user_id`='.($Player->id).') AND `anomaly`='.($Anomaly->id));
		$dist = $Player->Location->GetDistTo($Player->GetParam('loc_to'));
		$qArtCount = $SQLobj->Query('SELECT SUM(`count`) FROM `way_artefacts` WHERE `way_id`=(SELECT `way_id` FROM `users_ways` WHERE `user_id`='.($Player->id).') AND `anomaly`='.$Anomaly->id);
		$ArtCount = mysql_fetch_assoc($qArtCount);
		
		$params['art_left'] = ceil($ArtCount['SUM(`count`)'] * ANOMALY_ARTEFACTS_COEFF_LEFT * 100 / $dist);
		$params['art_right'] = ceil($ArtCount['SUM(`count`)'] * ANOMALY_ARTEFACTS_COEFF_RIGHT * 100 / $dist);
		$params['anom_left'] = $params['difficult'] * ANOMALY_ARTEFACTS_COEFF_LEFT;
		$params['anom_right'] = $params['difficult'] * ANOMALY_ARTEFACTS_COEFF_RIGHT;
		// учесть опыт чуть€
		return $params;
	}
	
	function AroundAnomaly($Player, $side)
	{
		
	}
}
?>