<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    function __construct() {
        parent::__construct();
		$this->load->model('master_model');
    }

	public function index()
	{
		$this->load->view('templates/header');
		$this->load->view('admin/admin_functions');
		$this->load->view('templates/footer');
	}

	public function login()
	{

	}

	public function create_user()
	{
		// if($this->session->userdata('logged_in')){
			$this->load->helper('form');
			$this->data['title']="Create User";
			$this->load->view('templates/header');
			$this->load->view('admin/create_user' , $this->data);
			// $this->data['userdata']=$this->session->userdata('logged_in');
			$this->load->view('templates/footer');

		// }

	}

	public function create_question()
	{
		$this->load->helper('form');
		$this->data['title']="Create Question";
		$this->load->view('templates/header');
		$this->data['languages'] = $this->master_model->get_languages();
		$this->data['groups'] = $this->master_model->get_groups();
		$this->data['sub_groups'] = $this->master_model->get_sub_groups();
		$this->data['question_levels'] = $this->master_model->get_question_levels();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('question','question','required');
		if ($this->form_validation->run() === FALSE) {
			$this->load->view('admin/create_question',$this->data);
		} else {
			if($this->master_model->create_question()){
				$this->data['msg']="Question created successfully";
				$this->load->view('admin/create_question',$this->data);
			} else {
				$this->data['msg']="Error creating question. Please retry.";
				$this->load->view('admin/create_question',$this->data);
			}
		}
		$this->load->view('templates/footer');
	}

}
