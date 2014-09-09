<?php
// ���������
Class Controller_Zone Extends Controller_Base {   
    // ������
    public $layouts = "game_layout";
     
    // �����
    function index() {
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		$Player->UpdateIP();
		$Player->UpdateOther();
		if($Player->InZone())
		{
			header("Location:".SITE_URL."game/zone/location?id=0");
			exit;
		}
		$model = new Model_Zone();
		$this->template->vars('title', '����');
		if(isset($_GET['id']))
		{
			$this->template->vars('location',$model->GetLocation($Player,intval($_GET['id'])));
		}
		else
		{
			$this->template->vars('locations',$model->GetLocations($Player));
		}
		$this->template->vars('Player', $Player);
        $this->template->view('index');
    }  
	function location() {
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		$Player->UpdateIP();
		$Player->UpdateOther();
		if($Player->InAnomaly())
		{
			header("Location:".SITE_URL."game/zone/anomaly");
			exit;
		}
		$model = new Model_Zone();
		$this->template->vars('title', '����');
		if(isset($_GET['id']))
		{
			$this->template->vars('notification',$model->GoToZone($Player,intval($_GET['id'])));
		}
		else
		{
			$this->template->vars('notification','���... ���� ������� ����. ��� ����� ��������...');
		}
		$this->template->vars('location',$model->GetLocation($Player,$Player->GetParam('loc_to')));
		$this->template->vars('Player', $Player);
        $this->template->view('location');
    }  
	function anomaly() {
		session_start();
		$this->autorize();
		$Player = new TPlayer();
		$Player->UpdateIP();
		$Player->UpdateOther();
		if(!($Player->InAnomaly())) // ���� ��� �� ����� �� ��������
		{
			header("Location:".SITE_URL."game/notepad");
			exit;
		}
		$model = new Model_Zone();
		// ���� �� �������, �� �������� � �������������
		$this->template->vars('title', '����');
		if(isset($_GET['side'])) // ���� ����� ������
		{
			switch($_GET['side'])
			{
				case 'left': $side='left';
					break;
				case 'right': $side='right';
					break;
				default: $side='left';
			}
			$model->AroundAnomaly($Player, $side);
			header("Location:".SITE_URL."game/notepad");
			exit;
		}
		else
		{
			// ������� �������� � ������������ ������ (������ "������"/"�����")
			$this->template->vars('anomaly', $model->GetAnomalyParams($Player));
			$this->template->vars('Player', $Player);
			$this->template->view('around_anomaly');
		}
		
    }
	
} 
?>
