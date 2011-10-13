<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Invoice_Settings extends Admin_Controller {

	function __construct() {

		parent::__construct();

		$this->load->model('bills/mdl_bills');

	}

	function display() {

		$this->load->model(
			array(
			'bill_statuses/mdl_bill_statuses',
			'tax_rates/mdl_tax_rates',
			'templates/mdl_templates',
			'bills/mdl_bill_groups'
			)
		);

		$params = array(
			'select'	=>	'*',
			'where'		=>	array(
				'bill_status_type'	=>	1
			)
		);

		$open_bill_statuses = $this->mdl_bill_statuses->get($params);

		$params = array(
			'select'	=>	'*',
			'where'		=>	array(
				'bill_status_type'	=>	3
			)
		);

		$closed_bill_statuses = $this->mdl_bill_statuses->get($params);

		$data = array(
			'open_bill_statuses'		=>  $open_bill_statuses,
			'closed_bill_statuses'	=>  $closed_bill_statuses,
			'tax_rates'					=>  $this->mdl_tax_rates->get(array('select'=>'*')),
			'templates'					=>  $this->mdl_templates->get('bills'),
			'bill_logos'				=>  $this->mdl_bills->get_logos(),
			'bill_groups'			=>	$this->mdl_bill_groups->get()
		);

		

		$this->load->view('settings', $data);

	}

	function save() {

		/**
		 * As per the config file, this function will
		 * execute when the core system settings are saved.
		 */
		
		$this->mdl_mcb_data->save('currency_symbol', $this->input->post('currency_symbol'));
		$this->mdl_mcb_data->save('currency_symbol_placement', $this->input->post('currency_symbol_placement'));
		$this->mdl_mcb_data->save('default_bill_group_id', $this->input->post('default_bill_group_id'));
		$this->mdl_mcb_data->save('default_quote_group_id', $this->input->post('default_quote_group_id'));
		$this->mdl_mcb_data->save('default_tax_rate_id', $this->input->post('default_tax_rate_id'));
        $this->mdl_mcb_data->save('default_tax_rate_option', $this->input->post('default_tax_rate_option'));
		$this->mdl_mcb_data->save('default_item_tax_rate_id', $this->input->post('default_item_tax_rate_id'));
		$this->mdl_mcb_data->save('default_bill_template', $this->input->post('default_bill_template'));
		$this->mdl_mcb_data->save('default_quote_template', $this->input->post('default_quote_template'));
		$this->mdl_mcb_data->save('bills_due_after', $this->input->post('bills_due_after'));
		$this->mdl_mcb_data->save('default_open_status_id', $this->input->post('default_open_status_id'));
		$this->mdl_mcb_data->save('default_closed_status_id', $this->input->post('default_closed_status_id'));
		$this->mdl_mcb_data->save('decimal_symbol', $this->input->post('decimal_symbol'));
		$this->mdl_mcb_data->save('thousands_separator', $this->input->post('thousands_separator'));
        $this->mdl_mcb_data->save('cron_key', $this->input->post('cron_key'));

        if ($this->input->post('bill_logo')) {

			$this->mdl_mcb_data->save('bill_logo', $this->input->post('bill_logo'));

		}

		if ($this->input->post('include_logo_on_bill')) {

			$this->mdl_mcb_data->save('include_logo_on_bill', "TRUE");

		}

		else {

			$this->mdl_mcb_data->save('include_logo_on_bill', "FALSE");

		}

		if ($this->input->post('display_quantity_decimals')) {

			$this->mdl_mcb_data->save('display_quantity_decimals', 1);

		}

		else {

			$this->mdl_mcb_data->save('display_quantity_decimals', 0);

		}

		if ($this->input->post('disable_bill_audit_history')) {

			$this->mdl_mcb_data->save('disable_bill_audit_history', 1);

		}

		else {

			$this->mdl_mcb_data->save('disable_bill_audit_history', 0);

		}

		if ($this->input->post('update_decimal_taxes')) {

			if ($this->input->post('decimal_taxes_num') == 2 or $this->input->post('decimal_taxes_num') == 3) {

				$this->mdl_mcb_data->save('decimal_taxes_num', $this->input->post('decimal_taxes_num'));

				$this->_adjust_decimal_taxes($this->input->post('decimal_taxes_num'));

			}

		}

	}

	function _adjust_decimal_taxes($num) {

		$this->db->query("ALTER TABLE `mcb_tax_rates` CHANGE `tax_rate_percent` `tax_rate_percent` DECIMAL( 10, " . $num . " ) NOT NULL");


	}

}

?>