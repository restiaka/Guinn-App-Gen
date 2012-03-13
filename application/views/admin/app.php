<?php $this->load->view('admin/header');?>
			<div id="content" class="container_16 clearfix">
			<div class="grid_16"><?php echo printNotification()?></div>
			<div class="grid_16" style="text-align:right;">
			<a href="<?=site_url('admin/app/add')?>">Add App Config</a><Br/><br/>
			</div>
			<?php if($data):?>
				<div class="grid_16">
					<a href="#" onclick="document.getElementById('task').value='delete'; document.forms[0].submit();">Delete Selected</a>
				<br/><br/>
				</div>
			<?php endif;?>	
				<div class="grid_16">
				<form method="POST">
					<table>
						<thead>
							<tr>
								<th width="5%">#</th>
                                <th>No</th>
								<th style="width:20%">Name</th>
								<th>App Info</th>
								<th style="width:15%" >Actions</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="6" class="pagination">
								<!--
									<span class="active curved">1</span><a href="#" class="curved">2</a><a href="#" class="curved">3</a><a href="#" class="curved">4</a> ... <a href="#" class="curved">10 million</a>
								-->
								<?=$pagination['all']?>
								</td>
							</tr>
						</tfoot>
						<tbody>
						<?php if($data): $i = (isset($idx) ? $idx : 0);?>
                            <?php foreach($data as $v): $api = getAppByIDS($v['APP_APPLICATION_ID'],$v['APP_SECRET_KEY'])?>
								<tr>
								 <td><input style="width:10px" type="checkbox" name="cid[]" value="<?=$v['APP_APPLICATION_ID']?>"/></td>
									<td><?=++$i?></td>
									<td><?=$v['APP_APPLICATION_NAME']?></td>
									<td style="font-size:12px;">
									<b>Platform Link:</b> https://developers.facebook.com/apps/<?=$v['APP_APPLICATION_ID']?>
									<br>
									<b>Fan Page: </b> <?=$v['APP_FANPAGE']?>
									<br>
									<b>App Canonical Link: </b> http://apps.facebook.com/<?=@$api['namespace']?>
									<br>
									<b>App UID Link (Save): </b> http://apps.facebook.com/<?=$v['APP_APPLICATION_ID']?>
									<Br>
									<b>Creator URL: </b> <?=(@$api['creator_uid'] ? "http://www.facebook.com/profile.php?id=".$api['creator_uid'] : "") ?>
									
									</td>
									<td>
									<a href="<?=site_url('admin/app/add/'.$v['APP_APPLICATION_ID'])?>">Edit</a>
									<a href="<?php echo appToPage_dialog_url($v['APP_APPLICATION_ID'],site_url("campaign/{$v['APP_APPLICATION_ID']}/addtopage"))?>">Add to Page</a>
									</td>
								</tr>
                            <?php endforeach;?>
						<?php else:?>
						<tr><Td colspan="6">No Data Available</td></tr>
						<?php endif;?>
						</tbody>
					</table>
					<input type="hidden" id="task" name="task"/>
					</form>
				</div>
			</div>
<?php $this->load->view('admin/footer');?>			