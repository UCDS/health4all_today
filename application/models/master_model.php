<?php
class Master_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function get_banner_text(){
       $this->db->select("banner_text")->from('banner_text')->where('status',1);
       $query = $this->db->get();
       $result =  $query->result();
        if($result){
            return $result;       
        }else{
            return false;
        }
    }

    function get_pagination_data($per_page ,$group , $sub_group , $question_level, $language){
        if($sub_group != 0){
			$this->db->where('question_grouping.sub_group_id' , $sub_group);
		}
        if($question_level != 0){
			$this->db->where('question.level_id' , $question_level);
		}
        if($language != 0){
			$this->db->where('question.language_id' , $language);
		}
        $this->db->select("question.question_id")
                ->from('question')
                ->join('question_grouping','question.question_id=question_grouping.question_id','inner')
                ->where('question_grouping.group_id' , $group);
        $query = $this->db->get();
        $result =  (object)["questions_count"=>$query->num_rows() , "pages_count"=>ceil($query->num_rows()/ $per_page)];
        if($result){
            return $result;       
           }else{
               return false;
           }
    }

    
    function get_questions($limit , $start  , $group , $sub_group , $question_level, $language) { 
        
        $logged_in=$this->session->userdata('logged_in'); 
        
        if($sub_group != 0){
			$this->db->where('question_grouping.sub_group_id' , $sub_group);
		}
        if($question_level != 0){
			$this->db->where('question.level_id' , $question_level);
        }
        if($language != 0){
			$this->db->where('question.language_id' , $language);
        }
        if(!$logged_in){
            $this->db->where('question.status_id' , 1);
        }

        $this->db->select('question.question_id, question, explanation, question_image, explanation_image, status_id as status')
            ->from('question')
            ->join('question_grouping','question.question_id=question_grouping.question_id','inner')
            ->where('question_grouping.group_id' , $group)
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

    function get_question_by_id($question_id){
        $this->db->select('question.question_id, question, question_image, explanation, explanation_image, level_id, language_id')
            ->from('question')
            ->join('question_grouping', 'question.question_id=question_grouping.question_id', 'inner')
            ->where('question.question_id', $question_id);
        $query = $this->db->get();
        $result = $query->result();
        if($result){
            return $result;       
            }else{
                return false;
            }
    }

    function get_group_info_by_question_id($question_id){
        $this->db->select('grouping_id, group_id,sub_group_id')
            ->from('question_grouping')
            ->where('question_grouping.question_id' , $question_id);
        $query = $this->db->get();
        $result = $query->result();
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
            'question_image'=>$this->input->post('question_image'), // to be filled
            'explanation_image'=>$this->input->post('explanation_image'), //to be filled
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
         $groups = $this->input->post('group');
         $sub_groups = $this->input->post('sub_group');
         $question_grouping_data = array();
         foreach($groups as $key=>$value){
            $question_grouping_data[]= array(
                'question_id'=>$question_id,
                'group_id'=>$groups[$key],
                'sub_group_id'=>$sub_groups[$key]
            ); 
         }

        //  var_dump($groups);
        //  var_dump($sub_groups);
         
        // inserting the question grouping data into question group link table
        $this->db->insert_batch('question_grouping',$question_grouping_data); 

        $answer_option=$this->input->post('answer_option'); //Get the answer options wrote by the user.
        $correct_option=$this->input->post('correct_option'); // Get the correct/incorrect value for answer options 
        $answer_option_image=$this->input->post('answer_option_image'); // Get the image names for answer options 
        // var_dump($answer_option);
        // var_dump($correct_option);
        $answer_option_data=array();
        foreach($answer_option as $key=>$value) { //loop through the answer options 
            $answer_option_data[]=array(
                'answer'=>$answer_option[$key],
                'question_id'=>$question_id,
                'correct_option'=>$correct_option[$key],
                'answer_image'=>$answer_option_image[$key],
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

    function update_question($question_id) {
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
            'last_modified_date_time'=>date("Y-m-d H:i:s")
        );
        // var_dump($data);  

        $this->db->trans_start(); //Transaction begins
        $this->db->where('question_id',$question_id);
        $this->db->update('question',$data); //updating the question w.r.t question_id

        // $groups = $this->input->post('group');
        // $sub_groups = $this->input->post('sub_group'); 
        // var_dump($groups);
        // var_dump($sub_groups);

        $answer_option=$this->input->post('answer_option'); //Get the answer options wrote by the user.
        $correct_option=$this->input->post('correct_option'); // Get the correct/incorrect value for answer options 
        // var_dump($answer_option);
        // var_dump($correct_option);
        $answer_option_data=array();
        foreach($answer_option as $key=>$value) { //loop through the answer options 
            $answer_option_data[]=array(
                'answer_option_id'=>$key,
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
        $this->db->update_batch('answer_option',$answer_option_data, 'answer_option_id');
        //clean up , redundant data:  delete all other answer options whose key is not present in the $answer_option and has given question_id
        $to_be_deleted_answer_option_ids = array_keys($answer_option);
        $this->db->where('question_id',$question_id);
        $this->db->where_not_in('answer_option_id', $to_be_deleted_answer_option_ids);
        $delete_question_answer_options = $this->db->delete('answer_option');


        $new_answer_option=$this->input->post('new_answer_option'); //Get the new answer options wrote by the user.
        $new_correct_option=$this->input->post('new_correct_option'); // Get the new correct/incorrect value for answer options 

        $new_answer_option_data=array();
        if(isset($new_answer_option)){
            foreach($new_answer_option as $key=>$value) { //loop through the new answer options 
                $new_answer_option_data[]=array(
                    'answer'=>$new_answer_option[$key],
                    'question_id'=>$question_id,
                    'correct_option'=>$new_correct_option[$key],
                    // 'answer_image'=>$a->answer_image,
                    // 'reference_note'=>$a->reference_note,
                    // 'created_by'=>$a->created_by, // will get from session data
                    // 'created_date_time'=>$this->input->post(''),
                    // 'last_modified_by'=>$this->input->post(''),
                    // 'last_modified_date_time'=>$this->input->post(''),
                );
            }
            // insert all the answer options into the answer table
            $this->db->insert_batch('answer_option' ,$new_answer_option_data);
        }
       
        $this->db->trans_complete(); //Transaction Ends
		if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }

    function delete_question($question_id){
        $this->db->trans_start(); //Transaction begins

        $this->db->select("*")->from('question')->where('question_id',$question_id);
        $result = $this->db->get();
        if(!$result){
            return "question does not exists";
        }
        // deleting question from question table
        $this->db->where('question_id' , $question_id);
        $delete_question = $this->db->delete('question');
        
        // deleting records of this question from question_grouping 
        $this->db->where('question_id' , $question_id);
        $delete_question_grouping_data = $this->db->delete('question_grouping');
        
        // deleting records of options from answer_option table 
        $this->db->where('question_id' , $question_id);
        $delete_question_answer_options = $this->db->delete('answer_option');
        
        if(!$delete_question || !$delete_question_grouping_data || !$delete_question_answer_options ){
            $this->db->trans_rollback();
            return -1;
        }
        return 1;
    }

    // Toggles the question status between 1 and 0 
    function toggle_question_status($question_id){
        $this->db->trans_start(); //Transaction begins
        $this->db->select("status_id")->from('question')->where('question_id',$question_id);
        $result = $this->db->get();
        
        if(!$result){
            return "question does not exists";
        }
        $current_status_id = $result->row()->status_id;
        $status_id = $current_status_id == "1" ? "0" : "1";
        $this->db->where('question_id' , $question_id);
        $updated = $this->db->update('question',array('status_id'=>$status_id));
        //Transaction ends here.
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		}
		else {
        return [true , $status_id];
        }
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

     function upload_image(){
        if($this->input->post('image')){
            $image_name = strtoupper($this->input->post('image_name'));
			$image = $this->input->post('image_val');
            $extension = explode('/', mime_content_type($image))[1];
            $imgdata =  explode(",", $image)[1];
            $imgdata = base64_decode($imgdata);
			// save to server (beware of permissions)
			$result = file_put_contents("assets/images/quiz/$image_name.jpeg", $imgdata );
			if (!$result) die("Could not save image!  Check file permissions.");
		}
        return true;
    }

}
