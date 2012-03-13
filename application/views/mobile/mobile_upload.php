<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<ul class="breadcrumb">
  <li><a href="<?=mobile_menu_url()?>" data-prefetch>Home</a></li>
  <li>Upload</li>
</ul>

<div class="main">
<div class="box box-m">
  <div class="inner">
  <h2 class="title">Upload Your's</h2>
  <p>Just a few seconds away, choose your best photo with Guinness photo booth and upload now.</p>
  <?php if(isset($html_form_upload)){ echo $html_form_upload; }?>
  </div>
</div>

</div>
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>