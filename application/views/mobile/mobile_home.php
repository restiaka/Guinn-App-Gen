<?php $this->load->view('mobile/mobile_header'); //Begin HTML ?>
<div>
        <ul>
		  <li>
            <a href="<?=mobile_menu_url('about')?>">About The Contest</a>
          </li>
          <li>
            <a href="<?=mobile_menu_url('login')?>">Login And Authorize</a>
          </li>
          <li>
            <a href="<?=mobile_menu_url('gallery')?>">Gallery</a>
          </li>
		  <li>
            <a href="<?=mobile_menu_url('rules')?>">Rules/FAQ</a>
          </li>
        </ul>
</div>
<?php $this->load->view('mobile/mobile_footer');//End HTML ?>