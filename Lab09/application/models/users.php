<?php

/**
 * Data access wrapper for "User" table.
 *
 * @author Sharon
 */
class Users extends MY_Model {
    // constructor
    function __construct() {
        parent::__construct('users','id');
    }
}
