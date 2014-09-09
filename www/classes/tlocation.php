<?php
class TLocation
{
	public $id;
	public $Barman;
	function __construct($id=NULL)
	{
		$this->id = $id;
		if($this->BarmanIsset()) $this->Barman = new TBarman($this->GetParam('barman_id'));
	}
	function GetParam($param)
	{
		global $SQLobj;
		$selectLocation = "SELECT * FROM `locations` WHERE `loc_id` = ".($this->id);
		$qLoc = $SQLobj->Query($selectLocation);
		if(mysql_num_rows($qLoc) == 0) return 0;
		$loc = mysql_fetch_assoc($qLoc);
		return $loc[$param];
	}
	function GetParamById($id,$param)
	{
		global $SQLobj;
		$selectLocation = "SELECT * FROM `locations` WHERE `loc_id` = ".($id)."";
		$qLoc = $SQLobj->Query($selectLocation);
		if(mysql_num_rows($qLoc) == 0) return 0;
		$loc = mysql_fetch_assoc($qLoc);
		return $loc[$param];
	}
	function BarmanIsset()
	{
		if ($this->GetParam("barman_id")==0) return false; else return true;
	}
	function GetDistTo($id)
	{
		global $SQLobj;
		$qLoc = $SQLobj->Query('SELECT * FROM `locations` WHERE `loc_id` = '.($this->id));
		$loc = mysql_fetch_assoc($qLoc);
		$loc_from_x = $loc['loc_pos_x'];
		$loc_from_y = $loc['loc_pos_y'];
		$qLoc = $SQLobj->Query('SELECT * FROM `locations` WHERE `loc_id` = '.$id);
		$loc = mysql_fetch_assoc($qLoc);
		$loc_to_x = $loc['loc_pos_x'];
		$loc_to_y = $loc['loc_pos_y'];
		$dist = sqrt(($loc_from_x-$loc_to_x)*($loc_from_x-$loc_to_x)+($loc_from_y-$loc_to_y)*($loc_from_y-$loc_to_y));
		return $dist;
	}
}
?>