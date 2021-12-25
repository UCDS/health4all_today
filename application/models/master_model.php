<?php
class Master_model extends CI_Model {

    private $user_language_ids =[];
    private $user_id ='';
    private $logged_in = NULL;
    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->logged_in = $this->session->userdata('logged_in');
        $this->user_id = $this->logged_in['user_id'];
        $user_languages=$this->user_model->user_languages($this->user_id);
        foreach ($user_languages as $key => $value) {
            array_push($this->user_language_ids, $value->language_id);
        }
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

    function get_defaults($id) {
        $this->db->select('*')->from('defaults')->where('default_id',$id);
       $query = $this->db->get();
       $result =  $query->result();
        if($result){
            return $result;       
        }else{
            return false;
        }
    }

    function get_pagination_data($per_page ,$group , $sub_group , $question_level, $language){
        if($this->logged_in) {
            $this->db->where_in('question.language_id', $this->user_language_ids);
        }
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
        $question_sequence = array();
        $sequence_exists = FALSE;
        $this->db->select('group_id, sub_group_id, language_id, sequence, created_by, created_datetime, updated_by, updated_datetime')
            ->from('question_sequence')
            ->where('group_id' , $group)
            ->where('sub_group_id' , $sub_group)
            ->where('language_id' , $language);
        $query = $this->db->get();    
        $question_sequence_result = $query->row();
        if($question_sequence_result) {
            $sequence_exists=TRUE;
            array_push($question_sequence, $question_sequence_result->sequence);
        }

        if($this->logged_in) {
            $this->db->where_in('question.language_id', $this->user_language_ids);
        }
        if($sub_group != 0){
			$this->db->where('question_grouping.sub_group_id' , $sub_group);
		}
        if($question_level != 0){
			$this->db->where('question.level_id' , $question_level);
        }
        if($language != 0){
			$this->db->where('question.language_id' , $language);
        }
        if($limit && $start){
            $this->db->limit($limit , $start);
        }

        if(!$this->logged_in){
            $this->db->where('question.status_id' , 1);
        }

        $this->db->select('question.question_id, question, explanation, question_image, question_image_width, explanation_image, explanation_image_width, status_id as status')
            ->from('question')
            ->join('question_grouping','question.question_id=question_grouping.question_id','inner')
            ->where('question_grouping.group_id' , $group)
            ->order_by('question.question_id','asc');
        $query = $this->db->get();
        // var_dump($query->result());
        $questions = $query->result();
        $questions_sequenced = array();
        foreach ($question_sequence as $s) {
            foreach ($questions as $key=>$value) {
                if($value->question_id == $s){
                    array_push($questions_sequenced, $value);
                    break;
                }
            }
        }
        foreach ($questions as $key => $value) {
            if(!in_array($value->question_id, $question_sequence)){
                array_push($questions_sequenced, $value);
            }
        }
        $result =  json_encode( $questions_sequenced , JSON_PRETTY_PRINT);
        if($result){
            return $result;       
           }else{
               return false;
           }
    }

