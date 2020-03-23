<?php

class LogParser {

	function __construct()
	{
		print 'Construct ' . __CLASS__ ;
	}

	function __destruct() {
       print '<br>Destruct ' . __CLASS__ ;
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
			echo '<br>============================';
			echo '<br> file is exist';
			echo '<br>============================';
		} else {
			echo '<br>============================';
			echo '<br> ERROR: file is not exist!!!';
			echo '<br>============================';
			die();
		}
		return $result;
	}

	/**
	 * Парсинг логов
	 * @param array $text
	 * @return array $result
	 */
	public function parseText($text) {
		$urls = [];
		$codes = [];
		$crawlers = [];
		$traffic = null;
		$result = [
			'views' => null,
			'urls' => null,
			'traffic' => null,
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
			$traffic += $codeAndTraffic[1];
		}
		$result['crawlers'] = $this->getCrawlers($crawlers);
		$result['statusCodes'] = $this->parseCodes($codes);
		$result['traffic'] = $traffic;
		$result['views'] = count($urls);
		$result['urls'] = count(array_unique($urls));

		return json_encode($result);
	}

	/**
	 * Парсинг массива кодов статусов
	 * @param array $codes
	 * @return array $result
	 */
	public function parseCodes($codes) {
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
	public function getCrawlers($data) {
		$result = [
			'Google' 	=> 	null, 
			'Yandex' 	=> 	null, 
			'Bing' 		=> 	null, 
			'Baidu' 	=> 	null
		];

		foreach ($result as $crawler => $count) {
			foreach ($data as $str) {
				strpos($str, $crawler) ? $result[$crawler]++ : '';
			}
		}

		return $result;
	}
}




