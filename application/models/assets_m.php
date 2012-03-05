<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Assets_m extends CI_Model {

  private $db;
  public $error = array();
  
  function __construct()
  {
   parent::__construct();
   $this->load->library('ezsql_mysql');
	    $this->db = $this->ezsql_mysql;
  }

  public function addAssets($data)
  {
 
	$ok = $this->db->insert('campaign_assets',$data);
	
	if($ok){
	 return $this->db->last_insert_id(); 
	}
	return false;
  }
  
  public function updateAssets($data)
  {
	$ok = $this->db->update('campaign_assets',$data,array('asset_id'=>$data['asset_id']));
	
	if($ok){
	 $asset_id = $data['asset_id'];
     return $asset_id;
	}else{
	   $this->error[] = "Submission Update has Failed, Try Again or Contact Web Administrator";
		return false;
	}
  }

  
  public function removeAssets($asset_id)
  {
    if($assets = $this->detailAsset($asset_id)){
		$deleted_related_assets = $this->db->query("DELETE FROM campaign_group_assets WHERE asset_id = ".$asset_id);
		$deleted = $this->db->query("DELETE FROM campaign_assets WHERE asset_id = ".$asset_id);
	
		if($deleted){
		    @unlink(CAMPAIGN_ASSETS_DIR.$assets['asset_basename']);
			return true;
		}
	}
	return false;
  }
  
  public function retrieveAssets($clauses = array() , $args = array())
	{
	   if(!is_array($args))
		  $args = array($args);

		if(!is_array($clauses))
		 parse_str($clauses,$clauses);

		$defaults = array('orderby' => 'campaign_assets.asset_id', 'order' => 'DESC', 'fields' => 'campaign_assets.*');
		$args = array_merge( $defaults, $args );
		extract($args, EXTR_SKIP);
		$order = ( 'desc' == strtolower($order) ) ? 'DESC' : 'ASC';
		
		$sql = "SELECT ";
		$sql .= $fields." ";
		$sql .= "FROM campaign_assets";
		

		foreach ($clauses as $key => $value){
		  if(is_array($value)){
		   $where[] = $key." IN (".implode(",",$value).") ";
		  }elseif(preg_match("/\s+(like '[a-zA-Z%]+')\s*/i",$value)){
		   $where[] = $key." ".$value;
		  }else{
		   $where[] = $key." = ".$value;
		  }
		  if(preg_match("/(campaign_group_assets\.[a-zA-Z0-9]+)\s*/i",$key)){
		    $join[] = " INNER JOIN campaign_group_assets ON campaign_assets.asset_id = campaign_group_assets.asset_id ";
		  }		  
		}
		
		if(isset($join) && count($join)>0)
			$sql .= implode(" ",$join);
		
		if(isset($where) && count($where)>0)
			$sql .= " WHERE ".implode(" AND ",$where);
		
		$sql .= " ORDER BY ".$orderby." ".$order;

		if(isset($limit_number) && isset($limit_offset))
			$sql .= " LIMIT ".$limit_offset.",".$limit_number;
		elseif(isset($limit_number))
			$sql .= " LIMIT ".$limit_number;
			


	  return $this->db->get_results($sql,'ARRAY_A');

	}
  
  public function detailAsset($asset_id) 
  {
   $sql  = "SELECT campaign_assets.* ";
   $sql .= "FROM campaign_assets ";
   $sql .= "WHERE campaign_assets.asset_id = ".$asset_id;
   return $this->db->get_row($sql,'ARRAY_A');
  }
  
  public function setConnectionCampaign($asset_id,$campaign_id){
    $extra_sql = " ON DUPLICATE KEY UPDATE GID = values(GID),asset_id = values(asset_id)"; 
	$result = $this->db->insert('campaign_group_assets',array('asset_id'=>addslashes($asset_id),'GID'=>addslashes($campaign_id)),$extra_sql);
  }
  

}