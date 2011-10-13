<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Clients extends MY_Model {

    public function __construct() {

        parent::__construct();

        $this->table_name = 'mcb_suppliers';

        $this->select_fields = "
		SQL_CALC_FOUND_ROWS
		mcb_suppliers.*,
		mcb_suppliers.supplier_id as join_supplier_id,
		(SELECT SUM(bill_total) FROM mcb_bill_amounts WHERE bill_id IN (SELECT bill_id FROM mcb_bills WHERE supplier_id = join_supplier_id AND bill_is_quote = 0)) AS supplier_total_bill,
		IFNULL((SELECT SUM(payment_amount) FROM mcb_payments JOIN mcb_bills ON mcb_bills.bill_id = mcb_payments.bill_id WHERE mcb_bills.supplier_id = mcb_suppliers.supplier_id AND bill_is_quote = 0), 0.00) AS supplier_total_payment,
		(SELECT ROUND(supplier_total_bill - supplier_total_payment, 2)) AS supplier_total_balance";

        $this->primary_key = 'mcb_suppliers.supplier_id';

        $this->order_by = 'supplier_name';

        $this->custom_fields = $this->mdl_fields->get_object_fields(3);

    }

    public function get($params = NULL) {

        $suppliers = parent::get($params);

        if (is_array($suppliers)) {

            if ($this->mdl_mcb_data->setting('version') > '0.8.2') {

                if (isset($params['set_supplier_data'])) {

                    foreach ($suppliers as $supplier) {

                        $this->db->where('supplier_id', $supplier->supplier_id);

                        $mcb_supplier_data = $this->db->get('mcb_supplier_data')->result();

                        foreach ($mcb_supplier_data as $supplier_data) {

                            $supplier->{$supplier_data->mcb_supplier_key} = $supplier_data->mcb_supplier_value;

                        }

                    }

                }

            }

        }

        else {

            if ($this->mdl_mcb_data->setting('version') > '0.8.2') {

                if (isset($params['set_supplier_data'])) {

                    $this->db->where('supplier_id', $suppliers->supplier_id);

                    $mcb_supplier_data = $this->db->get('mcb_supplier_data')->result();

                    foreach ($mcb_supplier_data as $supplier_data) {

                        $suppliers->{$supplier_data->mcb_supplier_key} = $supplier_data->mcb_supplier_value;

                    }

                }

            }

        }

        return $suppliers;

    }

    public function get_active($params = NULL) {

        if (!$params) {

            $params = array(
                'where'	=>	array(
                    'supplier_active'	=>	1
                )
            );

        }

        else {

            $params['where']['supplier_active'] = 1;

        }

        return $this->get($params);

    }

    public function validate() {

        $this->form_validation->set_rules('supplier_active', $this->lang->line('supplier_active'));
        $this->form_validation->set_rules('supplier_name', $this->lang->line('supplier_name'), 'required');
        $this->form_validation->set_rules('supplier_tax_id', $this->lang->line('tax_id_number'));
        $this->form_validation->set_rules('supplier_address', $this->lang->line('street_address'));
        $this->form_validation->set_rules('supplier_address_2', $this->lang->line('street_address_2'));
        $this->form_validation->set_rules('supplier_city', $this->lang->line('city'));
        $this->form_validation->set_rules('supplier_state', $this->lang->line('state'));
        $this->form_validation->set_rules('supplier_zip', $this->lang->line('zip'));
        $this->form_validation->set_rules('supplier_country', $this->lang->line('country'));
        $this->form_validation->set_rules('supplier_phone_number', $this->lang->line('phone_number'));
        $this->form_validation->set_rules('supplier_fax_number',	$this->lang->line('fax_number'));
        $this->form_validation->set_rules('supplier_mobile_number', $this->lang->line('mobile_number'));
        $this->form_validation->set_rules('supplier_email_address', $this->lang->line('email_address'), 'valid_email');
        $this->form_validation->set_rules('supplier_web_address', $this->lang->line('web_address'));
        $this->form_validation->set_rules('supplier_notes', $this->lang->line('notes'));

        foreach ($this->custom_fields as $custom_field) {

            $this->form_validation->set_rules($custom_field->column_name, $custom_field->field_name);

        }

        return parent::validate($this);

    }

    public function delete($supplier_id) {

        $this->load->model('bills/mdl_bills');

        /* Delete the supplier record */

        parent::delete(array('supplier_id'=>$supplier_id));

        /* Delete any related contacts */

        $this->db->where('supplier_id', $supplier_id);

        $this->db->delete('mcb_contacts');

        /*
		 * Delete any related bills, but use the bill model so records
		 * related to the bill are also deleted
        */

        $this->db->select('bill_id');

        $this->db->where('supplier_id', $supplier_id);

        $bills = $this->db->get('mcb_bills')->result();

        foreach ($bills as $bill) {

            $this->mdl_bills->delete($bill->bill_id);

        }

    }

    public function save() {

        $db_array = parent::db_array();

        if (!$this->input->post('supplier_active')) {

            $db_array['supplier_active'] = 0;

        }

        parent::save($db_array, uri_assoc('supplier_id'));

    }

}

?>