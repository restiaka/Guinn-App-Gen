<?php $CI = &get_instance(); $CI->load->model('customer_m'); $this->load->view('admin/header');?>
			<div id="content" class="container_16 clearfix">
				<div class="grid_16">
					<!--
					<a href="#" onclick="document.getElementById('task').value='activate'; document.forms[0].submit();">Activate Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='deactivate'; document.forms[0].submit();">Deactivate Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='delete'; document.forms[0].submit();">Delete Selected</a>
				<br/><br/>
				-->
				</div>
				
				<div class="grid_16">
				<form method="POST">
					<table>
						<thead>
							<tr>
								<!--<th width="5%">#</th>-->
                                <th  width="5%">No</th>
								<th style="width:30%">Facebook Data</th>
								<th style="width:30%">DB Data</th>
								<th>Status</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="4" class="pagination">
								<?=$pagination['all']?>
								</td>
							</tr>
						</tfoot>
						<tbody>
						<?php if($data): $i = (isset($idx) ? $idx : 0);?>
                            <?php foreach($data as $v): $fb = getFacebookUser($v['uid']);?>
								<tr>
								 <!--<td><input style="width:10px" type="checkbox" name="cid[]" value="<?=$v['uid']?>"/></td>-->
									<td><?=++$i?></td>
									<td>
									<b>UID :</b> <?=$v['uid']?><br/>
									<b>Name :</b> <?=$fb->name?><br/>
									<b>Gender :</b> <?=$fb->gender?><br/>
									<a href="http://www.facebook.com/profile.php?id=<?php echo $v['uid']?>" target="_blank">Go to Facebook Profile</a>
									</td>
									<td>
									<?php 
									$t = $CI->customer_m->detailTRAC($v['customer_id'],null,'C'); $t = @$t['fields'];?> 
									<b>FirstName :</b> <?=@$t['FIRSTNAME']?><Br/>
									<b>LastName :</b> <?=@$t['LASTNAME']?><Br/>
									<b>Email :</b> <?=@$t['EMAIL']?><Br/>
									<b>Email OPT :</b> <?=@$t['EMAILOPT']?><Br/>
									<b>Mobile :</b> <?=@$t['MOBILE']?><Br/>
									<b>Mobile 2nd :</b> <?=@$t[$this->config->item('TRAC_ATTR_MOBILE2')]?><Br/>
									<b>Active :</b> <?=@$t['ACTIVE']?><Br/>
									</td>
									
									<td><?=$v['status']?></td>
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