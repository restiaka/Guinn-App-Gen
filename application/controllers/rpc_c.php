<?php
 Class Rpc_c extends Controller
 {
	  protected $facebook;
	  protected $db;
	  
	  public function __construct()
	  {
		   $this->facebook = load::l('facebook');
		   $this->db = load::l('ezsql/ezsql_mysql');
		   //Getting Regex Arguments from URL Segments if exists (optional)
		   $reg = load::l('router')->reg_segments;
		   
		   switch($reg[0]){
				case "like" : $this->like($reg[1]);
								break;
				case "unlike" : $this->unlike($reg[1]);
								break;	
				case "commentcreate" : $this->commentcreate($reg[1]);
										break;			
				case "commentremove" : $this->commentremove($reg[1]);
										break;										
		   }
	  }
	  
	  public function like($id)
	  {
		@$this->db->query("INSERT INTO campaign_fblike_counter (`id`,`total`) VALUES (".addslashes($id).",1) 
					ON DUPLICATE KEY UPDATE `total` = campaign_fblike_counter.total+1");
			
	  } 
	  
	  public function unlike($id)
	  {
		@$this->db->query("INSERT INTO campaign_fblike_counter (`id`,`total`) VALUES (".addslashes($id).",0) 
					ON DUPLICATE KEY UPDATE `total` = campaign_fblike_counter.total-1");	
	  }
	  
	  public function commentCreate($id)
	  {
	  
		/*list($comments_fbid) = $this->facebook->api(array(
								'method'=>'fql.query',
								'query'=>'SELECT comments_fbid FROM link_stat WHERE url="'.addslashes($_GET['url']).'"'
							  ));*/
		print_r(addslashes($_GET['url']));
		
		
		/*@$this->db->query("INSERT INTO campaign_fbcomment_data (`id`,`total`) VALUES (".addslashes($id).",1) 
					ON DUPLICATE KEY UPDATE `total` = campaign_fblike_counter.total+1");
			*/
	  } 
	  
 }
  
 