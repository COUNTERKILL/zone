<?php
// ���������
Class Controller_Admin Extends Controller_Base {   
    // ������
    public $layouts = "game_layout";
     
    // �����
    function index() {
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		if($Player->GetParam('type')!='admin')
		{
			header("Location:".SITE_URL."game"); // ���� �� �����
			exit;
		}
		$Player->UpdateIP();
		$Player->UpdateOther();
		$this->template->vars('title', '�������');
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
			header("Location:".SITE_URL."game"); // ���� �� �����
			exit;
		}
		$Player->UpdateIP();
		$Player->UpdateOther();
		$this->template->vars('title', '�������: ������');
		$this->template->vars('Player', $Player);
        $this->template->view('vybros');
		$model = new Model_Admin();
		$model->SpawnArtefacts();
		$model->SpawnAnomalies();
	}
} 
?>