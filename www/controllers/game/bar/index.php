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
		if(!$Player->Location->BarmanIsset())
		{
			header("Location:".SITE_URL."game"); // нет бармена
				exit;
		}
		if($Player->InZone())
		{
			header("Location:".SITE_URL."game"); // в Зоне
			exit;
		}
		$Player->UpdateIP();
		$Player->UpdateOther();
		$this->template->vars('title', 'Бар');
		$this->template->vars('Player', $Player);
        $this->template->view('index');
    }  
	
} 
?>