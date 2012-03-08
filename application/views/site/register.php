<?php echo $this->load->view('site/header',$campaign,true); ?>
<div class="main">

<div class="box box-m">
  <div class="inner">
  <h2 class="title">Quick Registration</h2>
 <div><?php echo printNotification()?></div>
 <?php echo $html_form_register?>
  </div>
</div>

</div>
<?php echo $this->load->view('site/footer',$campaign,true);//End HTML ?>