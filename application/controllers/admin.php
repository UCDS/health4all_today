<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    function __construct() {
        parent::__construct();
		$this->load->model('master_model');
		if($this->session->userdata('logged_in')){
			$userdata = $this->session->userdata('logged_in');
			$user_id = $userdata['user_id'];
		}
    }

	public function index()
	{
		$this->data['title']="Home";
		if($this->session->userdata('logged_in')){
			$this->data['title']="Home";
			$this->data['userdata']=$this->session->userdata('logged_in');
			$this->load->view('templates/header' , $this->data);
			$this->load->view('admin/admin_functions' , $this->data);
		} else {
			$this->load->view('templates/header' , $this->data);
			$this->load->view('welcome' , $this->data);
		}
		$this->load->view('templates/footer');
	}

	public function login()
	{
		$this->load->helper('form');
		if($this->session->userdata('logged_in')){
			$this->data['title']="Home";
			$this->load->view('templates/header' , $this->data);
			$this->load->view('admin/admin_functions' , $this->data);
		}
		else {
			if(!$this->session->userdata('logged_in')){
				$this->data['title']="Login";
				$this->load->view('templates/header',$this->data);
				$this->load->helper('form');
				$this->load->library('form_validation');				
				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
				if ($this->form_validation->run() === FALSE)
				{
					$this->load->view('admin/login');
				}
				else{
					redirect('admin', 'refresh');
				}	
			}
			else {
				redirect('admin','refresh');
			}
		}
		$this->load->view('templates/footer');		
	}

	function check_database($password){
		$this->load->model('master_model');
		//Field validation succeeded.  Validate against database
		$username = $this->input->post('username');
		$result = $this->master_model->login($username, $password);
		if($result) {
			$sess_array = array(
				'user_id' => $result->user_id,
				'username' => $result->username,
				'email'=>$result->email,
				'admin'=>$result->admin
				);
			$this->session->set_userdata('logged_in', $sess_array);
			return TRUE;
		} else {
			$this->form_validation->set_message('check_database','Invalid Username or Password');
	     return false;
		}
	}

	function logout()
	 {
	   $this->session->sess_destroy();
	   redirect('welcome', 'refresh');
	 }


	public function create_user()
	{
		if($this->session->userdata('logged_in')){
			$this->load->helper('form');
			$this->data['title']="Create User";
			$this->load->view('templates/header' , $this->data);
			$this->data['languages'] = $this->master_model->get_languages();
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			if ($this->form_validation->run() === FALSE){
				$this->load->view('admin/create_user' , $this->data);
			} else {
				if($this->master_model->create_user()){
					$this->data['msg']="User created successfully";
					$this->load->view('admin/create_user',$this->data);
				} else {
					$this->data['msg']="Error creating user. Please retry.";
					$this->load->view('admin/create_user',$this->data);
				}
			}
			$this->load->view('templates/footer');

		} else{
			show_404();
		}

	}

	public function create_question()
	{
		if($this->session->userdata('logged_in')){
			$this->load->helper('form');
			$this->data['title']="Create Question";
			$this->load->view('templates/header' , $this->data);
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
		} else{
			show_404();
		}
	}

	public function change_password() {
		if($this->session->userdata('logged_in')){
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->data['title']="Change password";
			$this->data['userdata']=$this->session->userdata('logged_in');
			$user_id=$this->data['userdata']['user_id'];
			$this->load->view('templates/header',$this->data);
			$this->form_validation->set_rules('password','Password','required|trim|xss_clean');
			if ($this->form_validation->run() === FALSE)
			{
				$this->load->view('admin/change_password',$this->data);
			}
			else {
				if($this->master_model->change_password($user_id)){
					$this->data['msg']="Password has been changed successfully";
				}
				else{
					$this->data['msg']="Password could not be changed";
				}
				$this->load->view('admin/change_password',$this->data);
			}

		} else{
			show_404();
		}
	}

	public function create_group(){
		if($this->session->userdata('logged_in') &&  $this->session->userdata('logged_in')['admin']==1){
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->data['title']="Create Group";
			$this->load->library('form_validation');
			$this->load->view('templates/header',$this->data);
			$this->form_validation->set_rules('group','group','required');
			if ($this->form_validation->run() === FALSE){
				$this->load->view('admin/create_group' , $this->data);
			} else {
				if($this->master_model->insert_group()){
					$this->data['msg']="Group created successfully";
					$this->load->view('admin/create_group',$this->data);
				} else {
					$this->data['msg']="Error creating group. Please retry.";
					$this->load->view('admin/create_group',$this->data);
				}
			}
			$this->load->view('templates/footer');
		} else {
			show_404();
		}
	}

	public function create_language(){
		if($this->session->userdata('logged_in') &&  $this->session->userdata('logged_in')['admin']==1){
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->data['title']="Create Language";
			$this->load->library('form_validation');
			$this->load->view('templates/header',$this->data);
			$this->form_validation->set_rules('language','language','required');
			if ($this->form_validation->run() === FALSE){
				$this->load->view('admin/create_language' , $this->data);
			} else {
				if($this->master_model->insert_language()){
					$this->data['msg']="Group created successfully";
					$this->load->view('admin/create_language',$this->data);
				} else {
					$this->data['msg']="Error creating group. Please retry.";
					$this->load->view('admin/create_language',$this->data);
				}
			}
			$this->load->view('templates/footer');
		} else {
			show_404();
		}
	}

	public function create_level(){
		if($this->session->userdata('logged_in') &&  $this->session->userdata('logged_in')['admin']==1){
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->data['title']="Create Level";
			$this->load->library('form_validation');
			$this->load->view('templates/header',$this->data);
			$this->form_validation->set_rules('level','level','required');
			if ($this->form_validation->run() === FALSE){
				$this->load->view('admin/create_level' , $this->data);
			} else {
				if($this->master_model->insert_level()){
					$this->data['msg']="Group created successfully";
					$this->load->view('admin/create_level',$this->data);
				} else {
					$this->data['msg']="Error creating group. Please retry.";
					$this->load->view('admin/create_level',$this->data);
				}
			}
			$this->load->view('templates/footer');
		} else {
			show_404();
		}
	}

	public function create_sub_group(){

	}
}