<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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
		$this->data['title']="Quiz";
		$this->data['edit_question_access']=0;
		$this->data['remove_question_access']=0;
		$this->data['edit_question_status_access']=0;
		if($this->session->userdata('logged_in')){
			foreach($this->data['functions'] as $f){
				if($f->user_function=="question"){
					if($f->edit)
						$this->data['edit_question_access']=1;
					if($f->remove)
						$this->data['remove_question_access']=1;
				}
				if($f->user_function=="question_status"){
					if($f->edit)
						$this->data['edit_question_status_access']=1;
				}
			}
		}
		$this->load->view('templates/header' , $this->data);
		$this->data['display_max_height'] = $this->master_model->get_defaults('display_max_height');
		$this->data['display_max_width'] = $this->master_model->get_defaults('display_max_width');
		$this->data['bootstrap_question_col_values'] = $this->master_model->get_defaults('bootstrap_question_col_values');
		$this->data['display_images'] = $this->master_model->get_defaults('display_images');
		$this->data['default_quiz_language'] = $this->master_model->get_defaults('default_quiz_language');
		$this->data['user_display_images'] = $this->master_model->get_defaults('user_display_images');
		$this->data['display_transliterate'] = $this->master_model->get_defaults('display_transliterate');
		$this->data['user_display_transliterate'] = $this->master_model->get_defaults('user_display_transliterate');
		$this->data['groups'] = $this->master_model->get_groups();
		$this->data['sub_groups'] = $this->master_model->get_sub_groups();
		$this->data['question_levels'] = $this->master_model->get_question_levels();
		$this->data['languages'] = $this->master_model->get_languages();
		// $this->data['questions'] = $this->master_model->get_questions();
		// $this->data['answer_options'] = $this->master_model->get_answer_options();
		$this->load->view('home', $this->data);
		$this->load->view('templates/footer' ,$this->data);
	}

	public function quiz($page , $group , $sub_group, $question_level, $language, $transliterate_language){
		// $this->data['title']="Quiz page";
		$per_page = 10;
		$start = ($page -1 ) * $per_page;
		$question_answers_list = array();
		$questions =  $this->master_model->get_questions($per_page ,$start , $group , $sub_group , $question_level, $language);
		foreach( json_decode($questions) as $q){
			$question_answers_list[$q->question_id]  = (object)[ 
				"question"=>$q, 
				"answers"=> json_decode($this->master_model->get_answer_options_by_question_id($q->question_id)),
				"transliterate"=> json_decode($this->master_model->get_transliterate_data_by_question_and_language_id($q->question_id, $transliterate_language))
			];
		}
		$question_answers_list = array_values($question_answers_list); 
		print json_encode($question_answers_list);				
						
	}


	public function get_pagination_data( $group="", $sub_group="",  $level="", $language="" ){
		$rows_per_page="10"; 
		$pagination_data = $this->master_model->get_pagination_data($rows_per_page, $group, $sub_group, $level, $language);
		// echo json_encode($pagination_data);
		print json_encode($pagination_data);
	}

	public function delete_question($question_id){
		$deleted = $this->master_model->delete_question($question_id);
		print json_encode($deleted);
	}

	public function toggle_question_status($question_id){
		$updated = $this->master_model->toggle_question_status($question_id);
		print json_encode($updated);
	}
	
	public function update_question($question_id){
		if($this->session->userdata('logged_in')){
			$edit_question_access = 0;
			foreach($this->data['functions'] as $f){
				if($f->user_function=="question" && $f->edit) 	$edit_question_access=1;  	
			}
			if($edit_question_access) {
				$this->load->helper('form');
				$this->load->helper('directory');
				$this->data['title']="Update Question";
				$this->load->view('templates/header' , $this->data);
				$this->data['question_id'] = $question_id;
				$this->data['display_max_width'] = $this->master_model->get_defaults('display_max_width');
				$this->data['languages'] = $this->master_model->get_languages();
				$this->data['groups'] = $this->master_model->get_groups();
				$this->data['sub_groups'] = $this->master_model->get_sub_groups();
				$this->data['question_levels'] = $this->master_model->get_question_levels();
				$this->data['question_details']=$this->master_model->get_question_by_id($question_id);
				$this->data['answer_details']=$this->master_model->get_answer_options_by_question_id($question_id);
				$this->data['tranliterate_details']=$this->master_model->get_transliterate_data_by_question_id($question_id);
				$this->data['grouping_details']=$this->master_model->get_group_info_by_question_id($question_id);
				$images_list = directory_map("./assets/images/quiz",TRUE,FALSE);
					foreach($images_list as &$image_name){
						$image_name = pathinfo($image_name)['filename'];
					}
				sort($images_list);
				$this->data['images_list']= $images_list;
				$this->load->library('form_validation');
				$this->form_validation->set_rules('question','question','required');
				if ($this->form_validation->run() === FALSE) {
					$this->load->view('admin/update_question',$this->data);
				} else {
					if($this->master_model->update_question($question_id)){
						$this->data['question_details']=$this->master_model->get_question_by_id($question_id);
						$this->data['answer_details']=$this->master_model->get_answer_options_by_question_id($question_id);
						$this->data['tranliterate_details']=$this->master_model->get_transliterate_data_by_question_id($question_id);
						$this->data['grouping_details']=$this->master_model->get_group_info_by_question_id($question_id);
						$this->data['status']=200;
						$this->data['msg']="Question updated successfully";
						$this->load->view('admin/update_question',$this->data);
					} else {
						$this->data['status']=500;
						$this->data['msg']="Error creating question. Please retry.";
						$this->load->view('admin/update_question',$this->data);
					}
				}
				$this->load->view('templates/footer' , $this->data);
			} else {
				show_404();
			}
		} else{
			show_404();
		}

		// print json_encode($question_data);
	}

}
