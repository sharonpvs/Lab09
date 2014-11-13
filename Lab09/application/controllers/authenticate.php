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
    
    function login()
    {
        //gets the user and password from post form
        $key = $_POST['userid'];
        $password = md5($_POST['password']);
        
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