<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Media_m extends CI_Model {

  private $db;
  private $customer;

  function __construct()
  {
   parent::__construct();
   $this->load->library('ezsql_mysql');
		$this->db = $this->ezsql_mysql;
		
	$this->load->model('customer_m','customer');
	$this->load->model('setting_m');
	$this->load->model('campaign_m');
  }
  
  public function addMedia($data)
  { 
    $this->load->library('facebook');
    if(!$this->facebook->getUser())return false;
	

	
	
	$ok = $this->db->insert('campaign_media',$data);
	$media_id = $this->db->insert_id;
	if($ok && $media_id){
	   $this->db->insert('campaign_media_owner',array('media_id'=>$media_id,
													          'uid'=>$this->facebook->getUser(),
													          'GID'=>$data['GID']));	
	}else{
	  return false;
	}
  } 
  
  function fblike($href,$attr = "show_faces='false' width='430' font=''")
  {
     return "<fb:like href='$href' $attr ></fb:like>";
  }
  
  function setOpenGraphMeta($meta){
    if(!is_array($meta))$meta = array();
	foreach ($meta as $key => $value)
		$meta_tag .= '<meta property="og:'.$key.'" content="'.$value.'"/> ';
	$meta_tag .= '<meta property="fb:app_id" content="'.$this->setting_m->get('APP_APPLICATION_ID').'"/> ';	
		
    return $meta_tag;		
  } 
  
  function fbcomment($href,$attr = "colorscheme='light' width='460' num_posts='5'")
  {
   return "<fb:comments href='$href' $attr ></fb:comments>";
  }
 

 function gallery($media,$pagination = null,$use_default_style = true){

   if(!is_array($media)){
     $media = array();
   }

    if(count($media)>0):
		$html .=  '<ul class="gallery-list">';
		foreach($media as $row):
		$html .= '<li>'.
				 '<div style="width:100px;height:100px;"><a href="'.menu_url('media').'/?m='.$row['media_id'].'">'.$this->showMedia($row).'</a></div>'.
				 '<div class="posted-by">Posted by</div>'.
				 '<div class="owner"><fb:name uid="'.$row['uid'].'"></fb:name></div>'.
				 '</li>';
		endforeach;   
		$html .= '</ul>';
		$html .= '<div class="pagination">'.$pagination['all'].'</div>';
    else:
		$html .= '<div style="color:#fff" >'.
					'<a href="'.menu_url().'" >Click here and submit yours!</a>'.
				 '</div>';
	endif;
	return $html;			  
 }



  function gallery_deprecated($media,$pagination = null,$use_default_style = true){

   if(!is_array($media)){
     $media = array();
   }
	$default_style = '#media_container_wrapper {width:100px;height:140px;float:left;}
					  #media_container_thumb {height:100px;}
					  #media_container_thumb img {width:90px;}
					  #media_gallery_empty{text-align:center;padding:14px;color:#135D4B;font-weight:bold;font-size:15px;}
					  #media_gallery_empty a{color:#fff;}
					  #media_paging_links_top {margin-bottom:20px;}
					  #media_paging_links_bottom {margin-top:20px;}';
	$html .= $use_default_style ? '<style>'.$default_style.'</style>' : '';			  
    if(count($media)>0):
		$html .=  '<div id="media_paging_links_top">'.$pagination['all'].'</div>';
						foreach($media as $row):
						$html .= '<div id="media_container_wrapper" >
								<div id="media_container_thumb"  >
								'.$this->showMedia($row).'
								</div>
								<div id="media_container_meta" >
									<p><fb:name uid="'.$row['uid'].'"></fb:name></p>
									<p><a href="'.menu_url('media').'/?m='.$row['media_id'].'">View detail >></a></p>
								</div>
							</div>';
						endforeach;   
		$html .= '<div class="clear"></div>';
		$html .= '<div id="media_paging_links_bottom">'.$pagination['all'].'</div>';
    else:
		$html .= '<div id="media_gallery_empty" >
					<a href="'.menu_url().'" >Click here and submit yours!</a>
				 </div>';
	endif;
	return $html;			  
  }
  
  
  function mediaContainer($media = array(),$use_default_style = true)
  { 
    $default_style = '#media_container_wrapper { margin-bottom:20px; }
					  #media_container_source {}
					  #media_container_source img {width:400px;}
					  #media_meta_wrapper { margin-bottom:20px; }
					  #media_meta_user{ margin-bottom:20px; }
					  #media_meta_user td {padding:10px;}
					  #media_meta_user th {padding:10px;}
					  #media_social_wrapper{margin-bottom:20px;background-color:white;padding:5px;width:100%}
					  
					  #media_social_fblike {margin-bottom:10px;}
					  #media_social_fbcomment {margin-bottom:10px;}';
					  
	$html = $use_default_style ? '<style>'.$default_style.'</style>' : '';
	
	$html .=   '<div id="media_container_wrapper" >
					<div id="media_container_source" align="center">    
						'.$this->showMedia($media,false).' 
					</div>
				</div>';
	$html .=	'<div id="media_meta_wrapper">
				  <table id="media_meta_user">
					  <tr>
						  <th style="text-align:top;">Shared By : </th>
						  <td><fb:name uid="'.$media['uid'].'"></fb:name></td>
					  </tr>
				  </table>
				</div>';
				//$this->fblike(menu_url('media').'&m='.$media['media_id'])
	
	$fblike_href = menu_url('media').'/?m='.$media['media_id'];
	
    $activeCampaign = $this->campaign_m->getActiveCampaign();
	$html .=	'<div id="media_social_wrapper">';
	if($activeCampaign['media_has_fblike']){
		$html .= '<div id="media_social_fblike">'.$this->fblike($fblike_href).'</div>';
	}
	if($activeCampaign['media_has_fbcomment']){	
		$html .=	'<div id="media_social_fbcomment">'.$this->fbcomment($fblike_href).'</div>';
	}	
	$html .= '</div>';
	return $html;	
  }
  
  function getPlugin($media){
    
	$fblike_href = menu_url('media').'/?m='.$media['media_id'];
	//$fbcomment_href = $this->setting_m->get('APP_FANPAGE').'&app_data=redirect|'.menu_url('media',true).'/?m='.$media['media_id'];
	$plugins = array('fblike'=>'','fbcomment'=>'','votebutton'=>'');
    $activeCampaign = $this->campaign_m->getActiveCampaign();
	
	if($activeCampaign['media_has_fblike']){
		$plugins['fblike'] = $this->fblike($fblike_href);
	}
	
	if($activeCampaign['media_has_vote']){
		$plugins['votebutton'] = $this->showVote($media);
	}
	
	if($activeCampaign['media_has_fbcomment']){	
		$plugins['fbcomment'] =	$this->fbcomment($fblike_href);
	}	
	
	return $plugins;	  
  }
  
  function showVote($media){ 
	$this->load->model('form_m');
	$this->load->model('setting_m');
	$this->load->library('facebook');
	
	$isAuthorized = (!$this->facebook->getUser() || !isExtPermsAllowed()) ? false : true;
	if($isAuthorized){
		$content = $this->form_m->vote_form($media['media_id'],$media['media_vote_total']);
	}else{
	 $redirectURL = $this->setting_m->get('APP_FANPAGE')."&app_data=redirect_media|".$media['media_id'];
	 $content = authorizeButton('Login to Vote',$redirectURL);
	}
	
	return $content;
  }
  
  
  function showMedia($media_id,$thumb = true,$attribute=null){
   
   if(is_array($media_id)){
		$m = $media_id;	
   }else{
	  if(!$m = $this->detailMedia($media_id)) return false;
   }
   
   switch($m['media_source']){
		case "file" :
		 if($m['media_type']=="image"){
			//$src = !$thumb ?  $m['media_basename'] : "thumb_".$m['media_basename'];
			//$link = base_url()."image?gid=".$m['GID']."&src=".$src;
			$src = !$thumb ?  $m['media_url'] : $m['media_thumb_url'];
			$link = $src;
			return "<img src='$link' $attribute/>";
		 }elseif($m['media_type']=="video"){
			//$link = "video?gid=".$m['media_id']."&src=".$m['media_basename'];
			return null;
		 }		 
		break;
		
		default:
		if($m['media_type']=="image"){
			$src = !$thumb ?  $m['media_url'] : $m['media_thumb_url'];
			$link = $src;
			return "<img src='$link' $attribute/>";
		 }elseif($m['media_type']=="video"){
			$src = !$thumb ?  $m['media_url'] : $m['media_thumb_url'];
			if($thumb){
				$link = $src;
				return "<img src='$link' $attribute/>";
			}else{
				return '<object width="460" height="280">
						<param name="allowFullScreen" value="true"></param>
						<param name="allowscriptaccess" value="always"></param>
						<param name="movie" value="'.$src.'"></param>
						<embed src="'.$src.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="460" height="280"></embed>
						</object>';
			}
		 }	
		break;
		
   }
   return null;
		
  }

  
  public function removeMedia($media_id)
  {
	$media = $this->db->get_row("SELECT media_basename,media_type,media_source,GID FROM campaign_media WHERE media_id = ".$media_id,'ARRAY_A');
	//if(!$media['media_basename'])return false;

	  //Delete media
	 if($this->db->query("DELETE FROM campaign_media WHERE media_id = ".$media_id)){
	  //Delete media Owner
	  $this->db->query("DELETE FROM campaign_media_owner WHERE media_id = ".$media_id);
	  //Delete Physical file if exists
	  if($media['media_source'] == "file" && strtolower($media['type']) == "image"){
			@unlink(CUSTOMER_IMAGE_DIR.$media['GID'].'/'.$media['basename']);
			@unlink(CUSTOMER_IMAGE_DIR.$media['GID'].'/thumb_'.$media['basename']);
	  }elseif($media['media_source'] == "file" && strtolower($media['type']) == "video"){
			@unlink(CUSTOMER_VIDEO_DIR.$media['GID'].'/'.$media['basename']);
			@unlink(CUSTOMER_VIDEO_DIR.$media['GID'].'/thumb_'.$media['basename']);
	  }
	 }
	
	return true;
  }
  
  public function retrieveMedia($clauses = array() , $args = array())
	{
	   if(!is_array($args))
		  $args = array($args);

	if(!is_array($clauses))
	 parse_str($clauses,$clauses);

	$defaults = array('orderby' => 'campaign_media.media_id', 'order' => 'DESC', 'fields' => 'campaign_media.*,campaign_media_owner.uid');
	$args = array_merge( $defaults, $args );
	extract($args, EXTR_SKIP);
	$order = ( 'desc' == strtolower($order) ) ? 'DESC' : 'ASC';
    
	$sql = "SELECT ";
	$sql .= $fields." ";
	$sql .= " FROM campaign_media ";
	$sql .= " INNER JOIN campaign_media_owner ON campaign_media.media_id = campaign_media_owner.media_id ";

		foreach ($clauses as $key => $value){
		  if(is_array($value)){
		   $where[] = $key." IN ('".implode("','",$value)."') ";
		  }else{
		   $where[] = $key." = '".$value."'";
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
  
  public function detailMedia($media_id) 
  {
   $sql  = "SELECT campaign_media.*,campaign_media_owner.uid ";
   $sql .= "FROM campaign_media ";
   $sql .= "INNER JOIN campaign_media_owner ON campaign_media.media_id = campaign_media_owner.media_id ";
   $sql .= "WHERE campaign_media.media_id = ".$media_id;
   return $this->db->get_row($sql,'ARRAY_A');
  }
  
  public function setVote($media_id)
  {
   $this->load->library('facebook');
    if($uid = $this->facebook->getUser()){
	  $result = @$this->db->insert('campaign_media_vote',array('media_id' => $media_id,'uid' => $uid,'unix_timestamp' => time()));
	  if($result){
	    $totalnow = $this->db->get_var("SELECT count(*) as total FROM campaign_media_vote WHERE media_id = ".$media_id);
	    $this->db->update('campaign_media',array('media_vote_total'=>$totalnow),array('media_id'=>$media_id));
		return $totalnow;
	  }
	  return null;
	}
	return null;
  }
  
  public function setStatusMedia($media_id,$status)
  {
   return $this->db->update('campaign_media', array('media_status'=>$status), array('media_id'=>$media_id));
  }
  
  public function setWinnerMedia($media_id,$value = 1)
  {
   return $this->db->update('campaign_media', array('media_winner'=>$value), array('media_id'=>$media_id));
  }
}