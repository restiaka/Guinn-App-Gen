<?php
Class Mobile extends CI_Controller {

	function __construct()
	{
	 parent::__construct();
	 $this->load->library('ezsql_mysql');
	 $this->load->model('campaign_m','campaign');
	 $this->load->model('form_m','form');
	 $this->load->model('media_m','media');
	}

	function index(){
		$this->load->view('mobile_home');
	}

	function about(){
		$this->load->view('mobile_about');
	}

	function login(){
		echo "login";
	}

	function gallery(){
		$this->load->view('mobile_gallery');
	}

	function rules(){
		$this->load->view('mobile_rules');
	}
}