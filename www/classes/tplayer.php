<?php
class TPlayer
{
	public $autorized;
	public $status;
	public $Location;
	public $Notepad;
	private $name;
	public $id;
	
	//-----------------------------------------------------------------------------------------------------------------
	// �����������. ���������� ������(�� ������/������, ���� �� ������),
	// ���� ������������ � ����������. ���� �����������
	//-----------------------------------------------------------------------------------------------------------------
	function __construct($name = NULL, $pass = NULL, $login = true)
	{
		global $SQLobj;
		$this->autorized = false;
		if(isset($_SESSION['name']))
		{
			$this->autorized = true;
			$this->name = $_SESSION['name'];
			$this->Location = new TLocation($this->GetParam('location'));
			$this->id = $this->GetParam('id');
			$this->Notepad = new TNotepad($this->id);
			return;
		}
		if($name=='' | $pass=='')
		{
			$this->status = "���������� ��������� ��� ����!";
			return;
		}
		if(!$this->IsLogin($name))
		{
			$this->status = "����������� ������� � �����!";
			return;
		}
		$pass = md5($pass.':'.(strtolower($name)).'123456');
		$qUser = $SQLobj->Query('SELECT * FROM `users` WHERE (`name` = "'.$name.'") AND (`password` = "'.$pass.'")');
		if(@mysql_num_rows($qUser) == 0)
		{
			if($login) // ���� ��� �����������
			{
				$this->status = "������������ ��� ��� ������!";
				return;
			}
			//���� ��� �����������
			$qUser = $SQLobj->Query('SELECT * FROM `users` WHERE `name` = "'.$name.'"');
			if(@mysql_num_rows($qUser) != 0)
			{
				$this->status = "��� ��� ��� ������!";
				return;
			}
			if(!($SQLobj->Query('INSERT INTO `users`(`name`,`password`,`ip`) VALUES ("'.$name.'","'.$pass.'","'.($_SERVER["REMOTE_ADDR"]).'")')))
			{
				$this->status = "������ ����������� � ���� ������";
				return;
			}
			$this->name = $name;
			$this->autorized = true;
			if(!($SQLobj->Query('INSERT INTO `users_updates`(`user_id`) VALUES ('.($this->GetParam('id')).')')))
			{
				$this->status = "������ ����������� � ���� ������";
				return;
			}
			if(!($SQLobj->Query('INSERT INTO `users_params`(`user_id`) VALUES ('.($this->GetParam('id')).')')))
			{
				$this->status = "������ ����������� � ���� ������";
				return;
			}
			if(!($SQLobj->Query('INSERT INTO `users_inventory`(`user_id`, `inventory_type`, `inventory_id`, `dressed`, `weight`) VALUES ('.($this->GetParam('id')).', "backpacks", 3, 1, 1)')))
			{
				$this->status = "������ ����������� � ���� ������";
				return;
			}
			$this->autorized = true; // ���������������
		}
		else 
		{
			$this->autorized = true; // �����������
		}
		$this->name = $name;
		$_SESSION['name'] = $this->GetParam('name');
		$this->Location = new TLocation($this->GetParam('location'));
		$this->id = $this->GetParam('id');
		$this->Notepad = new TNotepad($this->id);
	}
	//-----------------------------------------------------------------------------------------------------------------
	// ���������� �������� ������ � ������� users
	//-----------------------------------------------------------------------------------------------------------------
	function GetParam($param)
	{
		global $SQLobj;
		if(!($this->autorized)) return -1;
		$selectUser = "SELECT * FROM `users` WHERE `name` = '".($this->name)."'";
		$qUser = $SQLobj->Query($selectUser);
		if(mysql_num_rows($qUser) == 0) return 0;
		$user = mysql_fetch_assoc($qUser);
		return $user[$param];
	}
	//-----------------------------------------------------------------------------------------------------------------
	// ���������� �������� ������ � ������� users_params
	//-----------------------------------------------------------------------------------------------------------------
	function GetParam2($param)
	{
		global $SQLobj;
		if(!($this->autorized)) return -1;
		$selectUser = "SELECT * FROM `users_params` WHERE `user_id` = ".($this->id);
		$qUser = $SQLobj->Query($selectUser);
		if(mysql_num_rows($qUser) == 0) return 0;
		$user = mysql_fetch_assoc($qUser);
		return $user[$param];
	}
	//-----------------------------------------------------------------------------------------------------------------
	// ������������� �������� ������ � ������� users
	//-----------------------------------------------------------------------------------------------------------------
	function SetParam($param, $value)
	{
		global $SQLobj;
		if(!($this->autorized)) return -1;
		if(is_string($value)) 
		{
			$updateUser = "UPDATE `users` SET `".$param."` ='".$value."' WHERE `name` = '".($this->name)."'";
		}
		else
		{
			$updateUser = "UPDATE `users` SET `".$param."` =".$value." WHERE `name` = '".($this->name)."'";
		}
		$q = $SQLobj->Query($updateUser);
		if($updateUser == 0) return -1;
		return 0;
	}
	//-----------------------------------------------------------------------------------------------------------------
	// �������� � ������ ������ � ���������� $money ��� ���������� false, ���� ������� ���
	//-----------------------------------------------------------------------------------------------------------------
	function GetMoney($money)
	{
		$issetMoney = $this->GetParam('money');
		if($issetMoney<$money) return false;
		$this->SetParam('money',$issetMoney-$money);
		return true;
	}
	//-----------------------------------------------------------------------------------------------------------------
	// ���� ������ ������ � ���������� $money
	//-----------------------------------------------------------------------------------------------------------------
	function GiveMoney($money)
	{
		global $SQLobj;
		$qUpd = $SQLobj->Query('UPDATE `users` SET `money` = `money` + '.$money.' WHERE `id` = '.($this->id));
		if($qUpd) return true; else return false;
	}
	//-----------------------------------------------------------------------------------------------------------------
	//	��������� �������� ����� ����� � ��������(����)
	//-----------------------------------------------------------------------------------------------------------------
	function FlairToPercent($param)
	{
		return tanh($param/800)-0.3; // -0.4 ��� ����� ��������� �����, ��������� ������� ����������� ������� � ��������
	}
	//-----------------------------------------------------------------------------------------------------------------
	// ��� ��������� ������, ��������������� ������������ �� �����?
	//-----------------------------------------------------------------------------------------------------------------
	function IsLogin($string)
	{
		if ($string != ereg_replace("([^0-9a-zA-Z�-��-�_ /-]{1,})","", $string)) return false; else return true;
	}
	//-----------------------------------------------------------------------------------------------------------------
	// ����� � ����?
	//-----------------------------------------------------------------------------------------------------------------
	function InZone()
	{
		if($this->GetParam('loc_to')==0) return false; else return true;
	}
	//-----------------------------------------------------------------------------------------------------------------
	// ��������� IP
	//-----------------------------------------------------------------------------------------------------------------
	function UpdateIP()
	{
		if(!($this->autorized)) return -1;
		$this->SetParam('ip', ($_SERVER["REMOTE_ADDR"]));
		$this->UpdateTime();
		return 0;
	}
	function UpdateTime()
	{
		if(!($this->autorized)) return -1;
		$this->SetParam('time', time());
		return 0;
	}
	//-----------------------------------------------------------------------------------------------------------------
	// �������� ��� ��������� � �������
	//-----------------------------------------------------------------------------------------------------------------
	function GetWeight()
	{
		global $SQLobj;
		$qWeight = $SQLobj->Query('SELECT round(SUM(weight),2) FROM `users_inventory` WHERE `user_id`='.($this->GetParam('id')).' AND `dressed`=0');
		$weight = mysql_fetch_assoc($qWeight);
		$weight = $weight['round(SUM(weight),2)'];
		return $weight;
	}

