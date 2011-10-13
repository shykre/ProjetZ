<?php

class Mdl_Invoice_History extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->table_name = 'mcb_bill_history';

		$this->primary_key = 'mcb_bill_history.bill_history_id';

		$this->order_by = 'bill_history_date DESC';

		$this->select = 'mcb_bill_history.*, mcb_users.username, mcb_bills.bill_number';

		$this->joins = array(
			'mcb_users'		=>	'mcb_users.user_id = mcb_bill_history.user_id',
			'mcb_bills'	=>	'mcb_bills.bill_id = mcb_bill_history.bill_id'
		);

	}

	function clear_history($bill_id = NULL) {

		if ($bill_id) {

			$this->db->where('bill_id', $bill_id);

		}

		else {

			$this->db->where('bill_id >', 0);

		}

		$this->db->delete($this->table_name);

	}

}

?>