<?php

/**
 * Order handler
 * 
 * Implement the different order handling usecases.
 * 
 * controllers/welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Order extends Application {

    function __construct() {
        parent::__construct();
    }

    // start a new order
    function neworder() {
        
        //you must be logged in to make an order
        if($this->session->userdata('userRole') == 0)
        {
            redirect('/authenticate/noLogin');
        }
        
        $order_num = $this->orders->highest() + 1;

        $record = $this->orders->create();
        $record->num = $order_num;
        $record->date = date(DATE_ATOM);
        $record->status = 'a';
        $record->total = 0.0;
        $this->orders->add($record);

        redirect('/order/display_menu/' . $order_num);
    }

    // add to an order
    function display_menu($order_num = null) {
        if ($order_num == null)
            redirect('/order/neworder');

        $this->data['pagebody'] = 'show_menu';
        $this->data['order_num'] = $order_num;

        // get order details for title
        $title = 'Order #' . $order_num . ' (' . number_format($this->orders->total($order_num), 2) . ')';
        $this->data['title'] = $title;

        // Make the columns
        $this->data['meals'] = $this->make_column('m');
        $this->data['drinks'] = $this->make_column('d');
        $this->data['sweets'] = $this->make_column('s');

        $this->render();
    }

    // make a menu ordering column
    function make_column($category) {
        $items = $this->menu->some('category', $category);
        return $items;
    }

    // add an item to an order
    function add($order_num, $item) {
        $this->orders->add_item($order_num, $item);
        redirect('/order/display_menu/' . $order_num);
    }

    // checkout
    function checkout($order_num) {
        $this->data['title'] = 'Checking Out';
        $this->data['pagebody'] = 'show_order';
        $this->data['order_num'] = $order_num;
        $this->data['total'] = number_format($this->orders->total($order_num), 2);

        $items = $this->orderitems->group($order_num);
        foreach ($items as $item) {
            $menuitem = $this->menu->get($item->item);
            $item->code = $menuitem->name;
        }
        $this->data['items'] = $items;

        $this->data['okornot'] = $this->orders->validate($order_num) ? "" : "disabled";

        $this->render();
    }

    // proceed with checkout
    function commit($order_num) {
        if (!$this->orders->validate($order_num))
            redirect('/order/display_menu/' . $order_num);
        $record = $this->orders->get($order_num);
        $record->date = date(DATE_ATOM);
        $record->status = 'c';
        $record->total = $this->orders->total($order_num);
        $this->orders->update($record);
        redirect('/authenticate/logout');
    }

    // cancel the order
    function cancel($order_num) {
        $this->orderitems->delete_some($order_num);
        $record = $this->orders->get($order_num);
        $record->status = 'x';
        $this->orders->update($record);
        redirect('/authenticate/logout');
    }

}

/* End of file welcome.php */
/* Location: application/controllers/welcome.php */