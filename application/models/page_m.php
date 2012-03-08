<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Page_m extends CI_Model {

  private $db;
  public $error = array();
  
  function __construct()
  {
   parent::__construct();
   $this->load->library('ezsql_mysql');
	    $this->db = $this->ezsql_mysql;
  }

  public function addPage($data)
  {
	$ok = $this->db->insert('campaign_page',$data);
	
	if($ok){
	 $id = $this->db->last_insert_id();
	 return $id;
	}else{
		return false;
	}
  }
  
  public function updatePage($data)
  {
	$ok = $this->db->update('campaign_page',$data,array('page_id'=>$data['page_id']));
	
	if($ok){
	 $page_id = $data['page_id'];
     return $page_id;
	}else{
	  return false;
	}
  }
  
  

  
  public function removePage($page_id)
  {
	$deleted = $this->db->query("DELETE FROM campaign_page WHERE page_id = ".$page_id);
	if($deleted){
		return true;
	}
	return false;
  }
  
  public function retrievePage($clauses = array() , $args = array())
	{
	   if(!is_array($args))
		  $args = array($args);

		if(!is_array($clauses))
		 parse_str($clauses,$clauses);

		$defaults = array('orderby' => 'page_id', 'order' => 'DESC', 'fields' => 'campaign_page.*,campaign_group.title');
		$args = array_merge( $defaults, $args );
		extract($args, EXTR_SKIP);
		$order = ( 'desc' == strtolower($order) ) ? 'DESC' : 'ASC';
		
		$sql = "SELECT ";
		$sql .= $fields." ";
		$sql .= "FROM campaign_page INNER JOIN campaign_group ON campaign_page.GID = campaign_group.GID ";

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

		if(isset($limit_number) && isset($limit_offset))
			$sql .= " LIMIT ".$limit_offset.",".$limit_number;
		elseif(isset($limit_number))
			$sql .= " LIMIT ".$limit_number;

	  return $this->db->get_results($sql,'ARRAY_A');

	}
  
  public function getPageURL($gid)
  {
	$rows = $this->retrievePage(array('campaign_page.GID'=>addslashes($gid)),array('orderby'=>'campaign_page.page_title','order'=>'ASC'));
    $url = array();
	foreach($rows as $row){
		$url[$row['page_id']] = menu_url('page/'.$row['page_id']);
	}
	return $url;
  }  
  
  public function detailPage($page_id) 
  {
   $sql  = "SELECT campaign_page.* ";
   $sql .= "FROM campaign_page ";
   $sql .= "WHERE campaign_page.page_id = ".$page_id;
   return $this->db->get_row($sql,'ARRAY_A');
  }
  
  public function setStatusPage($page_id,$status)
  {
   return $this->db->update('campaign_page', array('status'=>$status), array('page_id'=>$page_id));
  }
}