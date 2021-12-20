<?php
class User_model extends CI_Model {
    function __construct() {
        parent::__construct();
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

    //user_function() takes user ID as parameter and returns a list of all the functions the user has access to.
	function user_function($user_id){
		$this->db->select('user_function_id,user_function,add,edit,view,remove')
            ->from('user')
            ->join('user_function_link','user.user_id=user_function_link.user_id')
            ->join('user_function','user_function_link.function_id=user_function.user_function_id')
            ->where('user_function_link.user_id',$user_id)
            ->where('user_function_link.active','1');
		$query=$this->db->get();
		
		return $query->result();
	}

    // get all languages user associated with
    function user_languages($user_id) {
        $this->db->select('language_id')
            ->where('user_id', $user_id)
            ->from('user_language_link');
		$query=$this->db->get();
		
		return $query->result();
    }
}