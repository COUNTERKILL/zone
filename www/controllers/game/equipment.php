<?php
// ���������
Class Controller_Equipment Extends Controller_Base {   
    // ������
    public $layouts = "game_layout";
     
    // �����
    function index() {
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		$Player->UpdateIP();
		$Player->UpdateOther();
		$model = new Model_Equipment();
		$this->template->vars('title', '�������');
		$this->template->vars('Player', $Player);
		$this->template->vars('Equipment', $model->GetEquipment($Player));
		$this->template->vars('Weapon', $model->GetWeapon($Player));
		$this->template->vars('Artefacts', $model->GetArtefacts($Player));
        $this->template->view('index');
    }  
	function uset(){
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		$Player->UpdateIP();
		$Player->UpdateOther();
		if(isset($_GET['id']))
		{
			$id=intval($_GET['id']);
			$model = new Model_Equipment();
			$this->template->vars('notification',$model->USet($Player,$id));
		}
		else 
		{
			$this->template->vars('notification','��� ����� ��������. ���� ������� ����...');
		}
		$model = new Model_Equipment();
		$this->template->vars('title', '������');
		$this->template->vars('Player', $Player);
		$this->template->vars('Equipment',$model->GetEquipment($Player));
		$this->template->vars('Weapon',$model->GetWeapon($Player));
		$this->template->vars('Artefacts', $model->GetArtefacts($Player));
        $this->template->view('index');
    }  
	    
	
} 
?>