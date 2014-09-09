<?php
class TAnomaly
{
	public $id;
	function __construct($id)
	{
		$this->id = $id;
	}
	function GetParams()
	{
		global $SQLobj;
		$selectAnomaly = 'SELECT * FROM `anomalies` WHERE `id` = '.($this->id);
		$qAnom = $SQLobj->Query($selectAnomaly);
		if(mysql_num_rows($qAnom) == 0) return 0;
		$anom = mysql_fetch_assoc($qAnom);
		return $anom;
	}
	
}
?>