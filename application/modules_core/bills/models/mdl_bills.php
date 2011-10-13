<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Invoices extends MY_Model {

    public $date_formats;

    public function __construct() {

        parent::__construct();

        $this->table_name = 'mcb_bills';

        $this->primary_key = 'mcb_bills.bill_id';

        $this->order_by = 'mcb_bills.bill_date_entered DESC, mcb_bills.bill_id DESC';

        $this->select_fields = "
		SQL_CALC_FOUND_ROWS
		mcb_bills.*,
		mcb_bill_amounts.*,
        mcb_suppliers.*,
		mcb_users.username,
	    mcb_users.company_name AS from_company_name,
	    mcb_users.last_name AS from_last_name,
	    mcb_users.first_name AS from_first_name,
	    mcb_users.address AS from_address,
		mcb_users.address_2 AS from_address_2,
	    mcb_users.city AS from_city,
	    mcb_users.state AS from_state,
	    mcb_users.zip AS from_zip,
		mcb_users.country AS from_country,
	    mcb_users.phone_number AS from_phone_number,
		mcb_users.email_address AS from_email_address,
		mcb_users.web_address AS from_web_address,
		mcb_bill_statuses.*,
        IF(mcb_bill_statuses.bill_status_type <> 3, IF(mcb_bills.bill_due_date < UNIX_TIMESTAMP(), 1, 0), 0) AS bill_is_overdue,
		(DATEDIFF(FROM_UNIXTIME(UNIX_TIMESTAMP()),FROM_UNIXTIME(mcb_bills.bill_due_date))) AS bill_days_overdue";

        $user_custom_fields = $this->mdl_fields->get_object_fields(6);

        if ($user_custom_fields) {

            $this->select_fields .= ',';

            $ucf = array();

            foreach ($user_custom_fields as $user_custom_field) {

                $ucf[] = 'mcb_users.' . $user_custom_field->column_name;

            }

            $this->select_fields .= implode(',', $ucf);

        }

        if ($this->mdl_mcb_data->setting('version') >= '0.8.9') {

            $this->select_fields .= ', mcb_users.tax_id_number AS from_tax_id_number';

        }

        $this->joins = array(
            'mcb_bill_statuses'	=>	array(
                'mcb_bill_statuses.bill_status_id = mcb_bills.bill_status_id',
                'left'
            ),
            'mcb_users'				=>	array(
                'mcb_users.user_id = mcb_bills.user_id',
                'left'
            ),
            'mcb_bill_amounts'	=>	'mcb_bill_amounts.bill_id = mcb_bills.bill_id',
            'mcb_suppliers'			=>	'mcb_suppliers.supplier_id = mcb_bills.supplier_id'
        );

        $this->date_formats = array(
            'm/d/Y'		=>	array(
                'key'		=>	'm/d/Y',
                'picker'	=>	'mm/dd/yy',
                'mask'		=>	'99/99/9999',
                'dropdown'	=>	'mm/dd/yyyy'),
            'm/d/y'		=>	array(
                'key'		=>	'm/d/y',
                'picker'	=>	'mm/dd/y',
                'mask'		=>	'99/99/99',
                'dropdown'	=>	'mm/dd/yy'),
            'Y/m/d'		=>	array(
                'key'		=>	'Y/m/d',
                'picker'	=>	'yy/mm/dd',
                'mask'		=>	'9999/99/99',
                'dropdown'	=>	'yyyy/mm/dd'),
            'd/m/Y'		=>	array(
                'key'		=>	'd/m/Y',
                'picker'	=>	'dd/mm/yy',
                'mask'		=>	'99/99/9999',
                'dropdown'	=>	'dd/mm/yyyy'),
            'd/m/y'		=>	array(
                'key'		=>	'd/m/y',
                'picker'	=>	'dd/mm/y',
                'mask'		=>	'99/99/99',
                'dropdown'	=>	'dd/mm/yy'),
            'm-d-Y'		=>	array(
                'key'		=>	'm-d-Y',
                'picker'	=>	'mm-dd-yy',
                'mask'		=>	'99-99-9999',
                'dropdown'	=>	'mm-dd-yyyy'),
            'm-d-y'		=>	array(
                'key'		=>	'm-d-y',
                'picker'	=>	'mm-dd-y',
                'mask'		=>	'99-99-99',
                'dropdown'	=>	'mm-dd-yy'),
            'Y-m-d'		=>	array(
                'key'		=>	'Y-m-d',
                'picker'	=>	'yy-mm-dd',
                'mask'		=>	'9999-99-99',
                'dropdown'	=>	'yyyy-mm-dd'),
            'y-m-d'		=>	array(
                'key'		=>	'y-m-d',
                'picker'	=>	'y-mm-dd',
                'mask'		=>	'99-99-99',
                'dropdown'	=>	'yy-mm-dd'),
            'd.m.Y'		=>	array(
                'key'		=>	'd.m.Y',
                'picker'	=>	'dd.mm.yy',
                'mask'		=>	'99.99.9999',
                'dropdown'	=>	'dd.mm.yyyy'),
            'd.m.y'		=>	array(
                'key'		=>	'd.m.y',
                'picker'	=>	'dd.mm.y',
                'mask'		=>	'99.99.99',
                'dropdown'	=>	'dd.mm.yy')
        );

    }

    public function get($params = NULL) {

        $bills = parent::get($params);

        if (is_array($bills)) {

            foreach ($bills as $bill) {

                $bill = $this->set_bill_additional($bill, $params);

            }

        }

        else {

            $bills = $this->set_bill_additional($bills, $params);

        }

        return $bills;

    }

    public function get_recent_open($limit = 10) {

        $params = array(
            'limit'	=>	$limit,
            'where'	=>	array(
                'bill_status_type'	=>	1,
                'bill_is_quote'		=>	0
            ),
            'having'	=>	array(
                'bill_is_overdue'	=>	0
            )
        );

        if (!$this->session->userdata('global_admin')) {

            $params['where']['mcb_bills.user_id'] = $this->session->userdata('user_id');

        }

        return $this->get($params);

    }

    public function get_recent_pending($limit = 10) {

        $params = array(
            'limit'	=>	$limit,
            'where'	=>	array(
                'bill_status_type'	=>	2,
                'bill_is_quote'		=>	0
            )
        );

        if (!$this->session->userdata('global_admin')) {

            $params['where']['mcb_bills.user_id'] = $this->session->userdata('user_id');

        }

        return $this->get($params);

    }

    public function get_recent_closed($limit = 10) {

        $params = array(
            'limit'	=>	$limit,
            'where'	=>	array(
                'bill_status_type'	=>	3,
                'bill_is_quote'		=>	0
            )
        );

        if (!$this->session->userdata('global_admin')) {

            $params['where']['mcb_bills.user_id'] = $this->session->userdata('user_id');

        }

        return $this->get($params);

    }

    public function get_recent_overdue($limit = 10) {

        $params = array(
            'limit'	=>	$limit,
            'where'	=>	array(
                'bill_is_quote'	=>	0
            ),
            'having'	=>	array(
                'bill_is_overdue'	=>	1
            )
        );

        if (!$this->session->userdata('global_admin')) {

            $params['where']['mcb_bills.user_id'] = $this->session->userdata('user_id');

        }

        return $this->get($params);

    }

    public function get_overdue() {

        $params = array(
            'where' =>  array(
                'bill_is_quote'  =>  0
            ),
            'having'    =>  array(
                'bill_is_overdue'    =>  1
            )
        );

        if (!$this->session->userdata('global_admin')) {

            $params['where']['mcb_bills.user_id'] = $this->session->userdata('user_id');

        }

        return $this->get($params);

    }

    public function save($supplier_id, $date_entered, $bill_is_quote = 0, $strtotime = TRUE) {

        if ($strtotime) {

            $date_entered = strtotime(standardize_date($date_entered));

        }

        $db_array = array(
            'supplier_id'					=>	$supplier_id,
            'bill_date_entered'		=>	$date_entered,
            'bill_due_date'			=>	$this->calculate_due_date($date_entered),
            'user_id'					=>	$this->session->userdata('user_id'),
            'bill_status_id'			=>	$this->mdl_mcb_data->setting('default_open_status_id'),
            'bill_is_quote'			=>	$bill_is_quote
        );

        $this->db->insert($this->table_name, $db_array);

        $bill_id = $this->db->insert_id();

        $db_array = array(
            'bill_id'        =>	$bill_id,
            'tax_rate_id'       =>	$this->mdl_mcb_data->setting('default_tax_rate_id')
        );

        $default_tax_rate_option = $this->mdl_mcb_data->setting('default_tax_rate_option');

        if ($default_tax_rate_option) {

            $db_array['tax_rate_option'] = $default_tax_rate_option;

        }

        $this->db->insert('mcb_bill_tax_rates', $db_array);

        $this->save_bill_history($bill_id, $this->session->userdata('user_id'), $this->lang->line('created_bill'));

        return $bill_id;

    }

    public function save_bill_options($custom_fields = NULL) {

        $bill_id = uri_assoc('bill_id');

        $this->db->where('bill_id', $bill_id);

        $db_array = array(
            'supplier_id'					=>	$this->input->post('supplier_id'),
            'bill_date_entered'		=>	strtotime(standardize_date($this->input->post('bill_date_entered'))),
            'bill_notes'				=>	$this->input->post('bill_notes'),
            'user_id'                   =>  $this->input->post('user_id'),
            'bill_number'            =>  $this->input->post('bill_number')
        );

        if (is_numeric($this->input->post('bill_status_id'))) {

            $db_array['bill_status_id'] = $this->input->post('bill_status_id');

        }

        if ($this->input->post('bill_due_date')) {

            $db_array['bill_due_date'] = strtotime(standardize_date($this->input->post('bill_due_date')));

        }

        if ($custom_fields) {

            foreach ($custom_fields as $custom_field) {

                $db_array[$custom_field->column_name] = $this->input->post($custom_field->column_name);

            }

        }

        $this->db->update($this->table_name, $db_array);

        $this->db->where('bill_id', $bill_id);

        $this->db->delete('mcb_bill_tax_rates');

        foreach ($this->input->post('tax_rate_id') as $key=>$tax_rate_id) {

            $db_array = array(
                'bill_id'		=>	$bill_id,
                'tax_rate_id'		=>	$tax_rate_id,
                'tax_rate_option'	=>	$_POST['tax_rate_option'][$key]
            );

            $this->db->insert('mcb_bill_tax_rates', $db_array);

        }

        $this->load->model('mdl_bill_tags');

        $this->mdl_bill_tags->save_tags($bill_id, $this->input->post('tags'));

        $db_array = array(
            'bill_shipping'	=>	standardize_number($this->input->post('bill_shipping')),
            'bill_discount'	=>	standardize_number($this->input->post('bill_discount'))
        );

        $this->db->where('bill_id', $bill_id);

        $this->db->update('mcb_bill_amounts', $db_array);

        $this->save_bill_history($bill_id, $this->session->userdata('user_id'), $this->lang->line('saved_bill_options'));

        $this->session->set_flashdata('custom_success', $this->lang->line('bill_options_saved'));

    }

    public function delete($bill_id) {

        $this->db->query('DELETE FROM mcb_inventory_stock WHERE bill_item_id IN (SELECT bill_item_id FROM mcb_bill_items WHERE bill_id = ' . $bill_id . ')');

        parent::delete(array('bill_id'=>$bill_id));

        $this->db->where('bill_id', $bill_id);

        $this->db->delete(
            array(
            'mcb_bill_items',
            'mcb_payments',
            'mcb_bill_amounts',
            'mcb_bill_tax_rates',
            'mcb_bill_history',
            'mcb_bill_tax_rates'
            )
        );

        $this->db->query('DELETE FROM mcb_bill_item_amounts WHERE bill_item_id NOT IN (SELECT bill_item_id FROM mcb_bill_items)');

        $this->save_bill_history($bill_id, $this->session->userdata('user_id'), $this->lang->line('deleted_bill'));

    }

    public function get_logos() {

        $this->load->helper('directory');

        return directory_map('./uploads/bill_logos');

    }

    public function add_bill_item($bill_id, $item_name, $item_description, $item_qty, $item_price, $tax_rate_id = 0, $item_date = NULL) {

        $item_date = ($item_date) ? strtotime(standardize_date($item_date)) : time();

        $db_array = array(
            'bill_id'		=>	$bill_id,
            'item_name'			=>	$item_name,
            'item_description'	=>	$item_description,
            'item_qty'			=>	$item_qty,
            'item_price'		=>	$item_price,
            'tax_rate_id'		=>	$tax_rate_id,
            'item_date'			=>	$item_date
        );

        $this->db->insert('mcb_bill_items', $db_array);

        $bill_item_id = $this->db->insert_id();

        $this->load->model('bills/mdl_bill_amounts');

        $this->mdl_bill_amounts->adjust($bill_id);

        return $bill_item_id;

    }

    public function set_bill_discount($bill_id, $bill_discount) {

        $this->db->where('bill_id', $bill_id);

        $this->db->set('bill_discount', $bill_discount);

        $this->db->update('mcb_bill_amounts');

        $this->mdl_bill_amounts->adjust($bill_id);

    }

    public function set_bill_shipping($bill_id, $bill_shipping) {

        $this->db->where('bill_id', $bill_id);

        $this->db->set('bill_shipping', $bill_shipping);

        $this->db->update('mcb_bill_amounts');

        $this->mdl_bill_amounts->adjust($bill_id);

    }

    public function validate() {

        $this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'required');
        $this->form_validation->set_rules('user_id', $this->lang->line('created_by'), 'required');
        $this->form_validation->set_rules('bill_date_entered', $this->lang->line('date_entered'), 'required');
        $this->form_validation->set_rules('bill_date_closed', $this->lang->line('date_closed'));
        $this->form_validation->set_rules('bill_number', $this->lang->line('bill_number'), 'required');
        $this->form_validation->set_rules('bill_notes', $this->lang->line('notes'));

        return parent::validate();

    }

    public function validate_create() {

        $this->form_validation->set_rules('bill_date_entered', $this->lang->line('bill_date'), 'required');
        $this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'required');
        $this->form_validation->set_rules('bill_group_id', $this->lang->line('bill_group'), 'required');
        $this->form_validation->set_rules('bill_is_quote', $this->lang->line('quote_only'));

        return parent::validate();

    }

    public function validate_quote_to_bill() {

        $this->form_validation->set_rules('bill_date_entered', $this->lang->line('bill_date'), 'required');
        $this->form_validation->set_rules('bill_group_id', $this->lang->line('bill_group'), 'required');

        return parent::validate();

    }

    public function quote_to_bill($bill_id, $bill_date_entered, $bill_group_id) {

        $db_array = array(
            'bill_is_quote'		=>	0,
            'bill_date_entered'	=>	strtotime(standardize_date($bill_date_entered))
        );

        $this->db->where('bill_id', $bill_id);

        $this->db->update('mcb_bills', $db_array);

        $this->load->model('mdl_bill_groups');

        $this->mdl_bill_groups->adjust_bill_number($bill_id, $bill_group_id);

    }

    public function delete_bill_file($filename) {

        if (file_exists('uploads/temp/' . $filename)) unlink('uploads/temp/' . $filename);

    }

    public function save_bill_history($bill_id, $user_id, $bill_history_data) {

        if (!$this->mdl_mcb_data->setting('disable_bill_audit_history')) {

            $db_array = array(
                'bill_id'			=>	$bill_id,
                'user_id'				=>	$user_id,
                'bill_history_date'	=>	time(),
                'bill_history_data'	=>	$bill_history_data
            );

            $this->db->insert('mcb_bill_history', $db_array);

        }

    }

    private function calculate_due_date($date_entered) {

        return mktime(0, 0, 0, date("m", $date_entered), date("d", $date_entered) + $this->mdl_mcb_data->setting('bills_due_after'), date("Y", $date_entered));

    }

    public function set_bill_additional($bill, $params = NULL) {

        if (isset($params['get_bill_items'])) {

            $bill->bill_items = $this->get_bill_items($bill->bill_id);

        }

        if (isset($params['get_bill_payments'])) {

            $bill->bill_payments = $this->get_bill_payments($bill->bill_id);

        }

        if (isset($params['get_bill_tax_rates'])) {

            $bill->bill_tax_rates = $this->get_bill_tax_rates($bill->bill_id);

        }

        if (isset($params['get_bill_item_tax_sums'])) {

            $bill->bill_item_tax_sums = $this->get_bill_item_tax_sums($bill->bill_id);

        }

        if (isset($params['get_bill_tags'])) {

            $bill->bill_tags = $this->get_bill_tags($bill->bill_id);

        }

        return $bill;

    }

    public function get_bill_items($bill_id) {

        $this->db->where('bill_id', $bill_id);

        $this->db->join('mcb_bill_item_amounts', 'mcb_bill_item_amounts.bill_item_id = mcb_bill_items.bill_item_id');

        $this->db->join('mcb_tax_rates', 'mcb_tax_rates.tax_rate_id = mcb_bill_items.tax_rate_id', 'LEFT');

        $this->db->order_by('item_order');

        $items = $this->db->get('mcb_bill_items')->result();

        return $items;

    }

    public function get_bill_payments($bill_id) {

        $this->load->model('payments/mdl_payments');

        $params = array(
            'where'	=>	array(
                'mcb_payments.bill_id'	=>	$bill_id
            )
        );

        return $this->mdl_payments->get($params);

    }

    public function get_bill_tax_rates($bill_id) {

        $this->load->model('tax_rates/mdl_tax_rates');

        return $this->mdl_tax_rates->get_bill_tax_rates($bill_id);

    }

    public function get_bill_item_tax_sums($bill_id) {

        $this->db->select('tax_rate_name, tax_rate_percent, SUM(item_tax) AS tax_rate_sum');

        $this->db->group_by('mcb_tax_rates.tax_rate_id');

        $this->db->join('mcb_bill_item_amounts', 'mcb_bill_item_amounts.bill_item_id = mcb_bill_items.bill_item_id');

        $this->db->join('mcb_tax_rates', 'mcb_tax_rates.tax_rate_id = mcb_bill_items.tax_rate_id', 'LEFT');

        $this->db->where('mcb_bill_items.bill_id', $bill_id);

        return $this->db->get('mcb_bill_items')->result();


    }

    public function get_bill_tags($bill_id) {

        if ($this->mdl_mcb_data->setting('version') >= '0.8') {

            $this->load->model('bills/mdl_bill_tags');

            return $this->mdl_bill_tags->get_tags($bill_id);

        }

    }

    public function get_bill_history($bill_id) {

        $this->load->model('bills/mdl_bill_history');

        $params = array(
            'where'	=>	array(
                'mcb_bill_history.bill_id'	=>	$bill_id
            )
        );

        return $this->mdl_bill_history->get($params);

    }

    public function get_total_bill_balance($user_id = NULL) {

        $this->db->select('SUM(bill_balance) AS total_bill_balance');

        $this->db->join('mcb_bills', 'mcb_bills.bill_id = mcb_bill_amounts.bill_id');

        $this->db->where('mcb_bills.bill_is_quote', 0);

        if ($user_id) {

            $this->db->where('mcb_bills.user_id', $user_id);

        }

        return $this->db->get('mcb_bill_amounts')->row()->total_bill_balance;

    }

}

?>