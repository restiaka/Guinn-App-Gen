<?php $this->load->view('admin/header');?>
			<div id="content" class="container_16 clearfix">

			
				
				<div class="grid_16">
					<a href="#" onclick="document.getElementById('task').value='activate'; document.forms['adminform'].submit();">Activate Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='deactivate'; document.forms['adminform'].submit();">Ban Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='delete'; document.forms['adminform'].submit();">Delete Selected</a> | 
					<a href="#" onclick="document.getElementById('task').value='winner'; document.forms['adminform'].submit();">Pick Selected as WINNER</a>
					<a href="#" onclick="document.getElementById('task').value='resetwinner'; document.forms['adminform'].submit();">Reset Seleted WINNER</a>
				<br/><br/>
				</div>
				
				<div class="grid_4 omega" >
					<form name="search" method="POST" action="<?=site_url('admin/media/lists')."?pageID=1"?>">
					<div style="margin-bottom:10px;"><input type="submit" style="width:220px;" value="Go Filter"></div>
					<div style="margin-bottom:10px;">
					<select name="byorder" style="width:220px;">
						<option value="">Order by?</option>
						<option value="mostvoted" <?=($this->input->get_post('byorder', TRUE) == 'mostvoted' ? "selected='selected'" : "");?>>Most Voted</option>
						<option value="lessvoted" <?=($this->input->get_post('byorder', TRUE) == 'lessvoted' ? "selected='selected'" : "");?>>Less Voted</option>
					</select>
					</div>
					<div style="margin-bottom:10px;">
					<select name="bystatus" style="width:220px;">
								<option value="" >Pick Status?</option>
								<option value="active" <?=($this->input->get_post('bystatus', TRUE) == 'active' ? "selected='selected'" : "");?>>Active</option>
								<option value="banned" <?=($this->input->get_post('bystatus', TRUE) == 'banned' ? "selected='selected'" : "");?>>Banned</option>
								<option value="pending" <?=($this->input->get_post('bystatus', TRUE) == 'pending' ? "selected='selected'" : "");?>>Pending</option>
					</select>
					</div>
					<div style="margin-bottom:10px;">
					<select name="bycampaign" size="100" style="width:220px;">				
					<option value="" <?=(!$this->input->get_post('bycampaign', TRUE) ? "selected='selected'" : "");?> >All Campaign</option>
					<?php foreach($campaigns as $row):?>
					<option value="<?=$row['GID']?>" <?=($this->input->get_post('bycampaign', TRUE) == $row['GID'] ? "selected='selected'" : "");?>><?=$row['title']?></option>
					<?php endforeach;?>
					</select>
					</div>
					
					<input type="hidden" name="task" value="search">				
							
				
					</form>
				</div>
				<div class="grid_12">

				<?php 
				$qs = http_build_query(array('pageID'=>@$this->input->get_post('pageID', TRUE),'byuid'=>@$this->input->get_post('byuid', TRUE),'bycampaign'=>@$this->input->get_post('bycampaign', TRUE),'bystatus'=>@$this->input->get_post('bystatus', TRUE)));
				?>
				<form name="adminform" method="POST" action="<?=site_url('admin/media/lists').($qs ? '?'.$qs : '')?>">
					<table>
						<thead>
							<tr>
								<th  width="5%" >#</th>
								<th  width="5%">(<b>!</b>)</th>
                                <th width="5%" >No</th>
								<th width="50%">Media</th>
								<th width="25%">Information</th>
                                <th width="10%">Status</th>
					
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
						<?php if($data): $i = isset($offset) ? $offset : $idx; $this->load->model('media_m');$this->load->model('campaign_m');?>
							<?php  foreach($data as $v): ?>
								<tr>
								 <td style="padding:2px;vertical-align:top;<?=$v['media_winner'] ? 'background-color:#A1EAB3;' : ''?>"><input style="width:5px" type="checkbox" name="cid[]" value="<?=$v['media_id']?>"/></td>
								 <td style="padding:2px;vertical-align:top;"><input style="width:5px" type="checkbox" name="notify[]" value="<?=$v['media_id']?>"/></td>
								<td style="vertical-align:top;"><?=++$i?></td>
									<td style="vertical-align:top;">
										<div id="mediathumb" style="width:135px;float:left;">
										<?=$this->media_m->showMedia($v);?>
										<br/>
										<a href="<?=$v['media_url']?>" target="_blank">Link</a>
										<?php $u = getFacebookUser($v['uid']);?>
										<a href="http://www.facebook.com/profile.php?id=<?=$v['uid']?>" target="_blank">Author</a>
										</div>
										<div id="mediacaption" style="width:165px;float:left;">
										<?=$v['media_description']?>
										</div>
										<div style="clear:both;"></div>
									</td>
									<td style="vertical-align:top;">
									<?php $campaign = $this->campaign_m->detailCampaign($v['GID'])?>
										<?=$campaign['title']?><br/>
										<?=format_date($v['media_uploaded_date'])?> 
										<?=format_date($v['media_uploaded_date'],'time')?>
										<br/>
										<b>Vote <?=$v['media_vote_total']?></b>
									</td>
									<td style="vertical-align:top;"><?=$v['media_status']?></td>
									
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