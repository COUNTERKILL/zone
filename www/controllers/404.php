<?php
// контролер
Class Controller_404 Extends Controller_Base {   
    // шаблон
    public $layouts = "404";
     
    // экшен
    function index() {
        $this->template->view('index');
    }  
} 
?>