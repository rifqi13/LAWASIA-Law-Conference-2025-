<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
		$this->load->view('template/header');
        $this->load->view('program');
        $this->load->view('template/footer');
	}
}
