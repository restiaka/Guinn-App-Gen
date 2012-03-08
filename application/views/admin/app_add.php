<?php $this->load->view('admin/header');?>
<?php $domain = parse_url(base_url(),PHP_URL_HOST);?>
<div id="content" class="container_16 clearfix">
<div class="grid_16"><?php echo printNotification()?></div>
			<div class="grid_16" style="text-align:right;">
			<a href="<?=site_url('admin/app/lists')?>">App Config List</a><Br/><br/>
			</div>
			<div class="grid_6">
			<?=$content?>
			</div>
			<div class="grid_10">
				<h2>FAQ</h2> 
				<ol>
					<li>
					How To Register a Facebook App?
					<ol>
					<li>Go to <a href="https://developers.facebook.com/apps" target="_blank">Facebook Platform Panel</a>, and then "+Create New App"</li>
					<li>Fill Up your "App Name" and "App Namespace", security check may required.</li>
					<li>After registered you will be provided with <b>App ID</b> and <b>App Secret</b><Br>
					you now can use the <b>App ID</b> for next configuration.
					</li>
					</ol>
					</li>
					<li>
					On <b>"Settings > Basic"</b> Tab menu.
					<ul>
					<li>
					<b>- Basic Info -</b>
					<ol>
					 <li>App Domain : <b><?php echo $domain?></b></li>
					</ol>
					</li>
					
					<li>
					<b>- Select how your app integrates with Facebook -</b><Br/>
					Please change <b>[App ID]</b> tag with provide App ID that you already have.<br/><br/>
					<ul>
					 <li>
					 Click On "App On Facebook"
					 <ol>
					 <li>Canvas URL : <b>http://<?php echo $domain?>/campaign/[App ID]/</b></li>
					 <li>Secure Canvas URL : <b>https://<?php echo $domain?>/campaign/[App ID]/</b></li>
					 </ol>
					 </li>
					  <li>
					 Click On "Page Tab"
					 <ol>
					 <li>Page Tab Name : Fill with short campaign title, ex: 100 Photo Contest</li>
					 <li>Page Tab URL : <b>http://<?php echo $domain?>/campaign/[App ID]/</b></li>
					 <li>Secure Page Tab URL : <b>https://<?php echo $domain?>/campaign/[App ID]/</b></li>
					 </ol>
					 </li>
					  <li>
					 Click On "Mobile Web"
					 <ol>
					 <li>Mobile Web URL : <b>https://<?php echo $domain?>/mobile/[App ID]/</b></li>
					 </ol>
					 </li>
					</ul>
					</li>
					
					</ul>
					</li> 
					<li>
					After You finished the step on Facebook Platform, you may registered your Facebook App
					detail here. Please fill up your <b>APPLICATION NAME</b>, <b>APP ID</b>, <b>APP SECRET</b>, <b>FACEBOOK PAGE URL</b> ( Main Facebook Page URL for The App ), and check the <b>"Age 21+ Restriction"</b> if your app need to be restrict only for 21+ users

					</li>
				</ol>
			</div>
		</div>
<?php $this->load->view('admin/footer');?>		