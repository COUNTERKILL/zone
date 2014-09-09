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
		if(!$Player->Location->BarmanIsset())
		{
			header("Location:".SITE_URL."game"); // ��� �������
				exit;
		}
		if($Player->InZone())
		{
			header("Location:".SITE_URL."game"); // � ����
			exit;
		}
		$Player->UpdateIP();
		$Player->UpdateOther();
		$this->template->vars('title', '���');
		$this->template->vars('Player', $Player);
        $this->template->view('index');
    }  
	
} 
?>