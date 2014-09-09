<?php
class TItem
{
	public $id;
	public $class;
	function __construct($type, $id)
	{
		$this->id = $id;
		$this->type = $type;
	}
	function GetParam($param)
	{
		global $SQLobj;
		$selectLocation = 'SELECT * FROM `items_'.($this->type).'` WHERE `id` = '.($this->id);
		$qLoc = $SQLobj->Query($selectLocation);
		if(mysql_num_rows($qLoc) == 0) return 0;
		$loc = mysql_fetch_assoc($qLoc);
		return $loc[$param];
	}
	function GetWeight()
	{
		return $this->GetParam('weight');
	}
	
}
?>