<?php
// считываем текущее врем€

$start_time = microtime();

// раздел€ем секунды и миллисекунды (станов€тс€ значени€ми начальных ключей массива-списка)

$start_array = explode(" ",$start_time);

// это и есть стартовое врем€

$start_time = $start_array[1] + $start_array[0]; 
// включим отображение всех ошибок
error_reporting (E_ALL);
set_include_path('C:\Apache\stalker\www');
// подключаем конфиг
include('config.php');
include ('locations/'.LOCATION.'.php');
 
// подключаем €дро сайта
include (SITE_PATH.'core/core.php');
 
// «агружаем router
$router = new Router($registry);
// записываем данные в реестр
$registry->set ('router', $router);
// задаем путь до папки контроллеров.
$router->setPath (SITE_PATH . 'controllers');
// запускаем маршрутизатор
$router->start();
$end_time = microtime();
$SQLobj->Destroy();
$end_array = explode(" ",$end_time);

$end_time = $end_array[1] + $end_array[0];

// вычитаем из конечного времени начальное

$time = $end_time - $start_time;

// выводим в выходной поток (броузер) врем€ генерации страницы

printf("—траница сгенерирована за %f секунд<br/>",$time); 
?>