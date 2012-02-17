<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class App_m extends CI_model {
  protected $db;
    function __construct()
	 {
	    parent::__construct();
		$this->load->library('ezsql_mysql');
	    $this->db = $this->ezsql_mysql;
	 }
  
	public function add($data)
	  {
		$ok = $this->db->insert('campaign_app',$data);
		return $ok;
	  }
	  
	public function dispatch($appid)
	{
		$ok = $this->db->update('campaign_group',array('APP_APPLICATION_ID'=>''),array('APP_APPLICATION_ID'=>$appid));
		
		return $ok ? true : false;
	}
	  
	public function update($data)
	  {
		$ok = $this->db->update('campaign_app',$data,array('APP_APPLICATION_ID'=>$data['APP_APPLICATION_ID']));
		
		if($ok){
		 $gid = $data['APP_APPLICATION_ID'];
		 return $gid;
		}else{
		   $this->error[] = "Submission Update has Failed, Try Again or Contact Web Administrator";
			return false;
		}
	  }  
	 
	public function remove($gid)
	{
		$deleted = $this->db->query("DELETE FROM campaign_app WHERE APP_APPLICATION_ID = ".$gid);

		if(!$deleted){
		$this->error[] = "Deleting has Failed, Try Again or Contact Web Administrator";
		return false;
		}else{
		return true;
		}
		
	}	 
	  
	public function retrieve($clauses = array() , $args = array())
	{
	   if(!is_array($args))
		  $args = array($args);

	if(!is_array($clauses))
	 parse_str($clauses,$clauses);

	$defaults = array('orderby' => 'APP_APPLICATION_ID', 'order' => 'ASC', 'fields' => '*');
	$args = array_merge( $defaults, $args );
	extract($args, EXTR_SKIP);
	$order = ( 'desc' == strtolower($order) ) ? 'DESC' : 'ASC';
    
	$sql = "SELECT ";
	$sql .= $fields." ";
	$sql .= "FROM campaign_app ";

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
	
  public function detailApp($gid) 
  {
   $sql  = "SELECT campaign_app.* ";
   $sql .= "FROM campaign_app ";
   $sql .= "WHERE campaign_app.APP_APPLICATION_ID = ".$gid;
   return $this->db->get_row($sql,'ARRAY_A');
  }
	
  public function setStatus($gid,$status)
  {
   return $this->db->update('campaign_app', array('status'=>$status), array('APP_APPLICATION_ID'=>$gid));
  }
}