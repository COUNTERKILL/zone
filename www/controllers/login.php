<?php
// контролер
Class Controller_login Extends Controller_Base {   
    // шаблон
    public $layouts = "index_layout";
     
    // экшен
	 function index() {
		$this->template->vars('title','¬ход');
        $this->template->view('index');
    }  
	function process_login() {
		// модель дл€ регистрации
		session_start();
		$model = new Model_Autorization();
        $regResult = $model->Login();
		if($regResult=='') header("Location:".SITE_URL."game");
		$this->template->vars('notification',$regResult);
		$this->template->vars('title','¬ход');
        $this->template->view('index');
    }  
} 
?>