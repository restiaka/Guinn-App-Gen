<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Dashboard </title>
		<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/css/quickform.css" type="text/css" media="screen" charset="utf-8" />

		<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/css/960.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/css/template.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/css/colour.css" type="text/css" media="screen" charset="utf-8" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>

	  
	<!-- markItUp! -->
	<script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/markitup/jquery.markitup.js"></script>
	<!-- markItUp! toolbar settings -->
	<script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/markitup/sets/html/set.js"></script>
	<!-- markItUp! skin -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/admin/js/markitup/skins/markitup/style.css" />
	<!--  markItUp! toolbar skin -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/admin/js/markitup/sets/html/style.css" />
	</head> 
	<body>

		<h1 id="head">Control Panel <span style="font-size:12px;">Generic Media Contest Application</span></h1>
		<?php
		$nav = array('dashboard'=>'dashboard','campaign'=>'campaign/lists/','user'=>'user/lists/','customer'=>'customer/lists/','media'=>'media/lists/','app'=>'app/lists/','setting'=>'setting');
		?>
		<ul id="navigation">
		<?php foreach($nav as $k => $v):?>
		<?php 		//if(!$this->acl->isAllowed($this->auth->getAuthData('user_access_level'), $k, 'view')) continue;?>
		   <li><a style="text-decoration:none" href="<?php echo site_url('admin/'.$v)?>"><?=ucfirst($k)?></a></li>
		<?php endforeach;?>
			<li><a href="<?php echo site_url('admin/login/off')?>">Logout</a></li>
		</ul>
		
