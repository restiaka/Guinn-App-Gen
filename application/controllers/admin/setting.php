<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Setting extends CI_Controller {

  protected $db;
	
	function __construct()
	{
	    parent::__construct();
		$this->load->model('form_m','form');
		
	}
	
		
	public function getMsg($type){
		$template = array("submission_true"=>"Your data has been successfuly submitted.",
					 "submission_false"=>"Submission has Failed, Please Try Again.",
					 "update_true"=>"Item(s) has been successfuly updated.",
					 "update_false"=>"Item(s) has failed to be updated. Please Try Again.",
					 "delete_true"=>"Item(s) has been successfuly deleted",
					 "delete_false"=>"Item(s) has failed to be deleted. Please Try Again.");
					 
	    return isset($template[$type]) ? $template[$type] : '';	   
	}	
	
	
	function index()
	{
	 		$this->load->view('admin/setting',array('content'=> $this->form->setting_form()));

	}
	
	
	
	
	
}