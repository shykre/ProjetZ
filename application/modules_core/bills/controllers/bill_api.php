<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_API extends Admin_Controller {

	function __construct() {

		parent::__construct();

	}

	function display_create_bill() {

		$this->load->model('suppliers/mdl_suppliers');

		$this->load->model('bills/mdl_bill_groups');

		$data = array(
			'suppliers'			=>	$this->mdl_suppliers->get(),
			'bill_groups'	=>	$this->mdl_bill_groups->get()
		);

		$this->load->view('bills/choose_supplier', $data);

	}

	function create_bill($package) {

		/**
		 * $package requirements
		 * - supplier_id
		 * - bill_date_entered
		 * - bill_is_quote
		 * - bill_group_id
		 *
		 *
		 * $package optional
		 * - bill_discount
		 * - bill_shipping
		 * - bill_items (array)
		 *
		 * $package['bill_items'] requirements
		 * - item_name
		 * - item_description
		 * - item_qty
		 * - item_price
		 */

		if (!is_array($package)) {

			return FALSE;

		}

		$required_elements = array(
			'supplier_id',
			'bill_date_entered',
			'bill_group_id'
		);

		foreach ($required_elements as $req_el) {

			if (!isset($package[$req_el])) {

				return FALSE;

			}

		}

		extract($package);

		if (!isset($bill_is_quote)) {

			$bill_is_quote = 0;

		}

		$this->load->model('bills/mdl_bills');

		$bill_id = $this->mdl_bills->save($supplier_id, $bill_date_entered, $bill_is_quote);

		if (isset($bill_items)) {

			foreach ($bill_items as $bill_item) {

				unset($item_name, $item_description, $item_qty, $item_price);

				extract($bill_item);

				$this->mdl_bills->add_bill_item($bill_id, $item_name, $item_description, $item_qty, $item_price);

			}

		}

		if (isset($bill_discount)) {

			$this->mdl_bills->set_bill_discount($bill_id, $bill_discount);

		}

		if (isset($bill_shipping)) {

			$this->mdl_bills->set_bill_shipping($bill_id, $bill_shipping);

		}

		$this->adjust_bill_amount($bill_id);

		$this->load->model('bills/mdl_bill_groups');

		$this->mdl_bill_groups->adjust_bill_number($bill_id, $bill_group_id);

		return $bill_id;

	}

	function add_bill_item($package) {

		if (!is_array($package)) {

			return FALSE;

		}

		extract($package);

		$required_elements = array(
			'bill_id',
			'item_name',
			'item_description',
			'item_qty',
			'item_price'
		);

		foreach ($required_elements as $req_el) {

			if (!isset($package[$req_el])) {

				return FALSE;

			}

		}

		$this->load->model('bills/mdl_bills');

		$tax_rate_id = (isset($tax_rate_id) ? $tax_rate_id : 0);

		$bill_item_id = $this->mdl_bills->add_bill_item($bill_id, $item_name, $item_description, $item_qty, $item_price, $tax_rate_id);

		return $bill_item_id;

	}

	function add_bill_discount($bill_id, $bill_discount) {

		$this->mdl_bills->set_bill_discount($bill_id, $bill_discount);

	}

	function add_bill_shipping($bill_id, $bill_shipping) {

		$this->mdl_bills->set_bill_shipping($bill_id, $bill_shipping);

	}

	function adjust_bill_amount($bill_id) {

		$this->load->model('bills/mdl_bill_amounts');

		$this->mdl_bill_amounts->adjust($bill_id);

	}

}

?>