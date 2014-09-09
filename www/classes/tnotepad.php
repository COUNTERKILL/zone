<?php
class TNotepad
{
	
	private $id;
	function __construct($id)
	{
		$this->id = $id;
	}
	function GetData($page)
	{
		global $SQLobj;
		$limMax = EVENTS_PER_PAGE*$page;
		$limMin = $limMax - EVENTS_PER_PAGE;
		$qEvents = $SQLobj->Query('SELECT * FROM `notepad` WHERE `user_id` = '.($this->id).' ORDER BY `time` DESC LIMIT '.$limMin.', '.EVENTS_PER_PAGE);
		if(@mysql_num_rows($qEvents)==0) return 0;
		$i = 0;
		while($event = mysql_fetch_assoc($qEvents))
		{
			$events[$i] = $event;
			$i++;
		}
		return $events;
	}
	function Set($text)
	{
		global $SQLobj;
		$text = $SQLobj->ParseStr($text);
		$qIns = $SQLobj->Query('INSERT INTO `notepad`(`user_id`, `text`, `time`) VALUES ('.($this->id).', "'.$text.'",'.(time()).')');
		
	}
	function GetLastPage()
	{
		global $SQLobj;
		$qEvents = $SQLobj->Query('SELECT COUNT(*) FROM `notepad` WHERE `user_id` = '.($this->id));
		$count = mysql_fetch_assoc($qEvents);
		return ceil($count['COUNT(*)']/EVENTS_PER_PAGE);
	}
	
}
?>