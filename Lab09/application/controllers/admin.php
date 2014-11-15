<?php

/**
 * Administration.
 * 
 * Really just an excuse to play with view techniques
 * 
 * controllers/admin.php
 *
 * ------------------------------------------------------------------------
 */
class Admin extends Application {

    function __construct() {
        parent::__construct();
    }

    //-------------------------------------------------------------
    //  The normal pages
    //-------------------------------------------------------------

    function index() {
        $this->data['title'] = 'Jim\'s Joint Administration!';
        $this->data['pagebody'] = 'admin';
        
        //if they are not admin, they cannot view the page
        if($this->session->userdata('userRole') != ADMIN)
        {
            redirect('/authenticate/logout');
        }
        
        // Get all the menu items
        $choices = $this->menu->all();

        // and pass these on to the view
        $this->data['items'] = $choices;

        $this->render();
    }

    // show the menu items, but using nest view fragments
    // the output looks the same - we just get there differently
    function list2() {
        $this->data['title'] = 'Jim\'s Joint Administration (view 2)!';
        $this->data['pagebody'] = 'list2';

        // Get all the menu items
        $choices = $this->menu->all();

        $result = '';

        // iterate over each menu item, building its row as a view fragment
        foreach ($choices as $record) {
            $result .= $this->parser->parse('listitem2', (array) $record, true);
        }

        // and pass these on to the view
        $this->data['themeat'] = $result;

        $this->render();
    }

    // present a menu item for editing
    function edit3($which) {
        $this->data['title'] = 'Jim\'s Joint Maintenance (step 3)!';
        $this->data['pagebody'] = 'edit3';

        $record = $this->menu->get($which);

        // and pass the item's fields on
        $this->data = array_merge($this->data, (array) $record);

        $this->render();
    }

    // handle a proposed menu item form submission
    function post3($which) {
        $fields = $this->input->post(); // gives us an associative array
        // test the incoming fields
        if (strlen($fields['name']) < 1)
            $this->errors[] = 'An item has to have a name!';
        if (strlen($fields['description']) < 1)
            $this->errors[] = 'An item has to have a description!';
        if (!is_numeric($fields['price']))
            $this->errors[] = "An item's price has to be numeric!";
        $cat = $fields['category'];
        if (($cat != 'm') && ($cat != 'd') && ($cat != 'w'))
            $this->errors[] = 'Your category has to be one of m, d or c :(';

        // update if ok
        if (count($this->errors) < 1) {
            $record = $this->menu->get($which);
            $record = array_merge((array) $record, $fields);
            $this->menu->update($record);
            redirect('/admin/list2');
        } else {
            $this->edit3($which);
        }
    }

    // present a menu item for editing
    function edit4($which) {
        $this->data['title'] = 'Jim\'s Joint Maintenance (step 4)!';
        $this->data['pagebody'] = 'edit4';

        // use “item” as the session key
        // assume no item record in-progress
        $item_record = null;
        // do we have an item in the session already {
        $session_record = $this->session->userdata('item');
        if ($session_record !== FALSE) {
            // does its item # match the requested one {
            if (isset($session_record['code']) && ($session_record['code'] == $which)) {
                // use the item record from the session
                $item_record = $session_record;
            }
        }
        // if no item-in progress record {
        if ($item_record == null) {
            // get the item record from the items model
            $item_record = (array) $this->menu->get($which);
            // save it as the “item” session object
            $this->session->set_userdata('item', $item_record);
        }

        // merge the view parms with the current item record
        $this->data = array_merge($this->data, $item_record);
        $this->render();
    }

