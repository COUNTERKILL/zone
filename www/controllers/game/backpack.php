<?php
// ���������
Class Controller_Backpack Extends Controller_Base {   
    // ������
    public $layouts = "game_layout";
     
    // �����
    function index() {
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		$Player->UpdateIP();
		$Player->UpdateOther();
		$model = new Model_Backpack();
		$this->template->vars('title', '������');
		$this->template->vars('Player', $Player);
		$this->template->vars('Inventory',$model->GetInventory($Player));
		$this->template->vars('BackpackImage',$model->GetBackpackImage($Player));
        $this->template->view('index');
    }  
	function set(){
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		$Player->UpdateIP();
		if(isset($_GET['id']))
		{
			$id=intval($_GET['id']);
			$model = new Model_Backpack();
			$this->template->vars('notification',$model->Set($Player,$id));
		}
		else 
		{
			$this->template->vars('notification','��� ����� ��������. ���� ������� ����...');
		}
		$model = new Model_Backpack();
		$this->template->vars('title', '������');
		$this->template->vars('Player', $Player);
		$this->template->vars('Inventory',$model->GetInventory($Player));
		$this->template->vars('BackpackImage',$model->GetBackpackImage($Player));
        $this->template->view('index');
    }  
	    
	
} 
?>