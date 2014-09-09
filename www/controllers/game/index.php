<?php
// контролер
Class Controller_Index Extends Controller_Base {   
    // шаблон
    public $layouts = "game_layout";
     
    // экшен
    function index() {
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		$Player->UpdateIP();
		$Player->UpdateOther();
		$this->template->vars('title', 'Главная');
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