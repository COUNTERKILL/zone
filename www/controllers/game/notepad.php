<?php
// ���������
Class Controller_Notepad Extends Controller_Base {   
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
		if(isset($_GET['p'])) $p = intval($_GET['p']); else $p = 1;
		$model = new Model_Notepad();
		$this->template->vars('Events', $model->GetEvents($Player, $p));
		$this->template->vars('Page', $p);
		$this->template->vars('LastPage', $model->GetLastPage($Player));
		$this->template->vars('Player', $Player);
        $this->template->view('index');
    }  
	
} 
?>