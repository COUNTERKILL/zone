<?php
// класс роутера
 
Class Router {
 
    private $registry;
    private $path;
    private $args = array();
 
    // получаем хранилище
    function __construct($registry) {
        $this->registry = $registry;
    }
 
    // задаем путь до папки с контроллерами
    function setPath($path) {
        $path = trim($path, '/\\');
        $path .= '/';
        // если путь не существует, сигнализируем об этом
        if (is_dir($path) == false) {
            throw new Exception ('Invalid controller path: `' . $path . '`');
        }
        $this->path = $path;
    }  
     
    // определение контроллера и экшена из урла
	// если это не директория и не файл, и в директории есть файл index или файл с именем директории и расширением .php, то его имя - контроллер, остальное action
    private function getController(&$file, &$controller, &$action, &$args, &$tmpPath) {
        $route = (empty($_GET['route'])) ? '' : $_GET['route'];
        unset($_GET['route']);
        if (empty($route)) {
            $route = 'index';
        }
         
        // Получаем части урла
        $route = trim($route, '/\\');
        $parts = explode('/', $route);
        // Находим контроллер
        $cmd_path = $this->path; // /controllers/
		$tmpPath = '';
        foreach ($parts as $part) {// оперирует с копией массива
            $fullpath = $cmd_path . $part;
            // Проверка существования папки
            if (is_dir($fullpath)) {
                $cmd_path .= $part . '/';
				$lastDir = $part;
                array_shift($parts);
				$tmpPath .= $part.'/';
                continue;
            }
            // Находим файл
            if (is_file($fullpath . '.php')) {
                $controller = $part;
                array_shift($parts);
				$tmpPath .= $part;
                break;
            }
        }
 
        // если урле не указан контролер, то испольлзуем поумолчанию index
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
 
        // Получаем экшен
        $action = array_shift($parts);
        if (empty($action)) {
            $action = 'index';
        }
 
        $file = $cmd_path . $controller . '.php';
        $args = $parts;
    }
     
    function start() {
        // Анализируем путь
        $this->getController($file, $controller, $action, $args, $tmpPath);
         
        // Проверка существования файла, иначе 404
        if (is_readable($file) == false) {
            header("Status: 404 Not Found");
			header('Location:'.SITE_URL.'404');
			
        }
         
        // Подключаем файл
        include ($file);
 
        // Создаём экземпляр контроллера
        $class = 'Controller_' . $controller;
        $controller = new $class($this->registry, $tmpPath);
         
        // Если экшен не существует - 404
        if (is_callable(array($controller, $action)) == false) {
                        header("Status: 404 Not Found");
						header('Location:'.SITE_URL.'404');
        }
 
        // Выполняем экшен
        $controller->$action();
    }
}
?>