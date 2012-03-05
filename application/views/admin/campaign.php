<?php $this->load->view('admin/header');?>
			<div id="content" class="container_16 clearfix">
			<div class="grid_16"><?php echo printNotification()?></div>
			<div class="grid_16" style="text-align:right;">
			<a href="<?=site_url('admin/campaign/add')?>">Add Campaign</a><Br/><br/>
			</div>
				<div class="grid_16">
					<div style="float:left">
					<a href="#" onclick="document.getElementById('task').value='activate'; document.forms[0].submit();">Activate Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='deactivate'; document.forms[0].submit();">Deactivate Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='delete'; document.forms[0].submit();">Delete Selected</a>
					</div>
					<div style="float:right">
					<a href="#" onclick="document.getElementById('task').value='announcewinner'; document.forms[0].submit();">Announce Winner</a> | 
					<a href="#" onclick="document.getElementById('task').value='haltwinner'; document.forms[0].submit();">Halt Winner Announcement</a>
					</div>
					<div style="clear:both;"></div>
			<br/><br/>
				</div>
				
				<div class="grid_16">
				<form method="POST">
					<table>
						<thead>
							<tr>
								<th width="5%">#</th>
                                <th>No</th>
								<th style="width:20%">Title</th>
								<th>Start Date</th>
								<th>End Date</th>
                                <th>Status</th>
								<th>Winner<Br/>Announced</th>
								<th>Export</th>
								<th width="10%">Actions</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="9" class="pagination">
								<?=$pagination['all']?>
								</td>
							</tr>
						</tfoot>
						<tbody>
						<?php if($data): $i = @$idx;?>
                            <?php foreach($data as $v): ?>
								<tr>
								 <td><input style="width:10px" type="checkbox" name="cid[]" value="<?=$v['GID']?>"/></td>
									<td><?=++$i?></td>
									<td><?=$v['title']?></td>
									<td>
									<?=format_date($v['startdate'])?> <?=format_date($v['startdate'],'time')?>
									</td>
									<td><?=format_date($v['enddate'])?> <?=format_date($v['enddate'],'time')?></td>
									<td><?=$v['status']?></td>
									<td style="text-align:center;"><?=($v['winner_announced'] ? 'YES' : 'NO')?></td>
									<td>
									<a href="<?=site_url('admin/campaign/exportlist/'.$v['GID'])?>">xls</a> 
									<?php if($v['allowed_media_source']=='file'):?>
									<a href="<?=site_url('admin/campaign/exportfile/'.$v['GID'])?>">zip</a> 
									<?php endif;?>
									</td>
									<td><a href="<?=site_url('admin/campaign/add/'.$v['GID'])?>">Edit</a></td>
								</tr>
                            <?php endforeach;?>
						<?php else:?>
						<tr><Td colspan="8">No Data Available</td></tr>
						<?php endif;?>
						</tbody>
					</table>
					<input type="hidden" id="task" name="task"/>
					</form>
				</div>
			</div>
<?php $this->load->view('admin/footer');?>			