<?php
class master_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function get_banner_text(){
       $this->db->select("banner_text")->from('banner_text')->where('status',1);
       $query = $this->db->get();
       return $query->row();
    }

    function count_all(){
        $query = $this->db->get("question");
        return $query->num_rows();
    }

    function get_questions($level="") { 
        $this->db->select('question_id , question , explanation')
            ->from('question')
            ->order_by('question_id','asc');;
        $query = $this->db->get();
        $result =  $query->result();
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
        $this->db->insert('question',$data); //Insert into 'question' table the $data array
        $question_id=$this->db->insert_id(); //Get the Question ID from the inserted record
       
         // creating tuple for link table
         $question_grouping_data =  array(
            'question_id'=>$question_id,
            'group_id'=>$this->input->post('group'),
            'sub_group_id'=>$this->input->post('sub_group')
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
        $data = array(
            'language' => strtoupper($this->input->post('language'))
        );
        $this->db->trans_start();
        $this->db->insert('language',$data);
        $this->db->trans_complete(); //Transaction Ends
        if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }

    function insert_level() {
        $data = array(
            'level' => $this->input->post('level')
        );
        $this->db->trans_start();
        $this->db->insert('level',$data);
        $this->db->trans_complete(); //Transaction Ends
        if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }

    function insert_group() {
        $data = array(
            'group' => $this->input->post('group'),
            'group_image' => $this->input->post('group_image')
        );
        $this->db->trans_start();
        $this->db->insert('group',$data);
        $this->db->trans_complete(); //Transaction Ends
        if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }

    function insert_sub_group() {
        $data = array(
            'sub_group' => $this->input->post('sub_group'),
            'group_id' => $this->input->post('group_id'),
            'sub_group_image' => $this->input->post('sub_group_image')
        );
        $this->db->trans_start();
        $this->db->insert('group',$data);
        $this->db->trans_complete(); //Transaction Ends
        if($this->db->trans_status()===TRUE) return true; else return false; //if transaction completed successfully return true, else false.
    }

}
