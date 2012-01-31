<?php $this->load->view('admin/header');?>
			<div id="content" class="container_16 clearfix">

			
				
				<div class="grid_16">
					<a href="#" onclick="document.getElementById('task').value='activate'; document.forms['adminform'].submit();">Activate Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='deactivate'; document.forms['adminform'].submit();">Deactivate Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='delete'; document.forms['adminform'].submit();">Delete Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='winner'; document.forms['adminform'].submit();">Pick Selected as WINNER</a>
					<a href="#" onclick="document.getElementById('task').value='resetwinner'; document.forms['adminform'].submit();">Reset Seleted WINNER</a>
				<br/><br/>
				</div>
				
				<div class="grid_16">
				<form name="search" method="POST">
					By Campaign 
					<select name="bycampaign">
					<option value="">Select Campaign</option>
					<?php foreach($campaigns as $row):?>
					<option value="<?=$row['GID']?>"><?=$row['title']?></option>
					<?php endforeach;?>
					</select>
					&nbsp;&nbsp;
					By FB UID <input style="width:120px;" type="text" name="byuid" />
					&nbsp;&nbsp;
					By Status <select name="bystatus">
								<option value="" >Pick Status?</option>
								<option value="active">Active</option>
								<option value="inactive">In Active</option>
							  </select>	
					&nbsp;&nbsp;
					<input type="hidden" name="task" value="search">				
					<input type="submit" value="Search">				
				</form>
				<br/>
				</div>
				
				<div class="grid_16">
				<form name="adminform" method="POST">
					<table>
						<thead>
							<tr>
								<th width="5%">#</th>
                                <th >No</th>
								<th style="width:25%">Detail</th>
								<th >Media</th>
								<th>Upload Date</th>
								<th>Campaign</th>
                                <th>Status</th>
								<th colspan="2" width="10%">FB UID</th>
					
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
						<?php if($data): $i = $idx; 
						//$CI = &get_instance();
						$this->load->model('media_m');
						$this->load->model('campaign_m');
						?>
                            <?php foreach($data as $v): ?>
								<tr>
								 <td style="vertical-align:top;<?=$v['media_winner'] ? 'background-color:#A1EAB3;' : ''?>"><input style="width:10px" type="checkbox" name="cid[]" value="<?=$v['media_id']?>"/></td>
									<td style="vertical-align:top;"><?=++$i?></td>
									<td style="vertical-align:top;">
									<b><?=$v['media_title']?></b><br/>
									<?=$v['media_description']?>
									</td>
									<td style="vertical-align:top;text-align:center;">
									<?=$this->media_m->showMedia($v);?>
									<br/>
									<a href="<?=$v['media_url']?>" target="_blank">View <?=$v['media_type']?></a>
									</td>
									<td style="vertical-align:top;"><?=format_date($v['media_uploaded_date'])?> <?=format_date($v['media_uploaded_date'],'time')?></td>
									<?php $campaign = $this->campaign_m->detailCampaign($v['GID'])?>
									<td style="vertical-align:top;"><?=$campaign['title']?></td>
									<td style="vertical-align:top;"><?=$v['media_status']?></td>
									<td style="vertical-align:top;"><a href="http://www.facebook.com/profile.php?id=<?=$v['uid']?>" target="_blank"><?=$v['uid']?></a></td>
									
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