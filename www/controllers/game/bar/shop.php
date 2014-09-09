<?php
// контролер
Class Controller_Shop Extends Controller_Base {   
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
		$this->template->vars('title', 'Барыга');
		$this->template->vars('Player', $Player);
        $this->template->view('index');
    }  
	function buy() 
	{
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
		$category='';
		if(isset($_GET['category']))
		{
			switch($_GET['category']) 
			{
				case 'weapons': $category='weapons'; break;
				case 'alcohol': $category='alcohol'; break;
				//case 'eat': $category='eat'; break;
				case 'medkits': $category='medkits'; break;
				case 'equipment': $category='equipment'; break;
				case 'backpacks': $category='backpacks'; break;
				default:$category=''; break;
			}
		}
		$this->template->vars('title', 'Купить');
		$this->template->vars('Player', $Player);
		if($category!='')
		{
			$id = 0;
			if(isset($_GET['id'])) $id = intval($_GET['id']);
			$model = new Model_Shop();
			$this->template->vars('Goods', $model->GetGoods($Player, $category));
			$this->template->vars('notification',$model->BuyGood($Player, $category, $id));
			$this->template->vars('category', $category);
			$this->template->view('buy_with_category');
		}
		else
		{
			$model = new Model_Shop();
			$this->template->vars('Categories', $model->GetCategories($Player->Location->Barman->GetParam('id')));
			$this->template->view('buy');
		}
	}  
	function cell() 
	{
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
		$category='';
		if(isset($_GET['category']))
		{
			switch($_GET['category']) 
			{
				case 'weapons': $category='weapons'; break;
				case 'alcohol': $category='alcohol'; break;
				//case 'eat': $category='eat'; break;
				case 'medkits': $category='medkits'; break;
				case 'equipment': $category='equipment'; break;
				case 'backpacks': $category='backpacks'; break;
				case 'artefacts': $category='artefacts'; break;
				default:$category=''; break;
			}
		}
		$this->template->vars('title', 'Купить');
		$this->template->vars('Player', $Player);
		if($category!='')
		{
			$id = 0;
			if(isset($_GET['id'])) $id = intval($_GET['id']);
			$model = new Model_Shop();
			$this->template->vars('notification',$model->CellGood($Player, $category, $id));
			$this->template->vars('Items', $model->GetItems($Player));
			$this->template->vars('category', $category);
			$this->template->view('cell');
		}
		else
		{
			$model = new Model_Shop();
			$this->template->vars('Items', $model->GetItems($Player));
			$this->template->view('cell');
		}
	}
	
} 
?>