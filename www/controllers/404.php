<?php
// ���������
Class Controller_404 Extends Controller_Base {   
    // ������
    public $layouts = "404";
     
    // �����
    function index() {
        $this->template->view('index');
    }  
} 
?>