<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Template extends CI_Controller {

  protected $db;
 	
	function __construct()
	{
		parent::__construct();
	}
	
	function mcetemplate(){
	  $this->load->view('admin/template_list');
	}
	
	function snippet1(){
	 $this->load->view('admin/tinymce_templates/snippet1');
	}
	
	function layout1(){
	 $this->load->view('admin/tinymce_templates/layout1');
	}
}