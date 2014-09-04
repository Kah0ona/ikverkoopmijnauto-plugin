<?php 

class AdsModel {
	protected $options;
	function __construct($options){
		$this->options = options;
	}
	public function getData(){
		$url = BASE_URL_BACKEND.'/carads';
		$jsonString = $this->curl_fetch($url);
		$obj = json_decode($jsonString);
		return $obj;
	}

	protected function curl_fetch($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$json = curl_exec($ch);
		curl_close($ch);
		return $json;
	}



	public function getRandomAdText($num = 3){

		$arr = array(
			'Snel geholpen met de verkoop van mijn _MODEL_, top!',
			'Binnen een dag van mijn _BRAND_ _MODEL_ af!',
			'Fijne site.',
			'Erg handig!',
			'Dit kan ik aanraden, werkt heel eenvoudig. Mijn _MODEL_ was zo weg!',
			'Kwam hier via een kennis. Goed geholpen met mijn beschadigde _MODEL_',
			'Bedankt weer!', //todo add more
		);

		shuffle($arr);
		for($i = 0 ; $i < $num; $i++){
			$ret[] = $arr[$i];
		}
		return $ret;
	}

	public function replacePlaceHolders($model, $brand, $string){
		$ret = str_replace('_MODEL_', $model, $string);
		$ret = str_replace('_BRAND_', $brand, $ret);
		return $ret;
	}	
	
}
