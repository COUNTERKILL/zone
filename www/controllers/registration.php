<?php
// ���������
Class Controller_Registration Extends Controller_Base {   
    // ������
    public $layouts = "index_layout";
     
    // �����
    function index() {
		$this->template->vars('title','�����������');
        $this->template->view('index');
    }  
	function reg() {
		// ������ ��� �����������
		session_start();
		$model = new Model_Registration();
        $regResult = $model->Reg();
		if($regResult=='') header("Location:".SITE_URL."game");
		$this->template->vars('notification',$regResult);
		$this->template->vars('title','�����������');
        $this->template->view('index');
    }  
} 
?>