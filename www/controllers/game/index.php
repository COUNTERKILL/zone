<?php
// ���������
Class Controller_Index Extends Controller_Base {   
    // ������
    public $layouts = "game_layout";
     
    // �����
    function index() {
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		$Player->UpdateIP();
		$Player->UpdateOther();
		$this->template->vars('title', '�������');
		$this->template->vars('Player', $Player);
        $this->template->view('index');
    }  
	function doexit()
	{
		session_start();
		if(isset($_SESSION['name'])) unset($_SESSION['name']);
		header("Location:".SITE_URL);
	}
} 
?>