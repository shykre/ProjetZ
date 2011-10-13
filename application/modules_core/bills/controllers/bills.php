<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Invoices extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('mdl_bills');

    }

    function index() {

        $this->_post_handler();

        $this->redir->set_last_index();

        $this->load->helper('text');

        $order_by = uri_assoc('order_by');

        $supplier_id = uri_assoc('supplier_id');

        $status = uri_assoc('status');

        $is_quote = uri_assoc('is_quote');

        $params = array(
            'paginate'		=>	TRUE,
            'limit'			=>	$this->mdl_mcb_data->setting('results_per_page'),
            'page'			=>	uri_assoc('page'),
            'where'			=>	array()
        );

        $params['where']['mcb_bills.bill_is_quote'] = ($is_quote) ? 1 : 0;

        if (!$this->session->userdata('global_admin')) $params['where']['mcb_bills.user_id'] = $this->session->userdata('user_id');

        if ($supplier_id) {

            $params['where']['mcb_bills.supplier_id'] = $supplier_id;

        }

        if ($status) {

            $params['where']['bill_status'] = $status;

        }

        switch ($order_by) {
            case 'bill_id_desc':
                $params['order_by'] = 'mcb_bills.bill_number DESC';
                break;
            case 'bill_id_asc':
                $params['order_by'] = 'mcb_bills.bill_number ASC';
                break;
            case 'supplier_desc':
                $params['order_by'] = 'supplier_name DESC';
                break;
            case 'supplier_asc':
                $params['order_by'] = 'supplier_name ASC';
                break;
            case 'total_desc':
                $params['order_by'] = 'bill_total DESC';
                break;
            case 'total_asc':
                $params['order_by'] = 'bill_total ASC';
                break;
            case 'amount_desc':
                $params['order_by'] = 'bill_total DESC';
                break;
            case 'amount_asc':
                $params['order_by'] = 'bill_total ASC';
                break;
            case 'duedate_asc':
                $params['order_by'] = 'mcb_bills.bill_due_date ASC, mcb_bills.bill_id DESC';
                break;
            case 'duedate_desc':
                $params['order_by'] = 'mcb_bills.bill_due_date DESC, mcb_bills.bill_id DESC';
                break;
            case 'date_asc':
                $params['order_by'] = 'mcb_bills.bill_date_entered ASC, mcb_bills.bill_id DESC';
                break;
            default:
                $params['order_by'] = 'mcb_bills.bill_date_entered DESC, mcb_bills.bill_id DESC';
        }

        $bills = $this->mdl_bills->get($params);

        $data = array(
            'bills'		=>	$bills,
            'sort_links'	=>	TRUE
        );

        $this->load->view('index', $data);

    }

    function create() {

        if ($this->input->post('btn_cancel')) {

            redirect('bills');

        }

        if (!$this->mdl_bills->validate_create()) {

            $this->load->model(array('suppliers/mdl_suppliers','mdl_bill_groups'));

            /* If supplier_id exists in URL, pre-select the supplier */
            if (uri_assoc('supplier_id') and !$_POST) {

                $this->mdl_bills->set_form_value('supplier_id', uri_assoc('supplier_id'));

            }

            elseif (uri_assoc('supplier_id', 4) and !$_POST) {

                $this->mdl_bills->set_form_value('supplier_id', uri_assoc('supplier_id', 4));

            }

            $this->load->helper('text');

            $data = array(
                'suppliers'			=>	$this->mdl_suppliers->get_active(),
                'bill_groups'	=>	$this->mdl_bill_groups->get()
            );

            $this->load->view('choose_supplier', $data);

        }

        else {

            $this->load->module('bills/bill_api');

            $package = array(
                'supplier_id'				=>	$this->input->post('supplier_id'),
                'bill_date_entered'	=>	$this->input->post('bill_date_entered'),
                'bill_group_id'		=>	$this->input->post('bill_group_id'),
                'bill_is_quote'		=>	$this->input->post('bill_is_quote')
            );

            $bill_id = $this->bill_api->create_bill($package);

            redirect('bills/edit/bill_id/' . $bill_id);

        }

    }

    function delete() {

        $bill_id = uri_assoc('bill_id');

        if ($bill_id) {

            $this->mdl_bills->delete($bill_id);

        }

        redirect($this->session->userdata('last_index'));

    }

    function edit() {

        $tab_index = ($this->session->flashdata('tab_index')) ? $this->session->flashdata('tab_index') : 0;

        $this->_post_handler();

        $this->redir->set_last_index();

        $this->load->model(
            array(
            'suppliers/mdl_suppliers',
            'payments/mdl_payments',
            'tax_rates/mdl_tax_rates',
            'bill_statuses/mdl_bill_statuses',
            'templates/mdl_templates',
            'users/mdl_users'
            )
        );

        $this->load->helper('text');

        $params = array(
            'where'	=>	array(
                'mcb_bills.bill_id'	=>	uri_assoc('bill_id')
            )
        );

        if (!$this->session->userdata('global_admin')) {

            $params['where']['mcb_bills.user_id'] = $this->session->userdata('user_id');

        }

        $bill = $this->mdl_bills->get($params);
        
        if (!$bill) {

            redirect('dashboard/record_not_found');

        }

        $supplier_params = array(
            'select' =>  'mcb_suppliers.supplier_id, mcb_suppliers.supplier_name'
        );

        $user_params = array(
            'where' =>  array(
                'mcb_users.supplier_id'   =>  0
            )
        );

        if (!$this->session->userdata('global_admin')) {

            $user_params['where']['user_id'] = $this->session->userdata('user_id');

        }

        $data = array(
            'bill'			=>	$bill,
            'payments'          =>  $this->mdl_bills->get_bill_payments($bill->bill_id),
            'history'           =>  $this->mdl_bills->get_bill_history($bill->bill_id),
            'bill_items'     =>  $this->mdl_bills->get_bill_items($bill->bill_id),
            'bill_tax_rates' =>  $this->mdl_bills->get_bill_tax_rates($bill->bill_id),
            'tags'              =>  $this->mdl_bills->get_bill_tags($bill->bill_id),
            'suppliers'			=>	$this->mdl_suppliers->get_active($supplier_params),
            'tax_rates'			=>	$this->mdl_tax_rates->get(),
            'bill_statuses'	=>	$this->mdl_bill_statuses->get(),
            'tab_index'			=>	$tab_index,
            'custom_fields'		=>	$this->mdl_fields->get_object_fields(1),
            'users'             =>  $this->mdl_users->get($user_params)
        );

        $this->load->view('bill_edit', $data);

    }

    function generate_pdf() {

        $bill_id = uri_assoc('bill_id');

        $this->load->library('lib_output');

        $this->mdl_bills->save_bill_history($bill_id, $this->session->userdata('user_id'), $this->lang->line('generated_bill_pdf'));

        $this->lib_output->pdf($bill_id, uri_assoc('bill_template'));

    }

    function generate_html() {

        $bill_id = uri_assoc('bill_id');

        $this->load->library('bills/lib_output');

        $this->mdl_bills->save_bill_history($bill_id, $this->session->userdata('user_id'), $this->lang->line('generated_bill_html'));

        $this->lib_output->html($bill_id, uri_assoc('bill_template'));

    }

    function recalculate() {

        $this->load->model('mdl_bill_amounts');

        $this->mdl_bill_amounts->adjust();

        redirect('settings');

    }

    function quote_to_bill() {

        $this->_post_handler();

        $bill_id = uri_assoc('bill_id');

        if (!$this->mdl_bills->validate_quote_to_bill()) {

            $this->load->model('mdl_bill_groups');

            $data = array(
                'bill_groups'	=>	$this->mdl_bill_groups->get(),
                'bill'			=>	$this->mdl_bills->get_by_id($bill_id)
            );

            $this->load->view('quote_to_bill', $data);

        }

        else {

            $this->mdl_bills->quote_to_bill($bill_id, $this->input->post('bill_date_entered'), $this->input->post('bill_group_id'));

            redirect('bills/edit/bill_id/' . $bill_id);

        }

    }

    function _post_handler() {

        if ($this->input->post('btn_add_new_item')) {

            redirect('bills/items/form/bill_id/' . uri_assoc('bill_id'));

        }

        elseif ($this->input->post('btn_add_payment')) {

            redirect('payments/form/bill_id/' . uri_assoc('bill_id'));

        }

        elseif ($this->input->post('btn_add_bill')) {

            redirect('bills/create');

        }

        elseif ($this->input->post('btn_add_quote')) {

            redirect('bills/create/quote');

        }

        elseif ($this->input->post('btn_cancel')) {

            redirect('bills/index');

        }

        elseif ($this->input->post('btn_submit_options_general') or $this->input->post('btn_submit_options_tax') or $this->input->post('btn_submit_notes')) {

            if ($this->input->post('btn_submit_options_general')) {

                $this->session->set_flashdata('tab_index', 0);

            }

            elseif ($this->input->post('btn_submit_options_tax')) {

                $this->session->set_flashdata('tab_index', 3);

            }

            elseif ($this->input->post('btn_submit_notes')) {

                $this->session->set_flashdata('tab_index', 4);

            }

            $this->mdl_bills->save_bill_options($this->mdl_fields->get_object_fields(1));

            $this->load->model('mdl_bill_amounts');

            $this->mdl_bill_amounts->adjust(uri_assoc('bill_id'));

            redirect('bills/edit/bill_id/' . uri_assoc('bill_id'));

        }

        elseif ($this->input->post('btn_quote_to_bill')) {

            redirect('bills/quote_to_bill/bill_id/' . uri_assoc('bill_id'));

        }

    }

    function jquery_supplier_bill_template($supplier_id, $quote = 0) {

        $this->load->model('mcb_data/mdl_mcb_supplier_data');

        $type = (!$quote) ? 'bill' : 'quote';

        $default_template = $this->mdl_mcb_supplier_data->get($supplier_id, 'default_' . $type . '_template');

        if (!$default_template) {

            $default_template = $this->mdl_mcb_data->setting('default_' . $type . '_template');

        }

        echo $default_template;

    }

    function jquery_supplier_bill_group($supplier_id, $type = 'bill') {

        log_message('DEBUG', $type);

        $this->load->model('mcb_data/mdl_mcb_supplier_data');

        $default_group_id = $this->mdl_mcb_supplier_data->get($supplier_id, 'default_' . $type . '_group_id');

        if (!$default_group_id) {

            $default_group_id = $this->mdl_mcb_data->setting('default_' . $type . '_group_id');

        }

        echo $default_group_id;

    }

}

?>