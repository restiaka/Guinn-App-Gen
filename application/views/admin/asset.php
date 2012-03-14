<?php $this->load->view('admin/header');?>
			<div id="content" class="container_16 clearfix">

			
				<div class="grid_16"><?php echo printNotification()?></div>
				<div class="grid_16">
					<div style="float:left;">
					
					<a href="#" onclick="document.getElementById('task').value='filter'; document.forms['adminform'].submit();">Filter By Campaign</a>  
					<?php if($data):?>
					<a href="#" onclick="document.getElementById('task').value='delete'; document.forms['adminform'].submit();">Delete Selected Assets</a>  
					<?php endif;?>
					</div>
					<div style="float:right;">
					<?php if($data):?>
					<a href="#" onclick="document.getElementById('task').value='link'; document.forms['adminform'].submit();">Link Assets to Campaign</a>  
					<a href="#" onclick="document.getElementById('task').value='unlink'; document.forms['adminform'].submit();">Unlink Assets from Campaign</a>  
					<?php endif;?>
					<a href="<?=site_url('admin/assets/add')?>">Add an Asset</a>
					</div>
					<div style="clear:both;"></div>
				<br/><br/>
				</div>
				
				<div class="grid_4 omega" >
					<form name="adminform" method="POST" action="<?=site_url('admin/assets/lists')?>">
					<?php if($data):?>
					<div style="margin-bottom:10px;">
					<input type="text" name="bysearch" style="width:80%;"/>
					<a href="#" onclick="document.getElementById('task').value='search'; document.forms['adminform'].submit();">></a>  
					</div>
					<?php endif;?>
					<div style="margin-bottom:10px;">
					<select name="bycampaign" size="100" style="width:220px;">				
					<option value="" <?=(!$this->input->get_post('bycampaign', TRUE) ? "selected='selected'" : "");?> >All Campaign</option>
					<?php foreach($campaigns as $row):?>
					<option value="<?=$row['GID']?>" <?=($this->input->get_post('bycampaign', TRUE) == $row['GID'] ? "selected='selected'" : "");?>><?=$row['title']?></option>
					<?php endforeach;?>
					</select>
					</div>

				</div>
				<div class="grid_12">


					<table>
						<thead>
							<tr>
								<th  width="5%" >#</th>
                                <th width="5%" >No</th>
								<th width="20%">Assets</th>
								<th width="60%">Info</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="8" class="pagination">
								<?=$pagination['all']?>
								</td>
							</tr>
						</tfoot>
						<tbody>
						<?php if($data): $i = isset($offset) ? $offset : $idx; ?>
							<?php  foreach($data as $v): ?>
								<tr>
								 <td style="padding:2px;vertical-align:top;">
								 <input style="width:5px" type="checkbox" name="cid[]" value="<?=$v['asset_id']?>|<?=$v['asset_type']?>|<?=$v['asset_platform']?>"/>
								 </td>
								<td style="vertical-align:top;"><?=++$i?></td>
									<td>
										<img src="<?php echo site_url('image/campaign').'?src=thumb_'.$v['asset_basename']?>"/>
									</td>
									<td style="vertical-align:top;">
									<?php echo ucfirst($v['asset_name']);?><Br/>
									<?php echo strtoupper($v['asset_type']);?> >> <?php echo strtoupper($v['asset_platform']);?><Br/>
									<?php echo "w = ".$v['asset_width']."px h = ".$v['asset_height']."px";?><Br/>
									<a href="<?php echo site_url('image/campaign').'?src='.$v['asset_basename']?>" target="_blank">
									ImageURL
									</a>
									&nbsp;&nbsp;
									<a href="<?=site_url('admin/assets/add/'.$v['asset_id'])?>">
									Edit
									</a>
									</td>
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