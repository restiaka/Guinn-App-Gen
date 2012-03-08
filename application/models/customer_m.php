<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Customer_m extends CI_Model{

  private $db;
  public $error = array();
  protected $traction_enabled;

  function __construct()
  {
    parent::__construct();
    $this->load->library('ezsql_mysql');
	    $this->db = $this->ezsql_mysql;
	$this->load->library('traction');
	$this->traction_enabled = $this->config->item('TRAC_API_ENABLED');
  }
  
  public function add($data)
  { 
    $this->load->library('facebook');
	 $this->error = array(); 
	 
	if($this->traction_enabled){
	    	$data[$this->config->item('TRAC_ATTR_GID')] = $data['GID'];
			$data[$this->config->item('TRAC_ATTR_MOBILE2')] = $data['MOBILE'];//TRAC_ATTR_MOBILE2
			unset($data['MOBILE'],$data['GID']);
	
	
		$r = $this->traction->api('AddCustomer',array(
								   "CUSTOMER" => $this->traction->formatCustData($data),
								   "MATCHKEY" =>'E',
								   "MATCHVALUE" =>$data['EMAIL']
								));		
		if(isset($data['SUBSCRIPTIONID1'])){
			  		$s = $this->traction->api('MultiSubscribe',array(
								   "SUBSCRIPTIONID1" => $data['SUBSCRIPTIONID1'],
								   "MATCHKEY" =>'E',
								   "MATCHVALUE" =>$data['EMAIL']
								));	
		}
		
		if(!isset($r['TRAC-RESULT'])){
		  $this->error[] = "Submission Failed (TResult), Try Again!";	 
		  return false;
		}
		
		if(isset($r['TRAC-ERROR']) && $r['TRAC-RESULT'] !== 0){
		  $this->error[] = "Submission Failed (TError), Try Again!";	 
		  return false;						
		}
		
		if(isset($r['TRAC-CUSTOMERID']) && !empty($r['TRAC-CUSTOMERID'])){
		  $db_data['customer_id'] = $r['TRAC-CUSTOMERID']; 
		}else{
		  $this->error[] = "Submission Failed (Unknown Customer), Try Again!";	
			return false;
		}
	}else{

	foreach (array_keys($data) as $v)$trac_update[] = $v." = values(".$v.")";
	$trac_extra_sql = " ON DUPLICATE KEY UPDATE ".implode(',',$trac_update); 
	
	 $ok = $this->db->insert('campaign_customer_traction',$data,$trac_extra_sql);
	 if($this->db->result){
	  $db_data['customer_id'] = $this->db->last_insert_id() ? $this->db->last_insert_id() : $this->db->get_var("SELECT customer_id FROM campaign_customer_traction WHERE EMAIL = '".$data['EMAIL']."'");
	 }else{
	   return false;
	 }
	
	}
	
	$db_data['uid'] = $this->facebook->getUser(); 
	
	foreach (array_keys($db_data) as $v)$update[] = $v." = values(".$v.")";
	$extra_sql = " ON DUPLICATE KEY UPDATE ".implode(',',$update); 
	
	$ok = $this->db->insert('campaign_customer',$db_data,$extra_sql);
	
	if($this->db->result){
	 if(!$this->isAppAuthorized()){
	   $this->addAppAuthorization();
	 } 
	}else{
	  $this->error[] = "Submission Failed, Try Again!";	 
		return false;
	}
	
	return true;
  }
    
 
  public function registerRequire(){
   $this->load->model('setting_m');
   $this->load->library('facebook');
   
  if($this->facebook->getUser() && isExtPermsAllowed()){
	if(!$this->isRegistered()){
	  $ssl = $this->setting_m->get('SITE_URL').'index.php/'.menu_url('register',true);	  
	  header("Location: ".$ssl);
	  //redirect($ssl);
	  exit;
	}
	}
  }  
  
  public function addAppAuthorization(){
   
   $this->load->model('setting_m');
   $this->load->library('facebook');
   
   	$authorization_data['uid'] = $this->facebook->getUser();
	$authorization_data['authorized_date'] = date("Y-m-d h:i:s");
	$authorization_data['authorized'] = 1;
	$authorization_data['APP_APPLICATION_ID'] = $this->setting_m->get('APP_APPLICATION_ID');
	$authorization_data['access_token'] = $this->facebook->getAccessToken();
	
	foreach (array_keys($authorization_data) as $v)$update[] = $v." = values(".$v.")";
	$extra_sql = " ON DUPLICATE KEY UPDATE ".implode(',',$update); 
	
	$authorize_ok = $this->db->insert('campaign_customer_fbauthorization',$authorization_data,$extra_sql);
	
	return $authorize_ok;
  }
  
  public function isAppAuthorized(){
   $this->load->model('setting_m');
   $var = $this->db->get_var('SELECT COUNT(*) FROM campaign_customer_fbauthorization WHERE APP_APPLICATION_ID = '.$this->setting_m->get('APP_APPLICATION_ID').' AND uid = '.$this->facebook->getUser());
   return $var ? true : false;
  }
  
  public function update($data)
  { 
	$this->error = array();
	$ok = $this->db->update('campaign_customer',$data,array('uid'=>$data['uid']));
	
	if($ok){
	 $user_ID = $data['uid'];
     return $user_ID;
	}else{
	   $this->error[] = "Submission Update has Failed, Try Again or Contact Web Administrator";
		return false;
	}
  }

  
  public function remove($gid)
  { 
    $this->error = array();
	$deleted = $this->db->query("DELETE FROM campaign_customer WHERE uid = ".$gid);
	if($deleted){
		return true;
	}
	$this->error[] = "Deleting has Failed, Try Again or Contact Web Administrator";
	return false;
  }
  
  public function retrieve($clauses = array() , $args = array())
	{

	   if(!is_array($args))
		  $args = array($args);

	if(!is_array($clauses))
	 parse_str($clauses,$clauses);

	$defaults = array('orderby' => 'uid', 'order' => 'ASC', 'fields' => '*');
	$args = array_merge( $defaults, $args );
	extract($args, EXTR_SKIP);
	$order = ( 'desc' == strtolower($order) ) ? 'DESC' : 'ASC';
    
	$sql = "SELECT ";
	$sql .= $fields." ";
	$sql .= "FROM campaign_customer ";

		foreach ($clauses as $key => $value){
		  if(is_array($value)){
		   $where[] = $key." IN (".implode(",",$value).") ";
		  }else{
		   $where[] = $key." = ".$value;
		  }
		}
		
		if(isset($where) && count($where)>0)
			$sql .= " WHERE ".implode(" AND ",$where);
		
		$sql .= " ORDER BY ".$orderby." ".$order;

		if($limit_number && $limit_offset)
			$sql .= " LIMIT ".$limit_offset.",".$limit_number;
		elseif($limit_number)
			$sql .= " LIMIT ".$limit_number;

	  return $this->db->get_results($sql,'ARRAY_A');

	}
  
  public function detail($uid,$gid = null) 
  {
   $sql  = "SELECT campaign_customer.* ";
   $sql .= "FROM campaign_customer ";
   /*if($gid){
	$sql .= "INNER JOIN campaign_customer_regs ON campaign_customer.uid = campaign_customer_regs.uid ";
   }*/
   $sql .= "WHERE campaign_customer.uid = ".$uid;
   /*if($gid){
	$sql .= " AND campaign_customer_regs.gid = ".$gid;
   }*/
   return $this->db->get_row($sql,'ARRAY_A');
  }
  
  /**
     Return Success: Array 
					[TRAC-RESULT] => 0
					[TRAC-ATTRVAL2] 
					[TRAC-CUSTOMERID] 
					[TRAC-ATTRVAL4] 
					[TRAC-ATTRVAL3]
					[TRAC-ATTRVAL1]
					[result]
     Return Failed: Array
					[TRAC-RESULT] => 7
					[TRAC-ERROR] => Customer Not Found
					[result] =>
	 Available Value for $selection
		FIRSTNAME
		LASTNAME
		TITLE
		MOBILE
		EMAIL
		PASSWORD
		SMSOPT
		EMAILOPT
		EXTUSERID
		ACTIVE
		XXXX(The numeric Attribute ID of the Custom Attribute which is to be returned)
	 Available for Match Key
		E = Email Address
		M = Mobile Number
		X = External User ID
		C = Traction internal ID – can only be used to update existing customers. The internal ID is returned in most API responses.	
	Result for EMAILOPT AND SMSOPT
		I = opt-in
		O = opt-out
		B = opt-out blocked
		U = undefined (default)	
	Result for ACTIVE
		T – active
		F – not active	
  /**/
  public function detailTRAC($matchvalue,$selection = NULL,$matchkey = "E") 
  {
    if(!$this->traction_enabled) return array('fields' => array());
   
   $this->load->model('setting_m');
	   if(!is_array($selection)){
		$selection = array('FIRSTNAME',
							'LASTNAME',
							'MOBILE',
							'EMAIL',
							'EMAILOPT',
							'ACTIVE',
							$this->setting_m->get('TRAC_ATTR_FBUID'),
							$this->setting_m->get('TRAC_ATTR_GID'),
							$this->setting_m->get('TRAC_ATTR_MOBILE2'));
	   } 
	   
		$i=0;
		foreach($selection as $v){
			$ATTRID["ATTRID".++$i] = $v;
		}
	   
	   $params = array_merge(array("MATCHKEY"=>$matchkey,"MATCHVALUE"=>$matchvalue),$ATTRID);  
	   $r = $this->traction->api('RetrieveCustomer',$params);	
	   
	   if(!isset($r['TRAC-RESULT'])) return null;
	   if(isset($r['TRAC-ERROR']) && $r['TRAC-RESULT'] !== 0)return null;
	   
	   
	   foreach($r as $key => $value){
		 if(stripos($key,'TRAC-ATTRVAL')!== FALSE){
			$id = str_replace('TRAC-ATTRVAL','',$key);
			switch($ATTRID["ATTRID".trim($id)]){
			 case 'EMAILOPT':
			 case 'SMSOPT': if($value == 'I')$value = 'opt-in';
							elseif($value == 'O')$value = 'opt-out';
							elseif($value == 'B')$value = 'opt-out blocked';
							elseif($value == 'U')$value = 'Not Set';
							else $value = 'unknown';break;
			 case 'ACTIVE' : if($value=='T')$value = 'Active';
							 elseif($value=='F')$value = 'Not Active';
							 else $value='unknown';break;
			}
			$data[$ATTRID["ATTRID".trim($id)]] = $value;
		 }
	   }
	   $r['fields'] = $data;
	   
	   return $r;
  }
  
  public function detailTRAC_dump($matchvalue,$selection = NULL,$matchkey = "E") 
  {
    $r = array();
    switch($matchkey){
	  case 'E' : $clause = "EMAIL = '".$matchvalue."'"; break;
	  case 'C' : $clause = "CUSTOMER_ID = ".$matchvalue; break;
	}
	$r['fields'] = $this->db->get_row("SELECT * FROM campaign_customer_traction WHERE ".$clause,'ARRAY_A');
	return $r;
  }
  
  public function setStatus($gid,$status)
  {
   return $this->db->update('campaign_customer', array('status'=>$status), array('uid'=>$gid));
  }
  
  public function getCampaign_byCustomerApp($facebook_uid)
  {
   $sql = "SELECT
			campaign_group.title,
			campaign_group.GID
			FROM
			campaign_customer_fbauthorization
			Inner Join campaign_group ON campaign_customer_fbauthorization.APP_APPLICATION_ID = campaign_group.APP_APPLICATION_ID
			WHERE
			campaign_customer_fbauthorization.uid = $facebook_uid";
	return $this->db->get_results($sql,'ARRAY_A');		
  }
  
  public function getCampaign_byCustomerMedia($facebook_uid,$clause = array())
  {
   
   $sql = "SELECT
			campaign_group.GID,
			campaign_group.title,
			campaign_media.media_winner
			FROM
			campaign_group
			Inner Join campaign_media ON campaign_group.GID = campaign_media.GID
			Inner Join campaign_media_owner ON campaign_media.media_id = campaign_media_owner.media_id
			WHERE
			campaign_media_owner.uid = $facebook_uid";
	return $this->db->get_results($sql,'ARRAY_A');				
  }
  
  public function isRegistered()
  { 
   $this->load->library('facebook');
    if(!$uid = $this->facebook->getUser()) return false;
    $fromDB = $this->detail($uid); 
	
	if($fromDB){
	 //Checking authorized app
	 if(!$this->isAppAuthorized()){
	   return false;
	 }
	 
	 //Traction check
	 if($this->traction_enabled){
		 $trac = $this->detailTRAC($fromDB['customer_id'],null,'C');
		 if($trac)return true; else return false;
	 }else{
	   $trac = $this->detailTRAC_dump($fromDB['customer_id'],null,'C');
	   if($trac)return true; else return false;
	 }
	 
	 return true;
    }else{
	  return false;
	}
  }
}