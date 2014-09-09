<?php
// модель
Class Model_Notepad{
	public function GetEvents($Player, $page)
	{
		return $Player->Notepad->GetData($page);
	}
	public function GetLastPage($Player)
	{
		return $Player->Notepad->GetLastPage();
	}
}
?>