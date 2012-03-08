<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<div class="main">

<div class="box box-m">
  <div class="inner">
  <h2 class="title">Quick Registration</h2>
 <div><?php echo printNotification()?></div>
 <?php echo $html_form_register?>
  </div>
</div>

</div>
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>