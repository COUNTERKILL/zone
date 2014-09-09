<?php
// контролер
Class Controller_Registration Extends Controller_Base {   
    // шаблон
    public $layouts = "index_layout";
     
    // экшен
    function index() {
		$this->template->vars('title','Регистрация');
        $this->template->view('index');
    }  
	function reg() {
		// модель для регистрации
		session_start();
		$model = new Model_Registration();
        $regResult = $model->Reg();
		if($regResult=='') header("Location:".SITE_URL."game");
		$this->template->vars('notification',$regResult);
		$this->template->vars('title','Регистрация');
        $this->template->view('index');
    }  
} 
?>