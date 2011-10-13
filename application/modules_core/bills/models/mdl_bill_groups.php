<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Invoice_Groups extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->table_name = 'mcb_bill_groups';

		$this->primary_key = 'mcb_bill_groups.bill_group_id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";

		$this->order_by = 'mcb_bill_groups.bill_group_prefix';

		$this->limit = $this->mdl_mcb_data->setting('results_per_page');

	}

	public function validate() {

		$this->form_validation->set_rules('bill_group_name', $this->lang->line('group_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('bill_group_prefix', $this->lang->line('group_prefix'), 'max_length[10]');
		$this->form_validation->set_rules('bill_group_prefix_year', $this->lang->line('group_prefix_year'));
		$this->form_validation->set_rules('bill_group_prefix_month', $this->lang->line('group_prefix_month'));
		$this->form_validation->set_rules('bill_group_next_id', $this->lang->line('group_next_id'), 'required|numeric');
		$this->form_validation->set_rules('bill_group_left_pad', $this->lang->line('group_left_pad'), 'required');

		return parent::validate();

	}

	function save($db_array, $bill_group_id) {

		if (!isset($db_array['bill_group_prefix_year'])) {

			$db_array['bill_group_prefix_year'] = 0;

		}

		if (!isset($db_array['bill_group_prefix_month'])) {

			$db_array['bill_group_prefix_month'] = 0;

		}

		parent::save($db_array, $bill_group_id);

	}

	public function adjust_bill_number($bill_id, $bill_group_id) {

		$bill = $this->mdl_bills->get_by_id($bill_id);

		if ($bill->bill_group_id <> $bill_group_id) {

			$group = parent::get_by_id($bill_group_id);
			
			$bill_number = '';
			
			$date_prefix = FALSE;

			if ($group->bill_group_prefix_year) {

				$bill_number .= date('Y');
				$date_prefix = TRUE;

			}

			if ($group->bill_group_prefix_month) {

				$bill_number .= date('m');
				$date_prefix = TRUE;

			}

			if ($date_prefix) {

				$bill_number .= '-';

			}

			$bill_number .= $group->bill_group_prefix . str_pad($group->bill_group_next_id, $group->bill_group_left_pad, '0', STR_PAD_LEFT);

			/* Update the bill group record with the incremented next bill id */
			$this->db->set('bill_group_next_id', $group->bill_group_next_id + 1);
			$this->db->where('bill_group_id', $group->bill_group_id);
			$this->db->update('mcb_bill_groups');

			/* Assign the bill number to the bill */
			$this->db->set('bill_number', $bill_number);
			$this->db->set('bill_group_id', $bill_group_id);
			$this->db->where('bill_id', $bill_id);
			$this->db->update('mcb_bills');

		}

	}

}

?>