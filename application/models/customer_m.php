<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Customer_m extends CI_Model{

  private $db;
  public $error = array();
  private $traction;

  function __construct()
  {
    parent::__construct();
    $this->load->library('ezsql_mysql');
	    $this->db = $this->ezsql_mysql;
	$this->load->library('traction');
	$this->load->model('setting_m');
	
  }
  
  public function add($data)
  { 
    $this->load->library('facebook');
	 $this->error = array();
	$r = $this->traction->api('AddCustomer',array(
							   "CUSTOMER" => $this->traction->formatCustData($data),
							   "MATCHKEY" =>'E',
							   "MATCHVALUE" =>$data['EMAIL']
							));		
	
	if(!isset($r['TRAC-RESULT'])){
	  $this->error[] = "Submission Failed (TResult), Try Again!";	 
	  return false;
	}
	
	if(isset($r['TRAC-ERROR']) && $r['TRAC-RESULT'] !== 0){
	  $this->error[] = "Submission Failed (TError), Try Again!";	 
	  return false;						
    }
	
	
	$db_data['uid'] = $this->facebook->getUser();
	$db_data['email'] = $data['EMAIL'];
	$db_data['email_active'] = $data['EMAIL'];
	
	foreach (array_keys($db_data) as $v)$update[] = $v." = values(".$v.")";
	$extra_sql = " ON DUPLICATE KEY UPDATE ".implode(',',$update); 
	
	$ok = $this->db->insert('campaign_customer',$db_data,$extra_sql);
	
	
	
	if($ok){
	 if(!$this->isAppAuthorized()){
	   $this->addAppAuthorization();
	 }
	}else{
	  $this->error[] = "Submission Failed, Try Again!";	 
		return false;
	}
  }
  
 
  public function registerRequire(){
   $this->load->library('facebook');
  if($this->facebook->getUser() && isExtPermsAllowed()){
	if(!$this->isRegistered()){
	  $ssl = $this->setting_m->get('SITE_URL_SSL').'index.php/'.menu_url('register',true);	  
	  header("Location: ".$ssl);
	  //redirect($ssl);
	  exit;
	}
	}
  }  
  
  public function addAppAuthorization(){
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
		
		if(count($where)>0)
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
	Result for EMAILOPT AND SMSOPT
		I = opt-in
		O = opt-out
		B = opt-out blocked
		U = undefined (default)	
	Result for ACTIVE
		T – active
		F – not active	
  /**/
  public function detailTRAC($email,$selection = NULL) 
  {
	   if(!is_array($selection)){
		$selection = array('FIRSTNAME',
							'LASTNAME',
							'MOBILE',
							'EMAIL',
							'EMAILOPT',
							'ACTIVE',
							$this->setting_m->get('TRAC_ATTR_FBUID'),
							$this->setting_m->get('TRAC_ATTR_GID'));
	   } 
	   
		$i=0;
		foreach($selection as $v){
			$ATTRID["ATTRID".++$i] = $v;
		}
	   
	   $params = array_merge(array("MATCHKEY"=>'E',"MATCHVALUE"=>$email),$ATTRID);  
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
							elseif($value == 'U')$value = 'undefined';
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
  
  public function setStatus($gid,$status)
  {
   return $this->db->update('campaign_customer', array('status'=>$status), array('uid'=>$gid));
  }
  
  public function isRegistered()
  { 
   $this->load->library('facebook');
    if(!$uid = $this->facebook->getUser()) return false;
    $fromDB = $this->detail($uid); 
	
	if($fromDB){
	 //Checking authorized app
	 if(!$this->isAppAuthorized()){
	   $this->addAppAuthorization();
	 }
	 
	 //Traction check
	 $trac = $this->detailTRAC($fromDB['email']);
	 if($trac)return true; else return false;
    }else{
	  return false;
	}
  }
}