    // handle a proposed menu item form submission
    function post4($which) {
        $fields = $this->input->post(); // gives us an associative array
        // test the incoming fields
        if (strlen($fields['name']) < 1)
            $this->errors[] = 'An item has to have a name!';
        if (strlen($fields['description']) < 1)
            $this->errors[] = 'An item has to have a description!';
        if (!is_numeric($fields['price']))
            $this->errors[] = "An item's price has to be numeric!";
        $cat = $fields['category'];
        if (($cat != 'm') && ($cat != 'd') && ($cat != 'w'))
            $this->errors[] = 'Your category has to be one of m, d or c :(';

        // get the session item record
        $record = $this->session->userdata('item');
        // merge the session record into the model item record, over-riding any edited fields
        $record = array_merge($record, $fields);
        // update the session
        $this->session->set_userdata('item', $record);
        // update if ok
        if (count($this->errors) < 1) {
            // store the merged record into the model
            $this->menu->update($record);
            // remove the item record from the session container
            $this->session->unset_userdata('item');
            redirect('/admin/list2');
        } else {
            $this->edit4($which);
        }
    }

    // present a menu item for editing
    function edit5($which) {
        $this->data['title'] = 'Jim\'s Joint Maintenance (step 5)!';
        $this->data['pagebody'] = 'edit5';

        // use “item” as the session key
        // assume no item record in-progress
        $item_record = null;
        // do we have an item in the session already {
        $session_record = $this->session->userdata('item');
        if ($session_record !== FALSE) {
            // does its item # match the requested one {
            if (isset($session_record['code']) && ($session_record['code'] == $which)) {
                // use the item record from the session
                $item_record = $session_record;
            }
        }
        // if no item-in progress record {
        if ($item_record == null) {
            // get the item record from the items model
            $item_record = (array) $this->menu->get($which);
            // save it as the “item” session object
            $this->session->set_userdata('item', $item_record);
        }

        // merge the view parms with the current item record
//        $this->data = array_merge($this->data, $item_record);
        // we need to construct pretty editing fields using the formfields helper
        $this->load->helper('formfields');
        $this->data['fcode'] = makeTextField('Item Code', 'code', $item_record['code'], "item identifier ... cannot be changed", 10, 25, true);
        $this->data['fname'] = makeTextField('Name', 'name', $item_record['name'], "Name your customers are comfortable with");
        $this->data['fdescription'] = makeTextArea('Description', 'description', $item_record['description'], 'This is a long-winded and humorous caption that pops up if the visitor hovers over a menu item picture too long.');
        $this->data['fprice'] = makeTextField('Price', 'price', $item_record['price'], "Price per unit of one of these delicious menu items");

        $options = array('m' => 'Meal', 'd' => 'Drink', 's' => 'Sweet');
        $this->data['fcategory'] = makeComboField('Menu category', 'category', $item_record['category'], $options, "Menu category. Used to group similar things by column for ordering");
        $this->data['fpicture'] = showImage('Menu picture shown at ordering time', $item_record['picture']);
        $this->data['fsubmit'] = makeSubmitButton('Post Changes', 'Do you feel lucky?');
        $this->render();
    }

    // handle a proposed menu item form submission
    function post5($which) {
        $fields = $this->input->post(); // gives us an associative array
        // test the incoming fields
        if (strlen($fields['name']) < 1)
            $this->errors[] = 'An item has to have a name!';
        if (strlen($fields['description']) < 1)
            $this->errors[] = 'An item has to have a description!';
        if (!is_numeric($fields['price']))
            $this->errors[] = "An item's price has to be numeric!";
        $cat = $fields['category'];
        if (($cat != 'm') && ($cat != 'd') && ($cat != 'w'))
            $this->errors[] = 'Your category has to be one of m, d or c :(';

        // get the session item record
        $record = $this->session->userdata('item');
        // merge the session record into the model item record, over-riding any edited fields
        $record = array_merge($record, $fields);
        // update the session
        $this->session->set_userdata('item', $record);
        // update if ok
        if (count($this->errors) < 1) {
            // store the merged record into the model
            $this->menu->update($record);
            // remove the item record from the session container
            $this->session->unset_userdata('item');
            redirect('/admin/list2');
        } else {
            $this->edit5($which);
        }
    }

}
