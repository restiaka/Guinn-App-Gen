<?php $this->load->view('admin/header');?>
			<div id="content" class="container_16 clearfix">
						<div class="grid_16" style="text-align:right;">
			<a href="<?=site_url('admin/user/add')?>">Add User</a><Br/><br/>
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
								<th style="width:20%">Name</th>
								<th>Email</th>
								<th>Status</th>
								<th colspan="2" width="10%">Actions</th>
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
								 <td><input style="width:10px" type="checkbox" name="cid[]" value="<?=$v['user_ID']?>"/></td>
									<td><?=++$i?></td>
									<td><?=$v['user_name']?></td>
									<td><?=$v['user_email']?></td>
									<td><?=$v['user_status']?></td>
									<td><a href="<?=site_url('admin/user/add/'.$v['user_ID'])?>">Edit</a></td>
								</tr>
                            <?php endforeach;?>
						<?php else:?>
						<tr><Td colspan="7">No Data Available</td></tr>
						<?php endif;?>
						</tbody>
					</table>
					<input type="hidden" id="task" name="task"/>
					</form>
				</div>
			</div>
<?php $this->load->view('admin/footer');?>
			