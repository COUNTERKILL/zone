<?php
// ���������
Class Controller_login Extends Controller_Base {   
    // ������
    public $layouts = "index_layout";
     
    // �����
	 function index() {
		$this->template->vars('title','����');
        $this->template->view('index');
    }  
	function process_login() {
		// ������ ��� �����������
		session_start();
		$model = new Model_Autorization();
        $regResult = $model->Login();
		if($regResult=='') header("Location:".SITE_URL."game");
		$this->template->vars('notification',$regResult);
		$this->template->vars('title','����');
        $this->template->view('index');
    }  
} 
?>