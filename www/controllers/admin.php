<?php
// контролер
Class Controller_Admin Extends Controller_Base {   
    // шаблон
    public $layouts = "game_layout";
     
    // экшен
    function index() {
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		if($Player->GetParam('type')!='admin')
		{
			header("Location:".SITE_URL."game"); // если не админ
			exit;
		}
		$Player->UpdateIP();
		$Player->UpdateOther();
		$this->template->vars('title', 'Админка');
		$this->template->vars('Player', $Player);
        $this->template->view('index');
    }  
	function vybros()
	{
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		if($Player->GetParam('type')!='admin')
		{
			header("Location:".SITE_URL."game"); // если не админ
			exit;
		}
		$Player->UpdateIP();
		$Player->UpdateOther();
		$this->template->vars('title', 'Админка: выброс');
		$this->template->vars('Player', $Player);
        $this->template->view('vybros');
		$model = new Model_Admin();
		$model->SpawnArtefacts();
		$model->SpawnAnomalies();
	}
} 
?>