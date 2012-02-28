<?php $this->load->view('site/header'); //Begin HTML ?>
<?php $this->load->view('site/header_main_navigation'); //Begin HTML ?>

<div>
<table>
<?php if($media): foreach($media as $row):?>
<td><?php echo $this->showMedia($row);?></td>
<td><fb:name uid="<?php echo $row['uid']?>"></fb:name></td>
<?php endforeach; endif;?>
</table>
</div>
	
<?php $this->load->view('site/footer');//End HTML ?>