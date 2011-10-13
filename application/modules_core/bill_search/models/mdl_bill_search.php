<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Invoice_Search extends MY_Model {

	public function validate() {

		$this->form_validation->set_rules('bill_number', $this->lang->line('bill_number'));
		$this->form_validation->set_rules('from_date', $this->lang->line('from_date'));
		$this->form_validation->set_rules('to_date', $this->lang->line('to_date'));
		$this->form_validation->set_rules('tags', $this->lang->line('tags'));
		$this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'));
		$this->form_validation->set_rules('amount_operator', $this->lang->line('amount_operator'));
		$this->form_validation->set_rules('amount', $this->lang->line('amount'));

		return parent::validate();

	}

}

?>