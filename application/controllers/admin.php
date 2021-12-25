<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    function __construct() {
        parent::__construct();
		$this->load->model('master_model');
		$this->load->model('user_model');
		if($this->session->userdata('logged_in')){
			$userdata = $this->session->userdata('logged_in');
			$user_id = $userdata['user_id'];
			$this->data['functions']=$this->user_model->user_function($user_id);
			$this->data['user_languages']=$this->user_model->user_languages($user_id);
		}
		$this->data['banner_text'] = $this->master_model->get_banner_text();
		$this->data['yousee_website'] = $this->master_model->get_defaults('yousee_website');
    }

	public function index(){
		$this->data['title']="Home";
		if($this->session->userdata('logged_in')){
			$add_user_access = 0;
			$add_question_access = 0;
			$add_group_access=0;
			$add_sub_group_access=0;
			$add_image_access=0;
			$add_level_access=0;
			$add_language_access=0;
			foreach($this->data['functions'] as $f){
				if($f->user_function=="user" && $f->add) 	$add_user_access=1;  	
				if($f->user_function=="question" && $f->add) 	$add_question_access=1;  	
				if($f->user_function=="group" && $f->add)	$add_group_access=1;  	
				if($f->user_function=="sub_group" && $f->add)	$add_sub_group_access=1;  	
				if($f->user_function=="image" && $f->add)	$add_image_access=1;  	
				if($f->user_function=="language" && $f->add)	$add_language_access=1;  	
				if($f->user_function=="level" && $f->add)	$add_level_access=1;  	
			}
			$this->data['title']="Home";
			$this->data['userdata']=$this->session->userdata('logged_in');
			$this->data['add_user_access']=$add_user_access;
			$this->data['add_question_access']=$add_question_access;
			$this->data['add_group_access']=$add_group_access;
			$this->data['add_sub_group_access']=$add_sub_group_access;
			$this->data['add_image_access']=$add_image_access;
			$this->data['add_language_access']=$add_language_access;
			$this->data['add_level_access']=$add_level_access;
			$this->load->view('templates/header' , $this->data);
			$this->load->view('admin/admin_functions' , $this->data);
		} else {
			$this->load->view('templates/header' , $this->data);
			$this->load->view('welcome' , $this->data);
		}
		$this->load->view('templates/footer',$this->data);
	}

	public function login(){
		$this->load->helper('form');
		if($this->session->userdata('logged_in')){
			$this->data['title']="Login";
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
				$login = 0;
				if ($this->form_validation->run() === FALSE){
					$this->load->view('admin/login');
				} else {
					$login=1;
				}
				if($this->input->post('username') && $this->input->post('username')!=""){
					$this->user_model->save_user_signin($this->input->post('username'), $login);
				}
				if($login==1){
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
		$result = $this->user_model->login($username, $password);
		if($result) {
			$sess_array = array(
				'user_id' => $result->user_id,
				'username' => $result->username,
				'first_name' => $result->first_name,
				'last_name' => $result->last_name,
				'email'=>$result->email,
				'admin'=>$result->admin,
				'default_language_id'=>$result->default_language_id,
				);
			$this->session->set_userdata('logged_in', $sess_array);
			return TRUE;
		} else {
			$this->form_validation->set_message('check_database','Invalid Username or Password');
	     return false;
		}
	}

	function logout(){
	   $this->session->sess_destroy();
	   redirect('home', 'refresh');
	}


	public function create_user(){
		if($this->session->userdata('logged_in')){
			$add_user_access = 0;
			foreach($this->data['functions'] as $f){
				if($f->user_function=="user"){ 
					if($f->add)
						$add_user_access=1;  	
				}	
			}
			if($add_user_access){
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
					if($this->user_model->create_user()){
						$this->data['msg']="User created successfully";
						$this->load->view('admin/create_user',$this->data);
					} else {
						$this->data['msg']="Error creating user. Please retry.";
						$this->load->view('admin/create_user',$this->data);
					}
				}
				$this->load->view('templates/footer', $this->data);
			} else {
				show_404();
			}
		} else{
			show_404();
		}

	}

	public function create_question(){
		if($this->session->userdata('logged_in')){
			$add_question_access = 0;
			foreach($this->data['functions'] as $f){
				if($f->user_function=="question" && $f->add) 	$add_question_access=1;  	
			}
			if($add_question_access){
				$this->load->helper('form');
				$this->load->helper('directory');
				$this->load->library('form_validation');
				$this->data['title']="Create Question";
				$this->data['display_max_width'] = $this->master_model->get_defaults('display_max_width');
				$this->load->view('templates/header' , $this->data);
				$this->data['languages'] = $this->master_model->get_languages();
				$this->data['groups'] = $this->master_model->get_groups();
				$this->data['sub_groups'] = $this->master_model->get_sub_groups();
				$this->data['question_levels'] = $this->master_model->get_question_levels();
				
				$images_list = directory_map("./assets/images/quiz",TRUE,FALSE);
					foreach($images_list as &$image_name){
						$image_name = pathinfo($image_name)['filename'];
					}
				sort($images_list);
				$this->data['images_list']= $images_list;
				$this->form_validation->set_rules('question','question','required');
				if ($this->form_validation->run() === FALSE) {
					$this->load->view('admin/create_question',$this->data);
				} else {
					if($this->master_model->create_question()){
						$this->data['status']=200;
						$this->data['msg']="Question created successfully";
						$this->load->view('admin/create_question',$this->data);
					} else {
						$this->data['status']=500;
						$this->data['msg']="Error creating question. Please retry.";
						$this->load->view('admin/create_question',$this->data);
					}
				}
				$this->load->view('templates/footer' , $this->data);
			} else {
				show_404();
			}
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
				if($this->user_model->change_password($user_id)){
					$this->data['msg']="Password has been changed successfully";
				}
				else{
					$this->data['msg']="Password could not be changed";
				}
				$this->load->view('admin/change_password',$this->data);
				$this->load->view('templates/footer' , $this->data);
			}

		} else{
			show_404();
		}
	}

	public function create_group(){
		if($this->session->userdata('logged_in')){
			$add_group_access = 0;
			foreach($this->data['functions'] as $f){
				if($f->user_function=="group" && $f->add) 	$add_group_access=1;  	
			}
			if($add_group_access){
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
				$this->load->view('templates/footer' , $this->data);
			} else {
				show_404();	
			}
		} else {
			show_404();
		}
	}

	public function create_sub_group(){
		if($this->session->userdata('logged_in')){
			$add_sub_group_access = 0;
			foreach($this->data['functions'] as $f){
				if($f->user_function=="sub_group" && $f->add) 	$add_sub_group_access=1;  	
			}
			if($add_sub_group_access){
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->data['title']="Create Sub Group";
				$this->load->library('form_validation');
				$this->load->view('templates/header',$this->data);
				$this->data['groups'] = $this->master_model->get_groups();
				$this->form_validation->set_rules('group_id','group_id','required');
				$this->form_validation->set_rules('sub_group','sub_group','required');
				if ($this->form_validation->run() === FALSE){
					$this->load->view('admin/create_sub_group' , $this->data);
				} else {
					if($this->master_model->insert_sub_group()){
						$this->data['msg']="Sub group created successfully";
						$this->load->view('admin/create_sub_group',$this->data);
					} else {
						$this->data['msg']="Error creating sub group. Please retry.";
						$this->load->view('admin/create_sub_group',$this->data);
					}
				}
				$this->load->view('templates/footer' , $this->data);
			} else {
				show_404();	
			}
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
			$this->load->view('templates/footer', $this->data);
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
			$this->load->view('templates/footer',$this->data);
		} else {
			show_404();
		}
	}

	public function upload_image(){
		if($this->session->userdata('logged_in')){
			$add_image_access = 0;
			foreach($this->data['functions'] as $f){
				if($f->user_function=="image" && $f->add) 	$add_image_access=1;  	
			}
			if($add_image_access){
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->data['title']="Upload image";
				$this->load->view('templates/header',$this->data);
				$this->form_validation->set_rules('image','image','required');
				if ($this->form_validation->run() === FALSE){
					$this->load->view('admin/upload_image' , $this->data);
				} else {
					if($this->master_model->upload_image()){
						$this->data['msg']="Image uploaded successfully";
						$this->load->view('admin/upload_image',$this->data);
					} else {
						$this->data['msg']="Error creating group. Please retry.";
						$this->load->view('admin/upload_image',$this->data);
					}
				}
				$this->load->view('templates/footer',$this->data);
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
}