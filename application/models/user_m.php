<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class User_m  extends CI_Model{

  private $db;
  public $error = array();

  function __construct()
  {
     parent::__construct();
		$this->load->library('ezsql_mysql');
	    $this->db = $this->ezsql_mysql;
  }
  
  public function add($data)
  {
	$ok = $this->db->insert('campaign_user',$data);
	
	if($ok){
	 $gid = $this->db->insert_id;
	 //Create media folder
	 if($gid){
	  return $gid;
	 }else{
	  $this->error[] = "Submission Failed, Try Again!";	 
	  return false;
	 }
	}else{
	  $this->error[] = "Submission Failed, Try Again!";	 
		return false;
	}
  }
  
  public function update($data)
  {
	$ok = $this->db->update('campaign_user',$data,array('user_ID'=>$data['user_ID']));
	
	if($ok){
	 $user_ID = $data['user_ID'];
     return $user_ID;
	}else{
	   $this->error[] = "Submission Update has Failed, Try Again or Contact Web Administrator";
		return false;
	}
  }
  
  

  
  public function remove($gid)
  {
	$deleted = $this->db->query("DELETE FROM campaign_user WHERE user_ID = ".$gid);
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

	$defaults = array('orderby' => 'user_ID', 'order' => 'ASC', 'fields' => '*');
	$args = array_merge( $defaults, $args );
	extract($args, EXTR_SKIP);
	$order = ( 'desc' == strtolower($order) ) ? 'DESC' : 'ASC';
    
	$sql = "SELECT ";
	$sql .= $fields." ";
	$sql .= "FROM campaign_user ";

		foreach ($clauses as $key => $value){
		  if(is_array($value)){
		   $where[] = $key." IN (".implode(",",$value).") ";
		  }else{
		   $where[] = $key." = ".$value;
		  }
		}
		
		if(count(@$where)>0)
			$sql .= " WHERE ".implode(" AND ",$where);
		
		$sql .= " ORDER BY ".$orderby." ".$order;

		if($limit_number && $limit_offset)
			$sql .= " LIMIT ".$limit_offset.",".$limit_number;
		elseif($limit_number)
			$sql .= " LIMIT ".$limit_number;

	  return $this->db->get_results($sql,'ARRAY_A');

	}
  
  public function detail($uid) 
  {
   $sql  = "SELECT campaign_user.* ";
   $sql .= "FROM campaign_user ";
   $sql .= "WHERE campaign_user.user_ID = ".$uid;
   return $this->db->get_row($sql,'ARRAY_A');
  }
  
  public function setStatus($gid,$status)
  {
   return $this->db->update('campaign_user', array('user_status'=>$status), array('user_ID'=>$gid));
  }
}