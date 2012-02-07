<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Policy extends CI_Controller {
	public function index()
	{
		$this->load->view('site/policy');
	}
}
