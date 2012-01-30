<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Campaign_m extends CI_Model {

  private $db;
  public $error = array();
  public $active_campaign = array();

  function __construct()
  {
  parent::__construct();
   $this->load->library('ezsql_mysql');
	    $this->db = $this->ezsql_mysql;
	$this->active_campaign = $this->getActiveCampaign();
	 $this->load->model('setting_m');
  }
  
  public function getActiveCampaign()
  {
		$q = $this->db->get_row("SELECT * FROM campaign_group 
								  WHERE 
								  status = 'active' 
								  AND 
								  ( startdate <= '".date('Y-m-d h:i:s')."' AND enddate >= '".date('Y-m-d h:i:s')."' ) 
								  AND 
								  APP_APPLICATION_ID = '".$this->setting_m->get('APP_APPLICATION_ID')."'  
								  ORDER BY startdate DESC 
								  LIMIT 1",'ARRAY_A');						  
	    return 	$q ? $q : null;
  }
  
  public function getByAppId()
  {
    $q = $this->db->get_row("SELECT * FROM campaign_group WHERE APP_APPLICATION_ID = ".$this->setting_m->get('APP_APPLICATION_ID'),'ARRAY_A');
    return $q ? $q : null;
  }

  public function addCampaign($data)
  {
	$ok = $this->db->insert('campaign_group',$data);
	
	if($ok){
	 $gid = $this->db->last_insert_id();
	 
	 //Create media folder
	 if($gid){
	   if(!is_dir(CUSTOMER_IMAGE_DIR.$gid) && strtolower($data['allowed_media_type']) == "image"){
		 if(!mkdir(CUSTOMER_IMAGE_DIR.$gid, 0700)){
		  $this->error[] = "Create failed, cannot create Image Directory, Try Again!";
		  $this->removeCampaign($gid);
		  return false;
		 }
	   }
	   if(!is_dir(CUSTOMER_VIDEO_DIR.$gid)  && strtolower($data['allowed_media_type']) == "video"){
		 if(!mkdir(CUSTOMER_VIDEO_DIR.$gid, 0700)){
		  $this->error[] = "Create failed, cannot create Video Directory, Try Again!";
		  $this->removeCampaign($gid);
		  return false;		 
		 }
	   }		
	 }else{
	  $this->error[] = "Submission Failed, Try Again!";	 
	  return false;
	 }
	}else{
	  $this->error[] = "Submission Failed, Try Again!";	 
		return false;
	}
  }
  
  public function updateCampaign($data)
  {
	$ok = $this->db->update('campaign_group',$data,array('GID'=>$data['gid']));
	
	if($ok){
	 $gid = $data['gid'];
     return $gid;
	}else{
	   $this->error[] = "Submission Update has Failed, Try Again or Contact Web Administrator";
		return false;
	}
  }
  
  

  
  public function removeCampaign($gid)
  {
	$deleted = $this->db->query("DELETE FROM campaign_group WHERE GID = ".$gid);
	if($deleted){
		if(is_dir(CUSTOMER_IMAGE_DIR.$gid)){
			if(!rm_all_dir(CUSTOMER_IMAGE_DIR.$gid)){
			  $this->error[] = "Image Directory cannot be removed, contact Web Administrator!";
			  return false;
			}else{
			 return true;
			}
		}
		if(is_dir(CUSTOMER_VIDEO_DIR.$gid)){
			if(!rm_all_dir(CUSTOMER_VIDEO_DIR.$gid)){
			 $this->error[] = "Video Directory cannot be removed, contact Web Administrator!";
			  return false;
			}else{
			 return true;
			}
		}
	}
	$this->error[] = "Deleting has Failed, Try Again or Contact Web Administrator";
	return false;
  }
  
  public function retrieveCampaign($clauses = array() , $args = array())
	{
	   if(!is_array($args))
		  $args = array($args);

	if(!is_array($clauses))
	 parse_str($clauses,$clauses);

	$defaults = array('orderby' => 'GID', 'order' => 'DESC', 'fields' => '*');
	$args = array_merge( $defaults, $args );
	extract($args, EXTR_SKIP);
	$order = ( 'desc' == strtolower($order) ) ? 'DESC' : 'ASC';
    
	$sql = "SELECT ";
	$sql .= $fields." ";
	$sql .= "FROM campaign_group ";

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
  
  public function detailCampaign($gid) 
  {
   $sql  = "SELECT campaign_group.* ";
   $sql .= "FROM campaign_group ";
   $sql .= "WHERE campaign_group.GID = ".$gid;
   return $this->db->get_row($sql,'ARRAY_A');
  }
  
  public function setStatusCampaign($gid,$status)
  {
   return $this->db->update('campaign_group', array('status'=>$status), array('GID'=>$gid));
  }
}