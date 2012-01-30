<?php $this->load->view('admin/header');?>
			<div id="content" class="container_16 clearfix">
			<div class="grid_16" style="text-align:right;">
			<a href="<?=site_url('admin/campaign/add')?>">Add Campaign</a><Br/><br/>
			</div>
				<div class="grid_16">
					<a href="#" onclick="document.getElementById('task').value='activate'; document.forms[0].submit();">Activate Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='deactivate'; document.forms[0].submit();">Deactivate Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='delete'; document.forms[0].submit();">Delete Selected</a>
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
								<th>Export</th>
								<th width="10%">Actions</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="8" class="pagination">
								<!--
									<span class="active curved">1</span><a href="#" class="curved">2</a><a href="#" class="curved">3</a><a href="#" class="curved">4</a> ... <a href="#" class="curved">10 million</a>
								-->
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