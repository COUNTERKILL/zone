<?php
// абстрактый класс контроллера
Abstract Class Controller_Base {
 
    protected $registry;
    protected $template;
    protected $layouts; // шаблон
    public $path;
    public $vars = array();
	protected function autorize()
	{
		if(!isset($_SESSION['name']))
		{
			header("Location:".SITE_URL."login"); // не авторизован
			exit;
		}
	}
    // в конструкторе подключаем шаблоны
    function __construct($registry, $tmpPath) {
        $this->registry = $registry;
        // шаблоны, лежат в папке с именем контроллера
        $this->template = new Template($this->layouts, $tmpPath);
    }
 
    abstract function index(); 
}
?>