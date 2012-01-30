<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('form_m','form_model');
	}
	
	function index()
	{ 
		if(!$this->auth->getAuth())
		 $this->load->view('admin/login',array('content'=>$this->form_model->generate_form_login()));
		else
		 redirect('admin/dashboard');
	}
	
	function off()
	{
	 if($this->auth->getAuth()){
		$this->auth->logout();
		redirect('admin/login');
	  }else{
		redirect('admin/login');
	  }
	}
	
	
	
}
