<?php $this->load->view('admin/header');?>
<div id="content" class="container_16 clearfix">
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
					How To Get Facebook App Key ?
					<ol>
					<li>Go to <a href="https://www.facebook.com/developers/" target="_blank">Facebook Platform Panel</a>, and then "+Set Up New App"</li>
					<li>Give your application a name and then submit, then you will be redirected to Edit Form for your App</li>
					
					</ol>
					</li>
					<li>
					How To integrate My App with Facebook ?
					<ul>
					<li>On Edit App panel, go to "Facebook Integration" tab, there are some input that you need to fill!</li>
					<li><b>CORE SETTINGS</b>
					<ul><li>Application ID : <b>Put this ID on "Tab URL" bellow</b></li></ul>
					</li>
					
					<li>
					<b>CANVAS</b>
					<ul>
					<li>Canvas Page : <b>Unique name for your App, what ever you like</b></li>
					<li>Canvas URL : <br><b><?php echo base_url()?></b></li>
					<li>Secure Canvas URL : <br><b><?php echo base_url()?></b></li>
					<li>Iframe Size : set to <b>"Auto-resize"</b></li>
					</ul>
					</li>
					
					<li><b>PAGE TABS</b>
					<ul>
					<li>Tab Name : <b>Name on you Page Tabs, fill with what ever you like</b></li>
					<li>Tab URL : <br/><b><?php echo base_url()?>tab/[Fill with Application ID]</b></li> 
					<li>Secure Tab URL : <br><b><?php echo base_url?></b></li>
					</ul>
					</li>
					
					</ul>
					</li> 
				</ol>
			</div>
		</div>
<?php $this->load->view('admin/footer');?>		