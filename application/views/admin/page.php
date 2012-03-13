<?php $this->load->view('admin/header');?>
			<div id="content" class="container_16 clearfix">
			<div class="grid_16"><?php echo printNotification()?></div>
			<div class="grid_16" style="text-align:right;">
			<a href="<?=site_url('admin/page/add')?>">Add Page</a><Br/><br/>
			</div>
			<?php if($data):?>
				<div class="grid_16">
				 <div style="float:left;width:300px;">
					<a href="#" onclick="document.getElementById('task').value='publish'; document.forms[0].submit();">Set Publish</a> &nbsp; 
					<a href="#" onclick="document.getElementById('task').value='draft'; document.forms[0].submit();">Set Draft</a> &nbsp;
				 </div>
				 <div style="float:right;width:200px;text-align:right;">
					<a href="#" onclick="document.getElementById('task').value='delete'; document.forms[0].submit();">Delete Selected pages</a>
				 </div>
				 <div style="clear:both;"></div>
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
								<th style="width:20%">Title</th>
								<th>Publish Date</th>
								<th>Status</th>
								<th>Action</th>
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
								 <td><input style="width:10px" type="checkbox" name="cid[]" value="<?=$v['page_id']?>"/></td>
									<td><?=++$i?></td>
									<td><?=$v['page_title']?></td>
									<td><?=format_date($v['page_publish_date'])?> <?=format_date($v['page_publish_date'],'time')?></td>
									<td><?=$v['page_status']?></td>
									<td><a href="<?=site_url('admin/page/add/'.$v['page_id'])?>">Edit</a></td>
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