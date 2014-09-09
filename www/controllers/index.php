<?php
// контролер
Class Controller_Index Extends Controller_Base {   
    // шаблон
    public $layouts = "index_layout";
     
    // экшен
    function index() {
		if(isset($_SESSION['name']))
		{
			header("Location:".SITE_URL."game"); // уже авторизован
			exit;
		}
		$this->template->vars('title','Главная');
        $this->template->view('index');
    }  
} 
?>