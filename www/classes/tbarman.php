<?php
class TBarman
{
	private $id;
	
	function __construct($id=NULL)
	{
		$this->id = $id;
	}
	function GetParam($param)
	{
		global $SQLobj;
		$selectBarman = "SELECT * FROM `barmans` WHERE `id` = ".($this->id)."";
		$qBarman = $SQLobj->Query($selectBarman);
		if(mysql_num_rows($qBarman) == 0) return 0;
		$barman = mysql_fetch_assoc($qBarman);
		return $barman[$param];
	}
}
?>