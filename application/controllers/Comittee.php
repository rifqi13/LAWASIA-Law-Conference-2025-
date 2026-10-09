<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comittee extends CI_Controller {


	public function index()
	{
		$this->load->view('template/header');
        $this->load->view('comittee');
        $this->load->view('template/footer');
	}
}
