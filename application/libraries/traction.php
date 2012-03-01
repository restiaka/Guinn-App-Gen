<?php
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
 
 public $CI;
 
	function __construct($config = null)
	{
	 $this->CI = &get_instance();
	 $this->CI->load->model('setting_m');
	 
	 
	 	   $traction = array(
							'USERID'  =>  $this->CI->setting_m->get('TRAC_USERID'),
							'PASSWORD' =>  $this->CI->setting_m->get('TRAC_PASSWORD'),
							'ENDPOINTID' =>  $this->CI->setting_m->get('TRAC_ENDPOINTID')
						 );
   $config = $config ? $config : $traction;
	
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
	  if (!isset($ch)) {
		$ch = curl_init();
	  }
	  

		$opts = self::$CURL_OPTS;
		$opts[CURLOPT_URL] = $url;
		if($params){
		   /**
		   		$querystring = 'USERID='.$params['USERID'].'&'.
						'PASSWORD='.$params['PASSWORD'].'&'.
						'ENDPOINTID='.$params['ENDPOINTID'].'&'.
						'CUSTOMER='.$params['CUSTOMER'].'&'.
						'MATCHKEY='.$params['MATCHKEY'].'&'.
						'MATCHVALUE='.$params['MATCHVALUE'];
		   /**/
			$opts[CURLOPT_POSTFIELDS] = http_build_query($params);
		}
		
		//dg($opts);
		//exit;
		curl_setopt_array($ch, $opts);
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		//dg($result);
		//exit;
		
		return self::dataTRAC($result);;
	}
	
	private function httpPost($myUrl,$myDataArray) {
	/* Private method used to send request and retrieve response from Traction. */
		// get url parts..
		$url = preg_replace("@^http://@i", "", $myUrl);  // remove transport protocol
		$host = substr($url, 0, strpos($url, "/"));  // host of web application
		$uri = strstr($url, "/");  // path for web application
		$port = (int) substr($uri, strpos($uri, ":") + 1);  // get port from myUrl.
		if (!($port > 0)) $port = 80;  // if no port given in myUrl then default to port 80.
		
		//create request body..
		$myRequestBody = "";
		foreach ($myDataArray as $key => $val) {
			if (!empty($myRequestBody))
				$myRequestBody .= "&";
			$myRequestBody .= $key . "=" . urlencode($val);
		}
		$myContentLength = strlen($myRequestBody)+100;
		// create request header..
		$myRequestHeader = "POST " . $uri . " HTTP/1.0 \r\n";
		$myRequestHeader .= "Host: " . $host . "\r\n";
		$myRequestHeader .= "User-Agent: MassMediaStudios_Traction_Client\r\n";
		$myRequestHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$myRequestHeader .= "Content-Length: " . $myContentLength . "\r\n\r\n";
		$myRequestHeader .= $myRequestBody . "\r\n";
		
/* 		dg($myRequestHeader);
		dg($host, $port, $errno, $errstr);
		exit;
 */		
 //connect to server..
		$socket = fsockopen($host, $port, $errno, $errstr);
		if (!$socket) {
			// socket failed, return error details..
			$result["errno"] = $errno;
			$result["errstr"] = $errstr;
			
			return $result;
		}
		
		// pass data through socket..
		fputs($socket, $myRequestHeader);
		
		$result = "";
		while (!feof($socket)) {
			// get result..
			$result[] = fgets($socket, 4096);
		}
		
		fclose($socket); // close socket.
		
		return $result; // return successful socket result
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