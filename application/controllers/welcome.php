<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct() {
        parent::__construct();
		$this->load->model('master_model');
    }

	public function index()
	{
		$this->data['title']="";
		$this->data['banner_text'] = $this->master_model->get_banner_text()->banner_text;
		$this->load->view('templates/header' , $this->data);
		$this->data['questions'] = $this->master_model->get_questions();
		$this->data['answer_options'] = $this->master_model->get_answer_options();
		$this->load->view('home', $this->data);
		$this->load->view('templates/footer');
	}

	public function quiz(){
		$this->load->helper('form');
		$this->data['title']="Quiz Page";
		$this->load->view('templates/header');
		$this->data['questions'] = $this->master_model->get_questions();
		$this->data['answer_options'] = $this->master_model->get_answer_options();
		$this->load->view('users/play_quiz' , $this->data);
		$this->load->view('templates/footer');
	}

	public function pagination(){
		$this->load->library("pagination");
		$config = array();
		$config["base_url"]="#";
		$config["total_rows"]=$this->master_model->count_all();
		$config["per_page"] = 4;
		$config["uri_segment"]=3;
		$config["use_page_numbers"]=TRUE;
		
	}
}