    function get_question_by_id($question_id){
        $this->db->select('question.question_id, question, question_image, question_image_width, explanation, explanation_image, explanation_image_width, level_id, language_id')
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
        $this->db->select('answer_option_id , answer , correct_option , question_id , answer_image, answer_image_width')
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

    function get_transliterate_data_by_question_and_language_id($question_id, $language_id){
        if(isset($language_id)){
            $this->db->where('transliterate_question.language_id' , $language_id);
        }
        $this->db->select('question_transliterate_id, language_id, question_transliterate, explanation_transliterate')
            ->from('transliterate_question')
            ->order_by('question_transliterate_id','asc')
            ->where('transliterate_question.question_id' , $question_id);
        $query = $this->db->get();
        $result = json_encode($query->row());
        if($result){
            return $result;       
        }else{
            return false;
        }
    }
    
    function get_transliterate_data_by_question_id($question_id){
        $this->db->select('question_transliterate_id, language_id, question_transliterate, explanation_transliterate')
            ->from('transliterate_question')
            ->order_by('question_transliterate_id','asc')
            ->where('transliterate_question.question_id' , $question_id);
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
        $this->db->select('*')
                ->from('groups')
                ->order_by('group_name', 'asc');
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
            'question_image'=>$this->input->post('question_image'), 
            'question_image_width'=>$this->input->post('question_image_width'), 
            'explanation_image'=>$this->input->post('explanation_image'), 
            'explanation_image_width'=>$this->input->post('explanation_image_width'), 
            'level_id'=>$this->input->post('question_level'),
            'language_id'=>$this->input->post('language'),
            'default_question_id'=>$this->input->post('language'),
            'created_by'=>$this->user_id
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
        $answer_option_image_width=$this->input->post('answer_option_image_width'); // Get the image widths for answer options 
        // var_dump($answer_option);
        // var_dump($correct_option);
        $answer_option_data=array();
        foreach($answer_option as $key=>$value) { //loop through the answer options 
            $answer_option_data[]=array(
                'answer'=>$answer_option[$key],
                'question_id'=>$question_id,
                'correct_option'=>$correct_option[$key],
                'answer_image'=>$answer_option_image[$key],
                'answer_image_width'=>$answer_option_image_width[$key],
                'created_by'=>$this->user_id
                // 'reference_note'=>$a->reference_note,
                // 'created_date_time'=>$this->input->post(''),
                // 'last_modified_by'=>$this->input->post(''),
                // 'last_modified_date_time'=>$this->input->post(''),
            );
        }
        // insert all the answer options into the answer table
        $this->db->insert_batch('answer_option' ,$answer_option_data);
       
        $question_transliterate = $this->input->post('question_transliterate'); //Get question transliterates
        $explanation_transliterate = $this->input->post('question_transliterate'); //Get explanation transliterates
        $transliterate_language = $this->input->post('transliterate_language'); //Get tansliterating language
        $question_transaliterate_data=array();
        if(isset($question_transliterate)){
            foreach ($question_transliterate as $key => $value) {
                $question_transaliterate_data[] = array(
                    'question_id'=>$question_id,
                    'language_id'=>$transliterate_language[$key],
                    'question_transliterate'=>$question_transliterate[$key],
                    'explanation_transliterate'=>$explanation_transliterate[$key],
                    'created_by'=>$this->user_id
                );
            }
            // insert all transliterate data into question_transaliterate table
            $this->db->insert_batch('transliterate_question',$question_transaliterate_data);
        }

        $this->db->trans_complete(); //Transaction Ends
		if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }

    function update_question($question_id) {
        $data=array(
			'question'=>$this->input->post('question'),
			'explanation'=>$this->input->post('question_explanation'),
            'status_id'=>'1',
            'question_image'=>$this->input->post('question_image'), 
            'question_image_width'=>$this->input->post('question_image_width'), 
            'explanation_image'=>$this->input->post('explanation_image'),
            'explanation_image_width'=>$this->input->post('explanation_image_width'),
            'level_id'=>$this->input->post('question_level'),
            'language_id'=>$this->input->post('language'),
            'default_question_id'=>$this->input->post('language'),
            'updated_by'=>$this->user_id,
            'updated_datetime'=>date("Y-m-d H:i:s")
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
        $answer_option_image=$this->input->post('answer_option_image'); // Get the image names for answer options 
        $answer_option_image_width=$this->input->post('answer_option_image_width'); // Get the image widths for answer options 
        // var_dump($answer_option_image);
        // var_dump($correct_option);
        $answer_option_data=array();
        foreach($answer_option as $key=>$value) { //loop through the answer options 
            $answer_option_data[]=array(
                'answer_option_id'=>$key,
                'answer'=>$answer_option[$key],
                'question_id'=>$question_id,
                'correct_option'=>$correct_option[$key],
                'answer_image'=>$answer_option_image[$key],
                'answer_image_width'=>$answer_option_image_width[$key],
                'updated_by'=> $this->user_id,
                'updated_datetime'=>date("Y-m-d H:i:s")
            );
        }
        $this->db->update_batch('answer_option',$answer_option_data, 'answer_option_id');
        //clean up , redundant data:  delete all other answer options whose key is not present in the $answer_option and has given question_id
        $not_to_be_deleted_answer_option_ids = array_keys($answer_option);
        $this->db->where('question_id',$question_id);
        $this->db->where_not_in('answer_option_id', $not_to_be_deleted_answer_option_ids);
        $delete_question_answer_options = $this->db->delete('answer_option');

        $new_answer_option=$this->input->post('new_answer_option'); //Get the new answer options added by the user.
        $new_correct_option=$this->input->post('new_correct_option'); // Get the new correct/incorrect value for answer options 
        $new_answer_option_image=$this->input->post('new_answer_option_image'); // Get the new correct/incorrect value for answer options 
        $new_answer_option_image_width=$this->input->post('new_answer_option_image_width'); // Get the new answer image width value for answer options 

        $new_answer_option_data=array();
        if(isset($new_answer_option)){
            foreach($new_answer_option as $key=>$value) { //loop through the new answer options 
                $new_answer_option_data[]=array(
                    'answer'=>$new_answer_option[$key],
                    'question_id'=>$question_id,
                    'correct_option'=>$new_correct_option[$key],
                    'answer_image'=>$new_answer_option_image[$key],
                    'answer_image_width'=>$new_answer_option_image_width[$key],
                    'created_by'=> $this->user_id
                );
            }
            // insert all the answer options into the answer table
            $this->db->insert_batch('answer_option' ,$new_answer_option_data);
        }
       
        $question_transliterate = $this->input->post('question_transliterate'); //Get question transliterates
        $explanation_transliterate = $this->input->post('explanation_transliterate'); //Get explanation transliterates
        $transliterate_language = $this->input->post('transliterate_language'); //Get tansliterating language
        $question_transaliterate_data=array();
        if(isset($question_transliterate)){
            foreach ($question_transliterate as $key => $value) {
                $question_transaliterate_data[] = array(
                    'question_transliterate_id'=>$key,
                    'question_id'=>$question_id,
                    'language_id'=>$transliterate_language[$key],
                    'question_transliterate'=>$question_transliterate[$key],
                    'explanation_transliterate'=>$explanation_transliterate[$key],
                    'updated_by'=> $this->user_id,
                    'updated_datetime'=>date("Y-m-d H:i:s")
                );
            }
            $this->db->update_batch('transliterate_question',$question_transaliterate_data, 'question_transliterate_id');
        }

        //delete all other question tranliterate data whose key is not present in the $question_transliterate and has given question_id
        if(isset($question_transliterate)){
            $not_to_be_deleted_question_transliterate_ids = array_keys($question_transliterate);
            $this->db->where('question_id',$question_id);
            $this->db->where_not_in('question_transliterate_id', $not_to_be_deleted_question_transliterate_ids);
            $deleted_question_transliterates = $this->db->delete('transliterate_question');
        } else {
            $this->db->where('question_id',$question_id);
            $this->db->delete('transliterate_question');
        }
 
        $new_question_transliterate = $this->input->post('new_question_transliterate'); // new question translietare added by user
        $new_explanation_transliterate = $this->input->post('new_explanation_transliterate'); // new explanatoion translietare added by user
        $new_transliterate_language = $this->input->post('new_transliterate_language'); // new translietare language

        $new_question_transaliterate_data=array();
        if(isset($new_question_transliterate)){
            foreach ($new_question_transliterate as $key => $value) {
                $new_question_transaliterate_data[]= array(
                    'question_id'=>$question_id,
                    'language_id'=>$new_transliterate_language[$key],
                    'question_transliterate'=>$new_question_transliterate[$key],
                    'explanation_transliterate'=>$new_explanation_transliterate[$key],
                    'created_by'=>$this->user_id
                );
            }
            // insert all the question transliterate data into the transliterate_question table
            $this->db->insert_batch('transliterate_question' ,$new_question_transaliterate_data);
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
        return [true , $status_id ? "Un-Locked" : "Locked"];
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

    function upload_image(){
        if($this->input->post('image')){
            $image_name = strtoupper($this->input->post('image_name'));
			$image = $this->input->post('image_val');
            $imgdata =  explode(",", $image)[1];
            $imgdata = base64_decode($imgdata);
			// save to server (beware of permissions)
			$result = file_put_contents("assets/images/quiz/$image_name.jpeg", $imgdata );
			if (!$result) die("Could not save image!  Check file permissions.");
		}
        return true;
    }

    function create_question_sequence(){
        $data = array(
            'group_id' => $this->input->post('group'),
            'sub_group_id' => $this->input->post('sub_group'),
            'language_id' => $this->input->post('language'),
            'sequence' => $this->input->post('question_sequence'),
            'created_by' => $this->user_id
        );
        // echo $this->input->post('question_sequence');
        $this->db->trans_start();
        $this->db->insert('question_sequence',$data);
        $this->db->trans_complete(); //Transaction Ends
        if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }
}
