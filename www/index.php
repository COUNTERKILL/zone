<?php
// ��������� ������� �����

$start_time = microtime();

// ��������� ������� � ������������ (���������� ���������� ��������� ������ �������-������)

$start_array = explode(" ",$start_time);

// ��� � ���� ��������� �����

$start_time = $start_array[1] + $start_array[0]; 
// ������� ����������� ���� ������
error_reporting (E_ALL);
set_include_path('C:\Apache\stalker\www');
// ���������� ������
include('config.php');
include ('locations/'.LOCATION.'.php');
 
// ���������� ���� �����
include (SITE_PATH.'core/core.php');
 
// ��������� router
$router = new Router($registry);
// ���������� ������ � ������
$registry->set ('router', $router);
// ������ ���� �� ����� ������������.
$router->setPath (SITE_PATH . 'controllers');
// ��������� �������������
$router->start();
$end_time = microtime();
$SQLobj->Destroy();
$end_array = explode(" ",$end_time);

$end_time = $end_array[1] + $end_array[0];

// �������� �� ��������� ������� ���������

$time = $end_time - $start_time;

// ������� � �������� ����� (�������) ����� ��������� ��������

printf("�������� ������������� �� %f ������<br/>",$time); 
?>