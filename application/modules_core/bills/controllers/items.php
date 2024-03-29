<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Items extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->_post_handler();

        $this->load->model(
            array(
            'mdl_items',
            'mdl_bills',
            'inventory/mdl_inventory',
            'tax_rates/mdl_tax_rates'
            )
        );

    }

    function form() {

        if (!$this->mdl_items->validate()) {

            if (!$_POST AND uri_assoc('bill_item_id', 4)) {

                $this->mdl_items->prep_validation(uri_assoc('bill_item_id', 4));

                $this->mdl_items->set_form_value('item_date', format_date($this->mdl_items->form_value('item_date')));

            }

            elseif (!$_POST AND !uri_assoc('bill_item_id', 4)) {

                $this->mdl_items->set_form_value('item_date', format_date(time()));

            }

            $bill = $this->mdl_bills->get(array('where'=>array('mcb_bills.bill_id'=>uri_assoc('bill_id', 4))));

            $params = array(
                'group_by_type' =>  TRUE
            );

            $inventory_items = $this->mdl_inventory->get($params);

            $data = array(
                'bill'           =>	$bill,
                'inventory_items'	=>	$inventory_items,
                'tax_rates'         =>	$this->mdl_tax_rates->get(),
                'custom_fields'     =>	$this->mdl_items->custom_fields
            );

            $this->load->view('item_form', $data);

        }

        else {

            $bill_item_id = uri_assoc('bill_item_id', 4);

            $this->mdl_items->save($this->mdl_items->db_array(), $bill_item_id);

            if (!$bill_item_id) {

                $bill_item_id = $this->db->insert_id();

            }

            $this->load->model('mdl_bill_amounts');

            $this->mdl_bill_amounts->adjust(uri_assoc('bill_id', 4));

            if ($this->input->post('inventory_id')) {

                $this->load->model('inventory/mdl_inventory_stock');

                $this->mdl_inventory_stock->adjust($this->input->post('inventory_id'), ($this->input->post('item_qty') * -1), $bill_item_id);

            }

            $this->session->set_flashdata('tab_index', 1);

            redirect($this->session->userdata('last_index'));

        }

    }

    function delete() {

        $bill_item_id = uri_assoc('bill_item_id', 4);

        $bill_id = uri_assoc('bill_id', 4);

        if ($bill_item_id) {

            $this->mdl_items->delete($bill_item_id);

            $this->load->model('mdl_bill_amounts');

            $this->mdl_bill_amounts->adjust($bill_id);

            $this->load->model('inventory/mdl_inventory_stock');

            $this->mdl_inventory_stock->delete_by_bill_item_id($bill_item_id);

        }

        $this->session->set_flashdata('tab_index', 1);

        redirect($this->session->userdata('last_index'));

    }

    function jquery_save_order() {
       
        $item_orders = explode('&', $this->input->post('data'));

        unset($item_orders[0]);

        foreach ($item_orders as $item_order=>$bill_item_id) {

            $bill_item_id = str_replace('dnd[]=', '', $bill_item_id);

            $this->mdl_items->save_order($bill_item_id, $item_order);


        }

    }

    function _post_handler() {

        if ($this->input->post('btn_cancel')) {

            redirect($this->session->userdata('last_index'));

        }

    }

}

?>