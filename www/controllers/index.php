<?php
// ���������
Class Controller_Index Extends Controller_Base {   
    // ������
    public $layouts = "index_layout";
     
    // �����
    function index() {
		if(isset($_SESSION['name']))
		{
			header("Location:".SITE_URL."game"); // ��� �����������
			exit;
		}
		$this->template->vars('title','�������');
        $this->template->view('index');
    }  
} 
?>