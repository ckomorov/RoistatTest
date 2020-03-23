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

require_once('classes/LogParser.php');

$fileName = 'logs\access.log';
$logParser = new LogParser();

$text = $logParser->readTheFile($fileName);
$result = $logParser->parseText($text);
print_r($result);

isset($result) ? file_put_contents('result.json', $result) : print_r('ERROR!');