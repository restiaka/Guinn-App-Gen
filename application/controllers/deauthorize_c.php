<?php
Class Deauthorize_c extends controller {
 protected $db;
 public function __construct() {
	$this->db = load::l('ezsql/ezsql_mysql');
	$signed_request = load::l('facebook')->getSignedRequest();
	$this->db->update('campaign_customer_fbauthorization',
						array('authorized'=>0,'deauthorized_date'=>date('Y-m-d H:i:s')),
						array('uid'=>$signed_request['user_id'],
							  'APP_APPLICATION_ID'=>addslashes(load::l('router')->segments[1]))
					);
 }
}