<?php
// ������ ���������:
$sitePath = realpath(dirname(__FILE__)).'/';
define ('SITE_PATH', $sitePath); // ���� � �������� ����� �����
define ('SITE_URL','http://zone.xedus.ru/');
 
// ��� ����������� � ��
define('DB_USER', 'COUNTERKILL');
define('DB_PASS', '123456');
define('DB_HOST', 'localhost');
define('DB_NAME', 'zone');
define('EVENTS_PER_PAGE', 10);
define('ANOMALY_ARTEFACTS_COEFF_LEFT', 0.5);
define('ANOMALY_ARTEFACTS_COEFF_RIGHT', 1.5);
define('LOCATION', 'russian');
?>