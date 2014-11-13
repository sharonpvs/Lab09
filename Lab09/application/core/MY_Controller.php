<?php

/**
 * core/MY_Controller.php
 *
 * Default application controller
 *
 * @author		JLP
 * @copyright           2010-2013, James L. Parry
 * ------------------------------------------------------------------------
 */
class Application extends CI_Controller {

    protected $data = array();      // parameters for view components
    protected $id;                  // identifier for our content

    /**
     * Constructor.
     * Establish view parameters & load common helpers
     */

    function __construct() {
        parent::__construct();
        $this->data = array();
        $this->data['title'] = '?';
        $this->errors = array();
        $this->data['pageTitle'] = '??';
    }

    /**
     * Render this page
     */
    function render() {
        $this->data['menubar'] = $this->parser->parse('_menubar', $this->config->item('menu_choices'),true);
        $this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);

        $this->data['errormessages'] = $this->scold();
        
        // finally, build the browser page!
        $this->data['data'] = &$this->data;
        $this->parser->parse('_template', $this->data);
    }

    /**
     * Build a nice display of any error messages
     * 
     */
    function scold() {
        $result = '';
        if (count($this->errors) < 1) {
            $this->data['alerting'] = '';
        } else {
            $this->data['alerting'] = 'alert alert-error';
            foreach ($this->errors as $msg) {
                $result .= $msg . '<br/>'; // the break should be a constant somewhere
            }
        }
        return $result;
    }
}

/* End of file MY_Controller.php */
/* Location: application/core/MY_Controller.php */