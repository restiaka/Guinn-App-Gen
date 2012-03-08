<?php $this->load->view('admin/header');?>
<div id="content" class="container_16 clearfix">
<div class="grid_16"><?php echo printNotification()?></div>
			<div class="grid_16" style="text-align:right;">
			<a href="<?=site_url('admin/campaign/lists')?>">Campaign List</a><Br/><br/>
			</div>
			<div class="grid_11">
			<?=$content?>
			</div>
			<div class="grid_5">
				<h2>FAQ</h2> 
				<ol>
					<li>FAQ source</li>
				</ol>
			</div>
		</div>
<?php $this->load->view('admin/footer');?>		