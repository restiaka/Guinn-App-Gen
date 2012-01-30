<?php $this->load->view('admin/header');?>
			<div id="content" class="container_16 clearfix">
			<div class="grid_16" style="text-align:right;">
			<a href="<?=site_url('admin/app/add')?>">Add App Config</a><Br/><br/>
			</div>
				<div class="grid_16">
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
								<th style="width:30%">App ID/Name</th>
								<th style="width:20%">Canvas Page</th>
                           	<th style="width:20%">Canvas Fan Page</th>
								<th  >Actions</th>
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
						<?php if($data): $i = $idx;?>
                            <?php foreach($data as $v): ?>
								<tr>
								 <td><input style="width:10px" type="checkbox" name="cid[]" value="<?=$v['APP_APPLICATION_ID']?>"/></td>
									<td><?=++$i?></td>
									<td><?=$v['APP_APPLICATION_ID']?><Br/><?=$v['APP_APPLICATION_NAME']?></td>
									<td><?=$v['APP_CANVAS_PAGE']?></td>
									<td><?=$v['APP_FANPAGE']?></td>
									<td><a href="<?=site_url('admin/app/add/'.$v['APP_APPLICATION_ID'])?>">Edit</a></td>
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