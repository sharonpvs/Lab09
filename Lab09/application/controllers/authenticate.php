<?php

/**
 * Login/Logout controller
 * 
 * Implement the authenticaing handling usecases.
 * 
 * controllers/authenticate.php
 *
 * ------------------------------------------------------------------------
 */


class Authenticate extends Application
{
    function __construct() {
        parent::__construct();
    }
    function index()
    {
        $this->data['pagebody'] = 'login';
        $this->data['title'] = 'login';
        
        // use “item” as the session key
        // assume no item record in-progress
        $item_record = null;
        
        // do we have an item in the session already {
        $session_record = $this->session->userdata('user');
        if ($session_record !== FALSE) {
            // does its item # match the requested one {
            if (isset($session_record['id']) && ($session_record['id'] == $username)) {
                // use the item record from the session
                $item_record = $session_record;
            }
        }

        // merge the view parms with the current item record
        //        $this->data = array_merge($this->data, $item_record);
        // we need to construct pretty editing fields using the formfields helper
        $this->load->helper('formfields');
        $this->data['fusername'] = makeTextField('User Name', 'id', '', "You must have an username", 10, 25);
        $this->data['fpassword'] = makePasswordField('Password', 'password', '', "Account must have a password");
        
        $this->data['fsubmit'] = makeSubmitButton('Login', 'Do you feel lucky?');
        
        $this->render();
    }
    
    function login($code)
    {
        $fields = $this->input->post(); // gives us an associative array
        
        //gets the user and password from post form
        $key = $fields['userid'];
        $password = md5($fields['password']);
        
        //gets the user from the model
        $user = $this->users->get($key);
        
        //if the password from the post is the same as the model
        //continue
        if($password == (string) $user->password)
        {
            //set the user and permissions as defined in role
            $this->session->set_userdata('userID', $key);
            $this->session->set_userdata('userName', $user->name);
            $this->session->set_userdata('userRole', $user->role);
        }
        else
        {
            $this->index();
        }
    }
    
    function logout()
    {
        //destroys session, logouts out user
        $this->session->sess_destroy();
        $this->load->helper('url');
        
        redirect('/');
    }
    
}

/* End of file authenticate.php */
/* Location: application/controllers/authenticate.php */