<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accomodation extends CI_Controller {


	public function index()
	{
		$this->load->view('template/header');
        $this->load->view('accomodation');
        $this->load->view('template/footer');
	}
}