	//-----------------------------------------------------------------------------------------------------------------
	// ��������� ��������/������������ ���� �������
	//-----------------------------------------------------------------------------------------------------------------
	function UpdateOther()
	{
		/*global $SQLobj;
		
		$checkHealth = false; // �� ��������� �� ��������� ������
		
		
		// ��������� ��������/��������/������������ ��� � 10 �����
		$q = $SQLobj->Query('LOCK TABLES `updates` WRITE');// ��������� ������� �� ������/������
		$qUpdates = $SQLobj->Query('SELECT * FROM `updates`');
		$updates = mysql_fetch_assoc($qUpdates);
		if(time()>$updates['health'])
		{
			$checkHealth = true;
			
			$q = $SQLobj->Query('UPDATE `updates`  SET `health`='.(time()+600)); // ��������� ����� ���������� ���������� ��������
			$q = $SQLobj->Query('UNLOCK TABLES');// ������������ �������
			$q = $SQLobj->Query('UPDATE `users` INNER JOIN `users_params` ON `users`.id = `users_params`.`user_id` SET `users`.`radiation` = IF((`users`.`radiation` + `users_params`.radiation_res)>0, `users`.`radiation` + `users_params`.radiation_res, 0)'); // ���������� ������ ��������(�.�. �� ���� ����� ����������� � ������)
			// ��������� �������� � ������ ����������� ��������
			$q = $SQLobj->Query('UPDATE `users` set `health`=IF((`health`+`health_res`-FLOOR(`radiation`/500.0)-`blood`)>100,100,IF((`health`+`health_res`-FLOOR(`radiation`/500.0)-`blood`)<1,1,(`health`+`health_res`-FLOOR(`radiation`/500)-`blood`)))');
			// ��������� ����
			$q = $SQLobj->Query('UPDATE `users` INNER JOIN `users_params` ON `users`.id = `users_params`.`user_id` SET `blood` = IF(`users`.`blood`-`users_params`.`woundhealing`<0,0,`users`.`blood`-`users_params`.`woundhealing`)');
		}
		else
		{
			// ������������ �������
			$q = $SQLobj->Query('UNLOCK TABLES');
		}
		
		// ��������� �������� ��� � 10 ������
		$q = $SQLobj->Query('LOCK TABLES `updates` WRITE'); // ��������� ������� �� ������/������
		$qUpdates = $SQLobj->Query('SELECT * FROM `updates`');
		$updates = mysql_fetch_assoc($qUpdates);
		if(time()>$updates['anomalies'])
		{
			$checkHealth = true;
			$q = $SQLobj->Query('UPDATE `updates`  SET `anomalies`='.(time()+10)); // ��������� ����� ��������� �������� ��������
			$q = $SQLobj->Query('UNLOCK TABLES'); // ������������ �������
			//����������� �������� �� ���, ��� � ����
			$t = time();
			$qUsers_ways = $SQLobj->Query('SELECT * FROM `users_ways` WHERE `user_id` IN (SELECT `user_id` FROM `users_updates` WHERE `anomalies`<='.$t.')');
			// ��������� ��������� ����� ��������
			$q = $SQLobj->Query('UPDATE `users_updates` SET `anomalies`=`anomalies`+`next_anomalies` WHERE `anomalies`<='.$t);
			if(mysql_num_rows($qUsers_ways)!=0)
			{
				
				while($user_way = mysql_fetch_assoc($qUsers_ways))
				{
					$check_count_anom = $user_way['anomaly_check_count'];
					$max_checks_anom = $user_way['anomaly_check_max'];
					if($check_count_anom>$max_checks_anom) continue;
					$check_count_art = $user_way['artefact_check_count'];
					$max_checks_art = $user_way['artefact_check_max'];
					$qCheck = $SQLobj->Query('SELECT * FROM `users_ways_checks_anomalies` WHERE `user_id` = '.($user_way['user_id']).' AND `check_id` = '.($check_count_anom));
					if(mysql_num_rows($qCheck)!=0)
					{
						$check = mysql_fetch_assoc($qCheck);
						$health = 0;
						if($check['hit'])
						{
							$q = $SQLobj->Query('UPDATE `users` SET `health` = `health` - '.($check['damage']).' WHERE `id` = '.($user_way['user_id']));
							$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user_way['user_id']).', "��������", 0, "'.($check['event']).'", '.($t-1*rand(1,10)).')');
							if(!$q) $SQLobj->SetError();
							// �������� ��
							$qUser = $SQLobj->Query('SELECT `health` FROM `users` WHERE `id`='.($user_way['user_id']));
							$user = $SQLobj->Query($qUser);
							$health = $user['health'];
							if($health<2)
							{
								$q = $SQLobj->Query('UPDATE `users` SET `health` = 1 WHERE `id` = '.($user_way['user_id']));
								$health = 1;
								$q = $SQLobj->Query('UPDATE `way_anomalies` SET `death`=`death`+1 WHERE `anomaly`='.($check['anomaly']).' AND `way_id`='.($user_way['way_id']));
							}
							
						}
						else
						{
							$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user_way['user_id']).', "��������", 1, "'.($check['event']).'", '.$t.')');
							// ���� ����� +1
							$q = $SQLobj->Query('UPDATE `users_params` SET `flair`=`flair`+1 WHERE `user_id` = '.($user_way['user_id']));
						}
						if($check_count_anom<$max_checks_anom)
						{
							$q = $SQLobj->Query('UPDATE `users_ways` SET `anomaly_check_count` = `anomaly_check_count`+1 WHERE `user_id`='.($user_way['user_id']));
						}
						else
						{
							$q = $SQLobj->Query('UPDATE `users_ways` SET `anomaly_check_count` = `anomaly_check_max`+1 WHERE `user_id`='.($user_way['user_id']));
							if($health!=1) // ���� �� ����
							{
								if($check_count_art>$max_checks_art)
								{
									$q = $SQLobj->Query('UPDATE `users` SET `location`=`loc_to` WHERE `id` = '.($user_way['user_id']));
									$q = $SQLobj->Query('UPDATE `users` SET `loc_to` = 0 WHERE `id` = '.($user_way['user_id']));
									$this->Location->id = $this->GetParam('location');
									$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user_way['user_id']).', "����� �������", 1, "� �������� �� ������� '.($this->Location->GetParam('loc_name')).'", '.($t+1).')');
									$q = $SQLobj->Query('DELETE FROM `users_ways` WHERE `user_id` = '.($user_way['user_id']));
									$q = $SQLobj->Query('DELETE FROM `users_ways_checks_anomalies` WHERE `user_id` = '.($user_way['user_id']));
									$q = $SQLobj->Query('DELETE FROM `users_ways_checks_artefacts` WHERE `user_id` = '.($user_way['user_id']));
								}
							}
							else // ����
							{
								$q = $SQLobj->Query('UPDATE `users` SET `money`=0 WHERE `id` = '.($user_way['user_id']));
								$q = $SQLobj->Query('DELETE FROM `users_inventory` WHERE `user_id` = '.($user_way['user_id']));
								$q = $SQLobj->Query('DELETE FROM `users_ways` WHERE `user_id` = '.($user_way['user_id']));
								$q = $SQLobj->Query('DELETE FROM `users_ways_checks_anomalies` WHERE `user_id` = '.($user_way['user_id']));
								$q = $SQLobj->Query('DELETE FROM `users_ways_checks_artefacts` WHERE `user_id` = '.($user_way['user_id']));
							}
							
						}
						
						
					}
				}
			}
		}
		else
		{
			$SQLobj->Query('UNLOCK TABLES');
		}
		
		
		
		// ��������� �������� ��� � 10 ������
		$q = $SQLobj->Query('LOCK TABLES `updates` WRITE'); // ��������� ������� �� ������/������
		$qUpdates = $SQLobj->Query('SELECT * FROM `updates`');
		$updates = mysql_fetch_assoc($qUpdates);
		if(time()>$updates['artefacts'])
		{
			$checkHealth = true;
			$q = $SQLobj->Query('UPDATE `updates`  SET `artefacts`='.(time()+10)); // ��������� ����� ��������� �������� ��������
			$q = $SQLobj->Query('UNLOCK TABLES'); // ������������ �������
			// ����� ���������� ��� ���, ��� � ����
			
			$t = time();
			$qUsers_ways = $SQLobj->Query('SELECT * FROM `users_ways` WHERE `user_id` IN (SELECT `user_id` FROM `users_updates` WHERE `artefacts`<='.$t.')');
			// ��������� ��������� ����� ��������
			$q = $SQLobj->Query('UPDATE `users_updates` SET `artefacts`=`artefacts`+`next_artefacts` WHERE `artefacts`<='.$t);
			if(mysql_num_rows($qUsers_ways)!=0)
			{
				
				while($user_way = mysql_fetch_assoc($qUsers_ways))
				{
					$check_count_anom = $user_way['anomaly_check_count'];
					$max_checks_anom = $user_way['anomaly_check_max'];
					$check_count_art = $user_way['artefact_check_count'];
					$max_checks_art = $user_way['artefact_check_max'];
					if($check_count_art>$max_checks_art) continue;
					$qCheck = $SQLobj->Query('SELECT * FROM `users_ways_checks_artefacts` WHERE `user_id` = '.($user_way['user_id']).' AND `check_id` = '.($check_count_art));
					if(mysql_num_rows($qCheck)!=0)
					{
						$check = mysql_fetch_assoc($qCheck);
						if($check['artefact']!=0)
						{
							$item = new TItem('artefacts', $check['artefact']);
							$qWeight = $SQLobj->Query('SELECT round(SUM(weight),2) FROM `users_inventory` WHERE `user_id`='.($user_way['user_id']).' AND `dressed`=0');
							$weight = mysql_fetch_assoc($qWeight);
							$weight = $weight['round(SUM(weight),2)'];
							$selectUser = "SELECT * FROM `users_params` WHERE `user_id` = ".($user_way['user_id']);
							$qUser = $SQLobj->Query($selectUser);
							$user = mysql_fetch_assoc($qUser);
							$max_weight = $user['weight'];
							$item_weight = $item->GetWeight();
							if(($weight+$item_weight)>$max_weight) // ���� �� �������
							{
								$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user_way['user_id']).', "������ ��������", 0, "'.($check['event']).', �� ��� ���� �� ������� ����� � �������", '.$t.')');
							}
							else // �������
							{
								$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user_way['user_id']).', "������ ��������", 1, "'.($check['event']).'", '.$t.')');
								$q = $SQLobj->Query('INSERT INTO `users_inventory`(`user_id`,`inventory_type`,`inventory_id`, `weight`) VALUES ('.($user_way['user_id']).',"artefacts",'.($check['artefact']).', '.($item_weight).')');
								$q = $SQLobj->Query('UPDATE `way_artefacts` SET `count` = IF(`count`>1, `count`-1, 0) WHERE `way_id` = '.($user_way['way_id']));
							}
						}
						else
						{
							$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user_way['user_id']).', "������ ��������", 0, "'.($check['event']).'", '.$t.')');
						}
						// �������� �������� �� ����� � ����
						if($check_count_art<$max_checks_art)
						{
							$q = $SQLobj->Query('UPDATE `users_ways` SET `artefact_check_count` = `artefact_check_count`+1 WHERE `user_id`='.($user_way['user_id']));
						}
						else // ���� ��� ��������� ���
						{
							$q = $SQLobj->Query('UPDATE `users_ways` SET `artefact_check_count` = `artefact_check_max`+1 WHERE `user_id`='.($user_way['user_id']));
							//���� ��� �������� ��� ��������
							if($check_count_anom>$max_checks_anom)
							{
								$q = $SQLobj->Query('UPDATE `users` SET `location`=`loc_to` WHERE `id` = '.($user_way['user_id']));
								$q = $SQLobj->Query('UPDATE `users` SET `loc_to` = 0 WHERE `id` = '.($user_way['user_id']));
								$this->Location->id = $this->GetParam('location');
								$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user_way['user_id']).', "����� �������", 1, "� �������� �� ������� '.($this->Location->GetParam('loc_name')).'", '.($t+1).')');
								$q = $SQLobj->Query('DELETE FROM `users_ways` WHERE `user_id` = '.($user_way['user_id']));
								$q = $SQLobj->Query('DELETE FROM `users_ways_checks_artefacts` WHERE `user_id` = '.($user_way['user_id']));
								$q = $SQLobj->Query('DELETE FROM `users_ways_checks_anomalies` WHERE `user_id` = '.($user_way['user_id']));
							}
						}
						
						
					}
				}
			}		
		}
		else
		{
			$q = $SQLobj->Query('UNLOCK TABLES');
		}
		
		
		if($checkHealth)
		{
			$q = $SQLobj->Query('LOCK TABLES `users` WRITE'); // ��������� ������� �� ������/������
			$qUsers = $SQLobj->Query('SELECT * FROM `users` WHERE `health`=1');
			if(mysql_num_rows($qUsers)!=0)
			{
				while($user=mysql_fetch_assoc($qUsers))
				{
					if($user['loc_to']!=0)
					{
						$q = $SQLobj->Query('UPDATE `users` SET `loc_to`=0 WHERE `id` = '.($user['id']));
						$q = $SQLobj->Query('DELETE FROM `users_ways` WHERE `user_id` = '.($user['id']));
						$q = $SQLobj->Query('DELETE FROM `users_ways_checks_anomalies` WHERE `user_id` = '.($user['id']));
						$q = $SQLobj->Query('DELETE FROM `users_ways_checks_artefacts` WHERE `user_id` = '.($user['id']));
					}
					$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user['id']).', "��� ������", 2, "�������� ����� ���� ��� ����� � ������� ����������. ��� ��������� - �� �����.", '.(time()+1).')');
					$q = $SQLobj->Query('UPDATE `users` SET `money`=0 WHERE `id` = '.($user['id']));
					$q = $SQLobj->Query('DELETE FROM `users_inventory` WHERE `dressed`=0 AND `user_id` = '.($user['id']));
					$q = $SQLobj->Query('UPDATE `users` SET `health` = 2 WHERE `id` = '.($user['id']));
				}
			}
			$SQLobj->Query('UNLOCK TABLES');
		}
		// ���� �������� �� ����������� �����, ��������� � �������� ������
		if(!$checkHealth)
		{
			$qUsers = $SQLobj->Query('SELECT * FROM `users` WHERE `health`=1 AND `id` = '.($this->id));
			if(mysql_num_rows($qUsers)!=0)
			{
				$user=mysql_fetch_assoc($qUsers);
				if($user['loc_to']!=0)
				{
					$q = $SQLobj->Query('UPDATE `users` SET `loc_to`=0 WHERE `id` = '.($user['id']));
					$q = $SQLobj->Query('DELETE FROM `users_ways` WHERE `user_id` = '.($user['id']));
					$q = $SQLobj->Query('DELETE FROM `users_ways_checks_anomalies` WHERE `user_id` = '.($user['id']));
					$q = $SQLobj->Query('DELETE FROM `users_ways_checks_artefacts` WHERE `user_id` = '.($user['id']));
				}
				$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user['id']).', "��� ������", 2, "�������� ����� ���� ��� ����� � ������� ����������. ��� ��������� - �� �����.", '.(time()+1).')');
				$q = $SQLobj->Query('UPDATE `users` SET `money`=0 WHERE `id` = '.($user['id']));
				$q = $SQLobj->Query('DELETE FROM `users_inventory` WHERE `dressed`=0 AND `user_id` = '.($user['id']));
				$q = $SQLobj->Query('UPDATE `users` SET `health` = 2 WHERE `id` = '.($user['id']));
			}
		}*/
	}
	function CheckAnomalies()
	{
		global $SQLobj;
		// ��������� ��������
		$t = time();
		$qUsers_ways = $SQLobj->Query('SELECT * FROM `users_ways` WHERE `user_id` IN (SELECT `user_id` FROM `users_updates` WHERE `anomalies`<='.$t.' AND `user_id`='.($this->id).')');
		// ��������� ��������� ����� ��������
		if(mysql_num_rows($qUsers_ways)!=0)
		{
			$q = $SQLobj->Query('UPDATE `users_updates` SET `anomalies`=`anomalies`+`next_anomalies` WHERE `user_id`='.($this->id));
			$user_way = mysql_fetch_assoc($qUsers_ways);
			
			$check_count_anom = $user_way['anomaly_check_count'];
			$max_checks_anom = $user_way['anomaly_check_max'];
			if($check_count_anom>$max_checks_anom) continue;
			$check_count_art = $user_way['artefact_check_count'];
			$max_checks_art = $user_way['artefact_check_max'];
			$qCheck = $SQLobj->Query('SELECT * FROM `users_ways_checks_anomalies` WHERE `user_id` = '.($user_way['user_id']).' AND `check_id` = '.($check_count_anom));
			if(mysql_num_rows($qCheck)!=0)
			{
				$check = mysql_fetch_assoc($qCheck);
				$health = 0;
				if($check['hit'])
				{
					$q = $SQLobj->Query('UPDATE `users` SET `health` = `health` - '.($check['damage']).' WHERE `id` = '.($user_way['user_id']));
					$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user_way['user_id']).', "��������", 0, "'.($check['event']).'", '.($t-1*rand(1,10)).')');
					if(!$q) $SQLobj->SetError();
					// �������� ��
					$qUser = $SQLobj->Query('SELECT `health` FROM `users` WHERE `id`='.($user_way['user_id']));
					$user = $SQLobj->Query($qUser);
					$health = $user['health'];
					if($health<2)
					{
						$q = $SQLobj->Query('UPDATE `users` SET `health` = 1 WHERE `id` = '.($user_way['user_id']));
						$health = 1;
						$q = $SQLobj->Query('UPDATE `way_anomalies` SET `death`=`death`+1 WHERE `anomaly`='.($check['anomaly']).' AND `way_id`='.($user_way['way_id']));
					}
					
				}
				else
				{
					$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user_way['user_id']).', "��������", 1, "'.($check['event']).'", '.$t.')');
					// ���� ����� +1
					$q = $SQLobj->Query('UPDATE `users_params` SET `flair`=`flair`+1 WHERE `user_id` = '.($user_way['user_id']));
				}
				if($check_count_anom<$max_checks_anom)
				{
					$q = $SQLobj->Query('UPDATE `users_ways` SET `anomaly_check_count` = `anomaly_check_count`+1 WHERE `user_id`='.($user_way['user_id']));
				}
				else
				{
					$q = $SQLobj->Query('UPDATE `users_ways` SET `anomaly_check_count` = `anomaly_check_max`+1 WHERE `user_id`='.($user_way['user_id']));
					if($health!=1) // ���� �� ����
					{
						if($check_count_art>$max_checks_art)
						{
							$q = $SQLobj->Query('UPDATE `users` SET `location`=`loc_to` WHERE `id` = '.($user_way['user_id']));
							$q = $SQLobj->Query('UPDATE `users` SET `loc_to` = 0 WHERE `id` = '.($user_way['user_id']));
							$this->Location->id = $this->GetParam('location');
							$q = $SQLobj->Query('INSERT INTO `notepad` (`user_id`, `title`, `type`, `text`, `time`) VALUES ('.($user_way['user_id']).', "����� �������", 1, "� �������� �� ������� '.($this->Location->GetParam('loc_name')).'", '.($t+1).')');
							$q = $SQLobj->Query('DELETE FROM `users_ways` WHERE `user_id` = '.($user_way['user_id']));
							$q = $SQLobj->Query('DELETE FROM `users_ways_checks_anomalies` WHERE `user_id` = '.($user_way['user_id']));
							$q = $SQLobj->Query('DELETE FROM `users_ways_checks_artefacts` WHERE `user_id` = '.($user_way['user_id']));
						}
					}
					else // ����
					{
						$q = $SQLobj->Query('UPDATE `users` SET `money`=0 WHERE `id` = '.($user_way['user_id']));
						$q = $SQLobj->Query('DELETE FROM `users_inventory` WHERE `user_id` = '.($user_way['user_id']));
						$q = $SQLobj->Query('DELETE FROM `users_ways` WHERE `user_id` = '.($user_way['user_id']));
						$q = $SQLobj->Query('DELETE FROM `users_ways_checks_anomalies` WHERE `user_id` = '.($user_way['user_id']));
						$q = $SQLobj->Query('DELETE FROM `users_ways_checks_artefacts` WHERE `user_id` = '.($user_way['user_id']));
					}
					
				}
				
				
			}
		
		}
	}
	function InAnomaly()
	{
		global $SQLobj;
		$qUpdate_anomaly = $SQLobj->Query('SELECT * FROM `users_updates` WHERE `user_id`=(SELECT `user_id` FROM `users_ways` WHERE `user_id`='.($this->id).') AND `anomalies`<'.(time()));
		if (mysql_num_rows($qUpdate_anomaly)==0) return false; else return true;
	}
}
?>