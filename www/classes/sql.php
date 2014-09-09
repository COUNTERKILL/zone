<?php
// абстрактый класс контроллера
Class SQL {
	private $error;
 
    // в конструкторе подключаем шаблоны
    function __construct() 
	{
        mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
		mysql_query("SET NAMES 'cp1251'"); 
		mysql_query("SET CHARACTER SET 'cp1251'");
		mysql_query("SET SESSION collation_connection = 'cp1251_general_ci'");
		mysql_query('SET AUTOCOMMIT=0');
		//mysql_query('SET TRANSACTION ISOLATION LEVEL READ COMMTITED'); // ƒанные доступны только после коммита
		mysql_query('START TRANSACTION');
		$this->error = false;
		return;
    }
	function Destroy()
	{
		if(!$this->IsError()) mysql_query('COMMIT'); else mysql_query('ROLLBACK');
		echo ($this->IsError());
		//mysql_query('ROLLBACK');
		mysql_close();
		unset($this);
	}
	function ParseInt($str)
	{
		return intval($str);
	}
	function ParseStr($str)
	{
		return mysql_real_escape_string($str);
	}
	function SetError()
	{
		$this->error = true;
		return 0;
	}
	function IsError()
	{
		return $this->error;
	}
	function Query($str)
	{
		$q = mysql_query($str);
		if(!$q)
		{	
		  $this->SetError();
		  echo $str;
		}

		return $q;
	}
    
}
?>