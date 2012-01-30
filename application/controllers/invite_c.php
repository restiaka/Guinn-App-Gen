<?php
 Class Invite_c extends Controller
 {
  public function __construct()
  {
   //Getting Regex Arguments from URL Segments if exists (optional)
   $reg = load::l('router')->reg_segments;
   
   sdkRequireLogin(APP_CANVAS_PAGE.'invite');
   
   $fb = load::l("facebook");
   $inviter_uid = $fb->getUser();
   
   if($_POST['ids'])
   foreach ($_POST['ids'] as $uid){
	load::l("db")->insert("ptc_invitation", array("uid"=>$inviter_uid,"invited_uid"=>$uid));
   }
   
    //Get exclude_ids
   $exclude_ids = load::l('db')->query("SELECT invited_uid FROM ptc_invitation WHERE uid=".$inviter_uid,NULL,PDO::FETCH_COLUMN);

   
   $this->setOutput(array('exclude_ids'=>@implode(',',$exclude_ids),'total_invited'=>count($exclude_ids)));
  }
 } 
 