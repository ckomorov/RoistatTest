<?php

class LogParser {

	function __construct() {
		
	}

	function __destruct() {
       
   	}

	/**
	* Чтение текста из файла логов
	* @param string $path
	* @return array $result
	*/
	public function readTheFile($fileName) {
		$result = [];
		$directory = explode('\classes', __DIR__)[0];
		$path = $directory . '\\' . $fileName;
		if (file_exists($path)) {
			$file = fopen($path, 'r');
			while (!feof($file))
			{
				$result[] = trim(fgets($file));
			}
			fclose($file);
		} else {
			echo '<br>============================';
			echo '<br> ERROR: file is not exist!!!';
			echo '<br>============================<br>';
			file_put_contents('logs/error.log', print_r(date("Y-m-d H:m:s") . " - - read file error - - \n", 1), FILE_APPEND);
			die();
		}
		return $result;
	}

	/**
	 * Парсинг логов
	 * @param array $text
	 * @return JSON $result
	 */
	public function parseText($text) {
		$urls = [];
		$codes = [];
		$crawlers = [];
		$traffic = 0;
		$result = [
			'views' => 0,
			'urls' => 0,
			'traffic' => 0,
			'crawlers' => [],
			'statusCodes' => []
		];

		foreach ($text as $str) {
			$tmp = [];
			$tmp = explode(' - - ', $str);
			$urls[] = $tmp[0];
			$params = explode("\" ", $tmp[1]);
			$crawlers[] = $params[2];
			$codeAndTraffic = explode(" ", $params[1]);
			$codes[] = $codeAndTraffic[0];
			$codeAndTraffic[0] == 200 ? $traffic += $codeAndTraffic[1] : '';	
		}
		$result['crawlers'] = $this->getCrawlers($crawlers);
		$result['statusCodes'] = $this->parseCodes($codes);
		$result['traffic'] = $traffic;
		$result['views'] = count($urls);
		$result['urls'] = count(array_unique($urls));

		return ($result['views'] == 0) ? 0 : json_encode($result, JSON_PRETTY_PRINT);
	}

	/**
	 * Парсинг массива кодов статусов
	 * @param array $codes
	 * @return array $result
	 */
	private function parseCodes($codes) {
		$result = [];
		foreach ($codes as $code) {
			array_key_exists($code, $result) ? $result[$code]++ : $result[$code] = 1;
		}
		return $result;
	}

	/**
	 * Поиск количества запросов от поисковиков
	 * @param array $data
	 * @return array $result
	 */
	private function getCrawlers($data) {
		$result = [
			'Google' 	=> 	0, 
			'Yandex' 	=> 	0, 
			'Bing' 		=> 	0, 
			'Baidu' 	=> 	0
		];

		foreach ($result as $crawler => $count) {
			foreach ($data as $str) {
				strpos($str, $crawler) ? $result[$crawler]++ : '';
			}
		}

		return $result;
	}
}




