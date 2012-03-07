<?php echo $this->load->view('site/header',$campaign,true); //Begin HTML ?>

<div>
<table>
<?php if($media): foreach($media as $row):?>
<td><?php echo $this->showMedia($row);?></td>
<td><fb:name uid="<?php echo $row['uid']?>"></fb:name></td>
<?php endforeach; endif;?>
</table>
</div>
	
<?php echo $this->load->view('site/footer',$campaign,true);//End HTML ?>