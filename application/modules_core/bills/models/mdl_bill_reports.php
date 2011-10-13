<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Invoice_Reports extends MY_Model {

	public function validate() {

		$this->form_validation->set_rules('from_date', $this->lang->line('from_date'), 'required');
		$this->form_validation->set_rules('to_date', $this->lang->line('to_date'), 'required');

		return parent::validate();

	}

	public function bills($results) {

		$bill_amount = 0;

		$bill_tax = 0;

		$bill_total = 0;

		$bill_payment = 0;

		$bill_balance = 0;

		foreach ($results as $result) {

			$bill_amount += $result->bill_item_total;

			$bill_tax += $result->bill_tax_total_amount;

			$bill_total += $result->bill_total;

			$bill_payment += $result->bill_paid_amount;

			$bill_balance += $result->bill_balance;

		}

		return array(
			'bills'			=>	$results,
			'group_totals'		=>	array(
				'bill_amount'	=>	$bill_amount,
				'bill_tax'		=>	$bill_tax,
				'bill_total'		=>	$bill_total,
				'bill_payment'	=>	$bill_payment,
				'bill_balance'	=>	$bill_balance
			)
		);
		
	}

	public function bill_params() {

		$params = array(
			'where'	=> array(
				'mcb_bills.bill_date_entered >='	=>	strtotime(standardize_date($this->input->post('from_date'))),
				'mcb_bills.bill_date_entered <='	=>	strtotime(standardize_date($this->input->post('to_date')))
			)
		);

		if ($this->input->post('supplier_id') <> 'all') {

			$params['where']['mcb_bills.supplier_id'] = $this->input->post('supplier_id');

		}

		if ($this->input->post('status_select') == 'open') {

			$params['where']['mcb_bills.bill_status_id'] = 1;

		}

		elseif ($this->input->post('status_select') == 'pending') {

			$params['where']['mcb_bills.bill_status_id'] = 2;

		}

		elseif ($this->input->post('status_select') == 'closed') {

			$params['where']['mcb_bills.bill_status_id'] = 3;

		}

		return $params;

	}

}

?>