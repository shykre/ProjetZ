<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Clients extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->_post_handler();

        $this->load->model('mdl_suppliers');

    }

    function index() {

        $this->load->helper('text');

        $this->redir->set_last_index();

        $params = array(
            'paginate'	=>	TRUE,
            'limit'		=>	$this->mdl_mcb_data->setting('results_per_page'),
            'page'		=>	uri_assoc('page')
        );

        $order_by = uri_assoc('order_by');

        if ($order_by == 'supplier_id_desc') {

            $params['order_by'] = 'supplier_id DESC';

        }

        elseif ($order_by == 'supplier_id_asc') {

            $params['order_by'] = 'supplier_id ASC';

        }

        elseif ($order_by == 'balance_desc') {

            $params['order_by'] = 'supplier_total_balance DESC';

        }

        elseif ($order_by == 'balance_asc') {

            $params['order_by'] = 'supplier_total_balance ASC';

        }

        elseif ($order_by == 'supplier_name_asc') {

            $params['order_by'] = 'supplier_name ASC';

        }

        elseif ($order_by == 'supplier_name_desc') {

            $params['order_by'] = 'supplier_name DESC';

        }

        else {

            $params['order_by'] = 'supplier_name';

        }

        $data = array(
            'suppliers'	=>	$this->mdl_suppliers->get($params)
        );

        $this->load->view('index', $data);

    }

    function form() {

        $supplier_id = uri_assoc('supplier_id');

        $this->load->model(
            array(
            'mcb_data/mdl_mcb_supplier_data',
            'bills/mdl_bill_groups'
            )
        );

        if ($supplier_id) {

            $this->mdl_mcb_supplier_data->set_session_data($supplier_id);

        }

        if ($this->mdl_suppliers->validate()) {

            $this->mdl_suppliers->save();

            $supplier_id = ($supplier_id) ? $supplier_id : $this->db->insert_id();

            foreach ($this->input->post('supplier_settings') as $key=>$value) {

                if ($value) {

                    $this->mdl_mcb_supplier_data->save($supplier_id, $key, $value);

                }

                else {

                    $this->mdl_mcb_supplier_data->delete($supplier_id, $key);

                }

            }

            redirect($this->session->userdata('last_index'));

        }

        else {

            $this->load->model('templates/mdl_templates');

            $this->load->helper('form');

            if (!$_POST AND $supplier_id) {

                $this->mdl_suppliers->prep_validation($supplier_id);

            }

            $data = array(
                'custom_fields'     =>	$this->mdl_suppliers->custom_fields,
                'bill_templates' =>  $this->mdl_templates->get('bills'),
                'bill_groups'    =>  $this->mdl_bill_groups->get()
            );

            $this->load->view('form', $data);

        }

    }

    function details() {

        $this->redir->set_last_index();

        $this->load->helper('text');

        $this->load->model(
            array(
            'bills/mdl_bills',
            'mdl_contacts',
            'templates/mdl_templates'
            )
        );

        $supplier_id = uri_assoc('supplier_id');

        $supplier_params = array(
            'where'	=>	array(
                'mcb_suppliers.supplier_id'	=>	$supplier_id
            )
        );

        $contact_params = array(
            'where'	=>	array(
                'mcb_contacts.supplier_id'    =>  $supplier_id
            )
        );

        $bill_params = array(
            'where'	=>	array(
                'mcb_bills.supplier_id'        =>	$supplier_id,
                'mcb_bills.bill_is_quote' =>  0
            )
        );

        if (!$this->session->userdata('global_admin')) {

            $bill_params['where']['mcb_bills.user_id'] = $this->session->userdata('user_id');

        }

        $supplier = $this->mdl_suppliers->get($supplier_params);

        $contacts = $this->mdl_contacts->get($contact_params);

        $bills = $this->mdl_bills->get($bill_params);

        if ($this->session->flashdata('tab_index')) {

            $tab_index = $this->session->flashdata('tab_index');

        }

        else {

            $tab_index = 0;

        }

        $data = array(
            'supplier'	=>	$supplier,
            'contacts'	=>	$contacts,
            'bills'	=>	$bills,
            'tab_index'	=>	$tab_index
        );

        $this->load->view('details', $data);

    }

    function delete() {

        $supplier_id = uri_assoc('supplier_id');

        if ($supplier_id) {

            $this->mdl_suppliers->delete($supplier_id);

        }

        $this->redir->redirect('suppliers');

    }

    function get($params = NULL) {

        return $this->mdl_suppliers->get($params);

    }

    function _post_handler() {

        if ($this->input->post('btn_add_supplier')) {

            redirect('suppliers/form');

        }

        elseif ($this->input->post('btn_edit_supplier')) {

            redirect('suppliers/form/supplier_id/' . uri_assoc('supplier_id'));

        }

        elseif ($this->input->post('btn_cancel')) {

            redirect($this->session->userdata('last_index'));

        }

        elseif ($this->input->post('btn_add_contact')) {

            redirect('suppliers/contacts/form/supplier_id/' . uri_assoc('supplier_id'));

        }

        elseif ($this->input->post('btn_add_bill')) {

            redirect('bills/create/supplier_id/' . uri_assoc('supplier_id'));

        }

        elseif ($this->input->post('btn_add_quote')) {

            redirect('bills/create/quote/supplier_id/' . uri_assoc('supplier_id'));

        }

    }

}

?>