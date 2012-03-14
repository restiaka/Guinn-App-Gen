<?php echo $this->load->view('site/header',$campaign,true); ?>
<div class="main">

<div class="box box-m">
  <div class="inner">
  <h2 class="title">Upload Your <?php echo $campaign['allowed_media_type'] == 'image' ? 'Photo\'s' : 'Video\'s';?></h2>
  <p>Just a few seconds away, choose your best photo with Guinness photo booth and upload now.</p>
  <?php echo $html_form_upload?>
  </div>
</div>

</div>
<?php echo $this->load->view('site/footer',$campaign,true); ?>