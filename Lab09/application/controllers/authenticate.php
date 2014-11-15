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
    
    function attempt()
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
            if (isset($session_record['id'])) {
                // use the item record from the session
                $item_record = $session_record;
            }
        }

        // merge the view parms with the current item record
        //$this->data = array_merge($this->data, $item_record);
        // we need to construct pretty editing fields using the formfields helper
        $this->load->helper('formfields');
        $this->data['fusername'] = makeTextField('UserID', 'id', '', "You must have an userID", 10, 25);
        $this->data['fpassword'] = makePasswordField('Password', 'password', '', "Account must have a password");
        
        $this->data['fsubmit'] = makeSubmitButton('Login', 'Do you feel lucky?');
        
        $this->render();
    }
    
    function login($which)
    {
        $fields = $this->input->post(); // gives us an associative array
        
        //error checking
        if (strlen($fields['id']) < 1)
            $this->errors[] = 'The account has to have an id!';
        if (strlen($fields['password']) < 1)
            $this->errors[] = 'The account has to have a password!';
 
        //gets the user and password from post form
        $key = $fields['id'];
        $password = md5($fields['password']);
        
        //gets the user from the model
        $user = $this->users->get($key);
        
        //if the password from the post is the same as the model
        //continue
        if($password == md5((string)$user->password))
        {
            //keep constants as the user data
            if($user->role == 'admin')
            {
                $role = ADMIN;
            }
            else if($user->role == 'user')
            {
                $role = USER;
            }
            
            //set the user and permissions as defined in role
            $this->session->set_userdata('userID', $key);
            $this->session->set_userdata('userName', $user->name);
            $this->session->set_userdata('userRole', $role);
            
            redirect('/authenticate/success/'. $key);
        }
        else
        {
            $this->errors[] = 'UserID/password combo does not match!';
            $this->tryagain($which);
        }
    }
    
    function tryagain($which)
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
            if (isset($session_record['id']) && ($session_record['id'] == $which)) {
                // use the item record from the session
                $item_record = $session_record;
            }
        }
        
        // if no item-in progress record {
        if ($item_record == null) {
            // get the item record from the items model
            $item_record = (array) $this->users->get($which);
            // save it as the “item” session object
            $this->session->set_userdata('item', $item_record);
        }

        // merge the view parms with the current item record
        //        $this->data = array_merge($this->data, $item_record);
        // we need to construct pretty editing fields using the formfields helper
        $this->load->helper('formfields');
        $this->data['fusername'] = makeTextField('UserID', 'id', '', "You must have an userID", 10, 25);
        $this->data['fpassword'] = makePasswordField('Password', 'password', '', "Account must have a password");
        
        $this->data['fsubmit'] = makeSubmitButton('Login', 'Do you feel lucky?');
        
        $this->render();
    }
    
    
    function success($id)
    {
        $this->data['pagebody'] = 'success';
        $this->data['title'] = 'Success~';
        
        $user = $this->users->get($id);
        
        $this->data['id'] = $id;
        $this->data['username'] = $user->username;
        $this->data['role'] = $user->role;
        
        $this->render();
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