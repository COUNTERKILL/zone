<?php
// ������
Class Model_Admin{
	private function IsSpawn($prob)
	{
		$i = rand(1,100);
		if($prob>=$i) return true; else return false;
	}
	function SpawnAnomalies()
	{
		global $SQLobj;
		$q = $SQLobj->Query('DELETE FROM `way_anomalies`');
		$q = $SQLobj->Query('INSERT INTO `way_anomalies` (`way_id`,`anomaly`,`count`, `death`) SELECT `way_id`,`anomaly`, (RAND()*(count_max-count_min)+count_min), 100 FROM `base_way_anomalies`');
		return;
	}
	function SpawnArtefacts()
	{
		global $SQLobj;
		$q = $SQLobj->Query('DELETE FROM `way_artefacts`');
		$qTartefacts = $SQLobj->Query('SELECT * FROM `items_artefacts` ORDER BY `appearance`');
		while($artefact = mysql_fetch_assoc($qTartefacts))
		{
			$tArtefacts[$artefact['id']] = $artefact;
		}
		$qWay_anomalies = $SQLobj->Query('SELECT * FROM `way_anomalies` WHERE `death`!=0');
		while($way_anomaly = mysql_fetch_assoc($qWay_anomalies)) // ���������� ��� ������������ ��������
		{
			$anomaly = $way_anomaly['anomaly'];
			$death = $way_anomaly['death']; // ���������� ������� � ���� ��������
			$way = $way_anomaly['way_id'];
			$qArtefacts = $SQLobj->Query('SELECT `artefacts` FROM `anomalies` WHERE `id` = '.($way_anomaly['anomaly'])); 
			$artefacts = mysql_fetch_assoc($qArtefacts);
			$artefacts = $artefacts['artefacts'];
			$artefacts = explode(';', $artefacts); // ���������, ������������ � ���� ��������
			unset($spawn); // ������� ����������, �������� ��������� � �� ���������� ��� ������� ��������
			for($i = 0; $i<$death; $i++) // ��� ������ ������ ���������� ���
			{
				foreach($artefacts as $key => $value) //  � value id ����
				{
					$appearance = $tArtefacts[$value]['appearance'];
					if($this->IsSpawn($appearance))
					{
						if(isset($spawn[$value])) $spawn[$value] = $spawn[$value]+1; else $spawn[$value]=1;
						break;
					}
				}
			}
			if(isset($spawn))
			{
				print_r($spawn);
				foreach($spawn as $key => $value)
				{
					$q = $SQLobj->Query('INSERT INTO `way_artefacts` (`way_id`,`anomaly`, `artefact`,`count`) VALUES ('.$way.', '.$anomaly.', '.$key.', '.$value.')');
				}
			}
		}
		return;
	}
}
?>