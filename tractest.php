<pre>
<?php

$traction = new Traction(array('USERID' => 'fbdev',
								'PASSWORD' => 'th1nkw3b',
								'ENDPOINTID' => '17259'));
 $r =	$traction->api('AddCustomer',array('CUSTOMER' => 'FIRSTNAME|adadfadfLASTNAME|asfasfEMAIL|kh411d@yahoo.com3014098|103031180|23424234',
'MATCHKEY' => 'E',
'MATCHVALUE' => 'kh411d@yahoo.com'));

 print_r($r);
 phpinfo();
 
Class Traction {
 
 public static $CURL_OPTS = array(
								CURLOPT_CONNECTTIMEOUT => 10,
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_TIMEOUT        => 60,
								CURLOPT_POST => 1,
								CURLOPT_HEADER => 1
							  );
 public static $DOMAIN_API = "http://int.api.tractionplatform.com/ext/";  
 
 public static $ALLOWED_PARAMS = array("USERID" => 1,
										"PASSWORD" => 1,
										"ENDPOINTID" => 1,
										"CUSTOMER" => 1,
										"MATCHKEY" => 1,
										"MATCHVALUE" => 1,
										"ATTRID1" => 1,
										"ATTRID2" => 1,
										"ATTRID3" => 1,
										"ATTRID4" => 1,
										"ATTRID5" => 1,
										"ATTRID6" => 1,
										"ATTRID7" => 1,
										"ATTRID8" => 1,
										"ATTRID9" => 1,
										"ATTRID10" => 1,
										"TEST" => 1);
 public static $CONFIG;
	function __construct($config)
	{
	
	 if(!$config["USERID"] ||
		!$config["PASSWORD"] ||
		!$config["ENDPOINTID"]){
	   die("Traction Credential is Missing!");
	 }else{
	   self::$CONFIG = $config;
	 }
	}
	
	public function api($method,$params)
	{
	  if($call = $this->getApiMethod($method)){
	  
	   return $this->makeRequest($this->getUrl($call),$this->prepareParams($params));
	  }
	  
	  return NULL;
	}
	
	
	protected function getApiMethod($method)
	{
	 $calls = array(
	  "RetrieveCustomer"=>1,
	  "AddCustomer"=>1,
	  "CustomerLogin"=>1
	 );
	 if(isset($calls[$method])){
	  return $method;
	 }else{
	  return null;
	 }
	}
	
	
	private function makeRequest($url,$params)
	{
	  $ch = curl_init();
	  
		$opts = self::$CURL_OPTS;
		$opts[CURLOPT_URL] = $url;
		if($params){
			$opts[CURLOPT_POSTFIELDS] = http_build_query($params);
		}
		curl_setopt_array($ch, $opts);

		$result = curl_exec($ch);
		curl_close($ch);
		
		return self::dataTRAC($result);;
	}
	
	protected function getUrl($path='') {
		$url = self::$DOMAIN_API;
		if ($path) {
		  if ($path[0] === '/') {
			$path = substr($path, 1);
		  }
		  $url .= $path;
		}
		
		return $url;
	}
	
	private function prepareParams($params){
	  //Checking allowed
	  if(count(array_diff_key($params,self::$ALLOWED_PARAMS))<=0){
	   $prep = array_merge(self::$CONFIG,$params);
	   return $prep;
	  }
	  return null;
	}

	public static function formatCustData($data)
	{
	 $temp = array();
	 foreach($data as $key => $value){
	   $temp[] = $key."|".$value;
	 }
	 return implode(chr(31),$temp).chr(31);
	}
	
	public static function dataTRAC($response)
	{
		$parts = explode("\r\n\r\n", $response);
		$header = explode("\n", $parts[0]);
		$t = array();
		 foreach($header as $v){
			if(strpos($v,"TRAC")!==false){
			  $kv = explode(":",$v);	 
			  $t[$kv[0]] = trim($kv[1]);
			}
		 }
		$t['result'] = $parts[1]; 
		return $t;
	}
}