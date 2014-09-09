<?php
// ���������� ����� �����������
Abstract Class Controller_Base {
 
    protected $registry;
    protected $template;
    protected $layouts; // ������
    public $path;
    public $vars = array();
	protected function autorize()
	{
		if(!isset($_SESSION['name']))
		{
			header("Location:".SITE_URL."login"); // �� �����������
			exit;
		}
	}
    // � ������������ ���������� �������
    function __construct($registry, $tmpPath) {
        $this->registry = $registry;
        // �������, ����� � ����� � ������ �����������
        $this->template = new Template($this->layouts, $tmpPath);
    }
 
    abstract function index(); 
}
?>