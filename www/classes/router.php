<?php
// ����� �������
 
Class Router {
 
    private $registry;
    private $path;
    private $args = array();
 
    // �������� ���������
    function __construct($registry) {
        $this->registry = $registry;
    }
 
    // ������ ���� �� ����� � �������������
    function setPath($path) {
        $path = trim($path, '/\\');
        $path .= '/';
        // ���� ���� �� ����������, ������������� �� ����
        if (is_dir($path) == false) {
            throw new Exception ('Invalid controller path: `' . $path . '`');
        }
        $this->path = $path;
    }  
     
    // ����������� ����������� � ������ �� ����
	// ���� ��� �� ���������� � �� ����, � � ���������� ���� ���� index ��� ���� � ������ ���������� � ����������� .php, �� ��� ��� - ����������, ��������� action
    private function getController(&$file, &$controller, &$action, &$args, &$tmpPath) {
        $route = (empty($_GET['route'])) ? '' : $_GET['route'];
        unset($_GET['route']);
        if (empty($route)) {
            $route = 'index';
        }
         
        // �������� ����� ����
        $route = trim($route, '/\\');
        $parts = explode('/', $route);
        // ������� ����������
        $cmd_path = $this->path; // /controllers/
		$tmpPath = '';
        foreach ($parts as $part) {// ��������� � ������ �������
            $fullpath = $cmd_path . $part;
            // �������� ������������� �����
            if (is_dir($fullpath)) {
                $cmd_path .= $part . '/';
				$lastDir = $part;
                array_shift($parts);
				$tmpPath .= $part.'/';
                continue;
            }
            // ������� ����
            if (is_file($fullpath . '.php')) {
                $controller = $part;
                array_shift($parts);
				$tmpPath .= $part;
                break;
            }
        }
 
        // ���� ���� �� ������ ���������, �� ����������� ����������� index
        if (empty($controller)) {
			if(is_file($cmd_path . 'index.php'))
			{
				$controller = 'index';
				$tmpPath .= $controller;
			}
			elseif(is_file($cmd_path . $lastDir.'.php'))
			{
				$controller = $lastDir;
				$tmpPath .= $controller;
			}
			else
			{
				$cmd_path = $this->path;
				$controller = '404';
			}
        }
 
        // �������� �����
        $action = array_shift($parts);
        if (empty($action)) {
            $action = 'index';
        }
 
        $file = $cmd_path . $controller . '.php';
        $args = $parts;
    }
     
    function start() {
        // ����������� ����
        $this->getController($file, $controller, $action, $args, $tmpPath);
         
        // �������� ������������� �����, ����� 404
        if (is_readable($file) == false) {
            header("Status: 404 Not Found");
			header('Location:'.SITE_URL.'404');
			
        }
         
        // ���������� ����
        include ($file);
 
        // ������ ��������� �����������
        $class = 'Controller_' . $controller;
        $controller = new $class($this->registry, $tmpPath);
         
        // ���� ����� �� ���������� - 404
        if (is_callable(array($controller, $action)) == false) {
                        header("Status: 404 Not Found");
						header('Location:'.SITE_URL.'404');
        }
 
        // ��������� �����
        $controller->$action();
    }
}
?>