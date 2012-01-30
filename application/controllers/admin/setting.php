<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Setting extends CI_Controller {

  protected $db;
	
	function __construct()
	{
	    parent::__construct();
		$this->load->model('form_m','form');
		
	}
	
	function index()
	{
	 		$this->load->view('admin/setting',array('content'=> $this->form->setting_form()));

	}
	
	
	
	
	
}