<?php echo "<pre>";

/*
	Имеется обычный http access_log файл.
	Требуется написать PHP скрипт, обрабатывающий этот лог и выдающий информацию о нём в json виде.
	Требуемые данные: 
	- количество хитов/просмотров, 
	- количество уникальных url, 
	- объем трафика, 
	- количество строк всего, 
	- количество запросов от поисковиков, 
	- коды ответов.
*/

ini_set('date.timezone', 'Europe/Moscow');
require_once('classes/LogParser.php');

$fileName = 'logs\access.log';
$logParser = new LogParser();

$text = $logParser->readTheFile($fileName);
$result = $logParser->parseText($text);

empty($result) ? 
	file_put_contents('logs/error.log', date('Y-m-d H:m:s') . " - - parse error - - \n", FILE_APPEND) :
	file_put_contents('result.json', $result);

if (file_exists('result.json')) {
	print_r(file_get_contents('result.json'));
} else {
	print_r('error! file is not exist');
}