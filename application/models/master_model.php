<?php
class Master_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function get_banner_text(){
       $this->db->select("banner_text")->from('banner_text')->where('status',1);
       $query = $this->db->get();
       return $query->row();
    }

    function get_pages_count($per_page){
        $query = $this->db->get("question");
        
        return ceil($query->num_rows() / $per_page)  ;
    }

    /* function get_questions(){
        $this->db->select('question.question_id , question , explanation')
            ->from('question')
            ->order_by('question.question_id','asc');
            // ->limit($limit , $start);
        $query = $this->db->get();
        $result =  $query->result();
        if($result){
            return $result;       
           }else{
               return false;
           }
    } */

    function get_questions($limit , $start ) { 
        $this->db->select('question.question_id , question , explanation')
            ->from('question')
            ->order_by('question.question_id','asc')
            ->limit($limit , $start);
        $query = $this->db->get();
        $result =  json_encode( $query->result() , JSON_PRETTY_PRINT);
        if($result){
            return $result;       
           }else{
               return false;
           }
    }

    function get_answer_options_by_question_id($question_id) {
        $this->db->select('answer_option_id , answer , correct_option , question_id , answer_image')
            ->from('answer_option')
            ->order_by('answer_option_id','asc')
            ->where('answer_option.question_id' , $question_id);
        $query = $this->db->get();
        $result = json_encode($query->result());
        if($result){
            return $result;       
           }else{
               return false;
           }
    }

    function get_answer_options() {
        $this->db->select('answer_option_id , answer , correct_option , question_id , answer_image')
            ->from('answer_option')
            ->order_by('answer_option_id','asc');
        // ->where('answer_option.question_id' , $question_id);
        $query = $this->db->get();
        $result =  $query->result();
        if($result){
            return $result;       
           }else{
               return false;
           }
    }

    function get_groups() { 
        $this->db->select('*')->from('groups');
        $query = $this->db->get();
        $result =  $query->result();
        if($result){
            return $result;       
           }else{
               return false;
           }
    }

    function get_sub_groups(){
        $this->db->select('*')->from('sub_groups');
        $query = $this->db->get();
        $result =  $query->result();
        if($result){
            return $result;       
           }else{
               return false;
           }
    }

    function get_languages(){
        $this->db->select('language_id , language')->from('language');
        $query = $this->db->get();
        $result =  $query->result();
        if($result){
            return $result;       
           }else{
               return false;
           }
    }

    function get_question_levels() {
        $this->db->select('*')->from('question_level');
        $query = $this->db->get();
        $result =  $query->result();
        if($result){
            return $result;       
           }else{
               return false;
           }
    }

    function get_question_status() {
        $this->db->select('*')->from('question_status');
        $query = $this->db->get();
        $result =  $query->result();
        if($result){
            return $result;       
           }else{
               return false;
           }
    }

    function create_question() {
        $data=array(
			'question'=>$this->input->post('question'),
			'explanation'=>$this->input->post('question_explanation'),
            'status_id'=>'1',
            // 'question_image'=>$this->input->post('question_image'), // to be filled
            // 'explanation_image'=>$this->input->post('explanation_image'), //to be filled
            'level_id'=>$this->input->post('question_level'),
            'language_id'=>$this->input->post('language'),
            'default_question_id'=>$this->input->post('language'),
            // 'created_by'=>$this->input->post(''), //get from session data
            // 'created_date_time'=>$this->input->post(''),
            // 'last_modified_by'=>$this->input->post(''),
            // 'last_modified_date_time'=>$this->input->post(''),
        );
        // var_dump($data);
        $this->db->trans_start(); //Transaction begins
        $this->db->insert('question',$data); //Insert the $data array into 'question' table 
        $question_id=$this->db->insert_id(); //Get the Question ID from the inserted record
       
         // creating tuple for link table
         $question_grouping_data =  array(
            'question_id'=>$question_id,
            'group_id'=>$this->input->post('group'),
            // 'sub_group_id'=>$this->input->post('sub_group')
        );
        // inserting the data into question group link table
        $this->db->insert('question_grouping',$question_grouping_data); 

        $answer_option=$this->input->post('answer_option'); //Get the answer options wrote by the user.
        $correct_option=$this->input->post('correct_option'); // Get the correct/incorrect value for answer options 
        // var_dump($answer_option);
        // var_dump($correct_option);
        $answer_option_data=array();
        foreach($answer_option as $key=>$value) { //loop through the answer options 
            $answer_option_data[]=array(
                'answer'=>$answer_option[$key],
                'question_id'=>$question_id,
                'correct_option'=>$correct_option[$key],
                // 'answer_image'=>$a->answer_image,
                // 'reference_note'=>$a->reference_note,
                // 'created_by'=>$a->created_by, // will get from session data
                // 'created_date_time'=>$this->input->post(''),
                // 'last_modified_by'=>$this->input->post(''),
                // 'last_modified_date_time'=>$this->input->post(''),
            );
        }
        // insert all the answer options into the answer table
        $this->db->insert_batch('answer_option' ,$answer_option_data);
       
        $this->db->trans_complete(); //Transaction Ends
		if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }


    function insert_language() {
        $language = $this->input->post('language');
        $this->db->select('language')->from('language')->where('language',$language);
        $query=$this->db->get();
        // checking if language already exists
        if($query->num_rows()>0){
            return false;
        }
        $data = array(
            'language' => strtoupper($this->input->post('language'))
        );
        $this->db->trans_start();
        $this->db->insert('language',$data);
        $this->db->trans_complete(); //Transaction Ends
        if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }

    function insert_level() {
        $level = $this->input->post('level');
        $this->db->select('level')->from('question_level')->where('level',$level);
        $query=$this->db->get();
        // checking if level already exists
        if($query->num_rows()>0){
            return false;
        }
        $data = array(
            'level' => strtoupper($level),
            'level_image' => $this->input->post('level_image')
        );
        $this->db->trans_start();
        $this->db->insert('question_level',$data);
        $this->db->trans_complete(); //Transaction Ends
        if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }

    
    function insert_group() {
        $group = $this->input->post('group');
        $this->db->select('group_name')->from('groups')->where('group_name',$group);
        $query=$this->db->get();
        // checking if group already exists
        if($query->num_rows()>0){
            return false;
        }
        $data = array(
            'group_name' => strtoupper($group),
            'group_image' => $this->input->post('group_image')
        );
        $this->db->trans_start();
        $this->db->insert('groups',$data);
        $this->db->trans_complete(); //Transaction Ends
        if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }

    function insert_sub_group() {
        $sub_group = $this->input->post('sub_group');
        $this->db->select('sub_group')->from('sub_groups')->where('sub_group',$sub_group);
        $query=$this->db->get();
        // checking if group already exists
        if($query->num_rows()>0){
            return false;
        }
        $data = array(
            'sub_group' => strtoupper($sub_group),
            'group_id' => $this->input->post('group_id'),
            'sub_group_image' => $this->input->post('sub_group_image')
        );
        $this->db->trans_start();
        $this->db->insert('sub_groups',$data);
        $this->db->trans_complete(); //Transaction Ends
        if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }

    function login($username, $password){
        $this->db->select('user.*');
        $this->db->from('user');
        $this->db->where('username', $username);
        $this->db->where('password', MD5($password));
        $query = $this->db->get();
        if($query)
        {
          return $query->row();
        }
        else
        {
          return false;
        }
     }

     function create_user(){
        $data = array(
            'first_name'=>$this->input->post('first_name'),
            'last_name'=>$this->input->post('last_name'),
            'email'=>$this->input->post('email'),
            'phone'=>$this->input->post('phone'),
            'gender'=>$this->input->post('gender'),
            'note'=>$this->input->post('note'),
            'username'=>$this->input->post('username'),
            'password'=>md5($this->input->post('password')),
        );
        $this->db->trans_start(); //Transaction begins
        $this->db->insert('user',$data); //Insert the $data array into 'user' table 
        $user_id=$this->db->insert_id(); //Get the User ID from the inserted record


         // creating tuple for user access table
         $user_access_data =  array(
            'user_id'=>$user_id,
            'language_id'=>$this->input->post('language'),
            // 'expiry_date'=>$this->input->post('expiry_date')
        );
        $this->db->insert('user_access',$user_access_data); 
        $this->db->trans_complete(); //Transaction Ends
		if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
     }

     function change_password($user_id) {
         //select the old password from the database
        $this->db->select('password')->from('user')->where('user_id',$user_id);
		$query=$this->db->get();
        $password=$query->row();
        $form_password=$this->input->post('old_password'); //get the old password from the form
        if($password->password==md5($form_password)){ //match both the old passwords
			$this->db->where('user_id',$user_id); //search for the user in db
			if($this->db->update('user',array('password'=>md5($this->input->post('password'))))){ 
				//if the user table has been updated successfully, return true else false.
				return true;
				}
			else return false;
		}
		else return false; //if the old password entered doesn't match the database password, return false.
     }


}
