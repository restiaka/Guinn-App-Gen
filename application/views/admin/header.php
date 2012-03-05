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
		<script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/tiny_mce/tiny_mce.js"></script>
    
	<script>
	 $(document).ready(function() {
			tinyMCE.init({
				// General options
				mode : "textareas",
				theme : "advanced",
				plugins : "autolink,lists,pagebreak,table,save,advhr,advimage,advlink,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,fullscreen,noneditable,visualchars,xhtmlxtras,template,wordcount,advlist",
				//editor_selector : "mceEditor",
				editor_deselector : "mceNoEditor",
				width : "600",

				// Theme options
				theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,|,visualchars,template,pagebreak",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,fullscreen",
				
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,

				// Example content CSS (should be your site CSS)
				/*content_css : "css/content.css",*/

				// Drop lists for link/image/media/template dialogs
				template_external_list_url : "<?php echo site_url('admin/template/mcetemplate')?>",

				/*external_link_list_url : "lists/link_list.js",
				external_image_list_url : "lists/image_list.js",
				media_external_list_url : "lists/media_list.js",*/

				formats : {
					alignleft : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'left'},
					aligncenter : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'center'},
					alignright : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'right'},
					alignfull : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'full'},
					bold : {inline : 'span', 'classes' : 'bold'},
					italic : {inline : 'span', 'classes' : 'italic'},
					underline : {inline : 'span', 'classes' : 'underline', exact : true},
					strikethrough : {inline : 'del'}
				}
			});	
	 });	
	</script>
	
	<style>
	#content .quickform  a{
	 background:none;
	 border:none;
	 color:#000000;
	}
	</style>
	
	</head> 
	<body>

		<h1 id="head">Control Panel <span style="font-size:12px;">Generic Media Contest Application</span></h1>
		<?php
		$nav = array('dashboard'=>'dashboard','campaign'=>'campaign/lists/','assets'=>'assets/lists/','user'=>'user/lists/','customer'=>'customer/lists/','page'=>'page/lists/','media'=>'media/lists/','app'=>'app/lists/');
		?>
		<ul id="navigation">
		<?php foreach($nav as $k => $v):?>
		   <li><a style="text-decoration:none" href="<?php echo site_url('admin/'.$v)?>"><?=ucfirst($k)?></a></li>
		<?php endforeach;?>
			<li><a href="<?php echo site_url('admin/login/off')?>">Logout</a></li>
		</ul>
		
