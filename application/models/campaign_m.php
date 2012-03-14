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
  }
  
  public function getActiveCampaign()
  {
    $sql = "SELECT * 
			FROM campaign_group 
			WHERE status = 'active' 
				  AND( startdate <= '".date('Y-m-d H:i:s')."' AND enddate >= '".date('Y-m-d H:i:s')."' ) 
				  AND APP_APPLICATION_ID = '".$this->setting_m->get('APP_APPLICATION_ID')."'  
			ORDER BY startdate DESC 
			LIMIT 1";

	  if($result = $this->db->get_row($sql,'ARRAY_A')){
	    $result = array_merge($result,$this->getStatus($result));
		//Merge Assets if exists
		if($assets = $this->getAssets($result['GID'])){
		  $result = array_merge($result,$assets);
		}
		//Merge extra Page if exists
		if($pages = $this->getPages($result['GID'])){
		  $result = array_merge($result,$pages);
		}
	  }else{
		$result = null;
	  }	  

      return 	$result ? $result : null;
  }
  
/**
 *
 *  SETUP STATUS OF CAMPAIGN
 *   
 *                 _____________ON_PROGRESS__________________________
 *   ___ON_WAIT___                                                    ___IS_OFF___
 *   
 *   -------------|---------------|------------------|---------------|------------
 *              Start            Upload             Judging          End
 *                               End                Time
 *             
 *                ___ON_UPLOAD____
 *                ______________ON_VOTE______________
 *				                                    ___ON_JUDGING___
 *
 **/
 
  public function getStatus($data)
  {
	  $o_nowdate = new DateTime(date("Y-m-d H:i:s")); 
	  $nowTime = $o_nowdate->getTimestamp();
	  
	  $o_startdate = new DateTime($data['startdate']); 
	  $startTime = $o_startdate->getTimestamp();
	  
	  $o_upload_enddate = new DateTime($data['upload_enddate']); 
	  $uploadEndTime = $o_upload_enddate->getTimestamp();
	  
	  $o_judging = new DateTime($data['winner_selectiondate']); 
	  $judgingTime = $o_judging->getTimestamp();
	  
	  $o_enddate = new DateTime($data['enddate']); 
	  $endTime = $o_enddate->getTimestamp();
	  
	  if($nowTime < $startTime){
		$status = $this->setStatus(true,false,false,false,false,false);
	  }elseif($nowTime >= $startTime && $nowTime < $uploadEndTime){
	    $status = $this->setStatus(false,true,true,true,false,false);
	  }elseif($nowTime >= $uploadEndTime && $nowTime < $judgingTime){
	    $status = $this->setStatus(false,true,false,true,false,false);
	  }elseif($nowTime >= $judgingTime && $nowTime < $endTime){
	    $status = $this->setStatus(false,true,false,false,true,false);
	  }else{
	    $status = $this->setStatus(false,false,false,false,false,true);
	  }
	  
	  return $status;
  }
  
  public function getAssets($GID){
    $this->load->model('assets_m','asset');
    $data = array();
	if($assets = $this->asset->retrieveAssets(array('campaign_group_assets.GID'=>$GID))){
		foreach ($assets as $asset){
			$data["asset_".$asset['asset_platform']][$asset['asset_type']]['url'] = $asset['asset_url']; 
			$data["asset_".$asset['asset_platform']][$asset['asset_type']]['bgcolor'] = $asset['asset_bgcolor']; 
		}
	}
	return $data;
  }
  
  public function getPages($GID){
  $this->load->model('page_m','page');
    $data = array();
	if($pages = $this->page->retrievePage(array('campaign_page.GID'=>$GID))){
		foreach ($pages as $page){
			$data['pages'][] = array('id'=>$page['page_id'],'facebook'=>$page['page_facebook'],'mobile'=>$page['page_mobile'],'name'=>$page['page_short_name'],'url'=>menu_url('page/'.$page['page_id'])); 
		}
	}
	return $data;
  }
  
  public function setStatus($on_wait = false,$on_progress = false,$on_upload = false,$on_vote = false,$on_judging = false,$is_off = true){
	return compact('on_wait','on_progress','on_upload','on_vote','on_judging','is_off');
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
	  return $gid;
	 }else{
	  return false;
	 }
	}else{
		return false;
	}
  }
  
  public function duplicateCampaign($data)
  {
    $use_assets = false; $use_pages = false;
	$GID = $data['GID']; 
	unset($data['GID']);

	if(isset($data['assets_duplicate']) && $data['assets_duplicate'] == 1){
	  $use_assets = true;
	  unset($data['assets_duplicate']);
	}
	
	if(isset($data['pages_duplicate']) && $data['pages_duplicate'] == 1){
	  $use_pages = true;
	  unset($data['pages_duplicate']);
	}
	
	$proceed = true;
	$error = array();

	//Add new campaign
	if($new_GID = $this->addCampaign($data)){

		if($use_assets && $proceed){
			$this->load->model('assets_m','asset');
			if($assets = $this->asset->retrieveAssets(array('campaign_group_assets.GID'=>$GID))){
				foreach ($assets as $asset){
					$ok = $this->asset->setConnectionCampaign($asset['asset_id'],$new_GID,$asset['asset_type'],$asset['asset_platform']);
				    if(!$ok){ $proceed = false; $error[]['asset']=$asset; break;}
				}
			}
		}
		
		if($use_pages && $proceed){
			$this->load->model('page_m','page');
			if($pages = $this->page->retrievePage(array('campaign_page.GID'=>$GID),array('fields'=>'campaign_page.*'))){
				foreach ($pages as $page){
					$page['GID'] = $new_GID;
					unset($page['page_id']);
					$ok = $this->page->addPage($page);
					if(!$ok){ $proceed = false; $error[]['page']=$page; break;}
				}
			}
		}

		if(!$proceed){
			$this->removeCampaign($new_GID);
			return false;
		}
		
		return true;	
	}else{
		return false;
	}
	
  }
  
  public function updateCampaign($data)
  {
	$ok = $this->db->update('campaign_group',$data,array('GID'=>$data['gid']));
	
	if($ok){
	 $gid = $data['gid'];
     return true;
	}else{
	   $this->error[] = "Submission Update has Failed, Try Again or Contact Web Administrator";
		return false;
	}
  }
  
  

  
  public function removeCampaign($gid)
  {
    
	 $this->load->model('media_m','media');
	 //Archive Campaign if it's already a participant
	 if($media = $this->media->mediaByRandom($gid)){
		return false;
	 }
   
	$deleted = $this->db->query("DELETE FROM campaign_group WHERE GID = ".$gid);
	$deleted = $this->db->query("DELETE FROM campaign_page WHERE GID = ".$gid);
	$deleted = $this->db->query("DELETE FROM campaign_group_assets WHERE GID = ".$gid);
	
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
		
		if(isset($where) && count(@$where)>0)
			$sql .= " WHERE ".implode(" AND ",$where);
		
		$sql .= " ORDER BY ".$orderby." ".$order;

		if(isset($limit_number) && isset($limit_offset))
			$sql .= " LIMIT ".$limit_offset.",".$limit_number;
		elseif(isset($limit_number))
			$sql .= " LIMIT ".$limit_number;

	  return $this->db->get_results($sql,'ARRAY_A');

	}
  
  public function detailCampaign($gid) 
  {
   $sql  = "SELECT campaign_group.* ";
   $sql .= "FROM campaign_group ";
   $sql .= "WHERE campaign_group.GID = ".$gid;
   $result = null;
   	  if($result = $this->db->get_row($sql,'ARRAY_A')){
	    $result = array_merge($result,$this->getStatus($result));
		//Merge Assets if exists
		if($assets = $this->getAssets($result['GID'])){
		  $result = array_merge($result,$assets);
		}
		//Merge extra Page if exists
		if($pages = $this->getPages($result['GID'])){
		  $result = array_merge($result,$pages);
		}
	  }	  
   
    return $result;
  }
  
  public function getCampaignByAppID($appid,$limitOne = false)
  {
	   $result = null;
	   $sql  = "SELECT campaign_group.* ";
	   $sql .= "FROM campaign_group ";
	   $sql .= "WHERE campaign_group.APP_APPLICATION_ID = ".$appid;
	   if($limitOne){
		$sql .= "LIMIT 1";
		$result = $this->db->get_row($sql,'ARRAY_A');
	   }else{
		$result = $this->db->get_results($sql,'ARRAY_A');
	   }
	   return $result;
  }
  
  public function setStatusCampaign($gid,$status)
  {
   return $this->db->update('campaign_group', array('status'=>$status), array('GID'=>$gid));
  }
  
  public function announceWinner($gid,$status)
  {
   return $this->db->update('campaign_group', array('winner_announced'=>$status), array('GID'=>$gid));
  }
}