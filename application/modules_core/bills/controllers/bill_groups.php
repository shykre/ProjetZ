<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Invoice_Groups extends Admin_Controller {

	function __construct() {

		parent::__construct();

		$this->_post_handler();

		$this->load->model('mdl_bill_groups');

	}

	function index() {

		$this->redir->set_last_index();

		$params = array(
			'paginate'	=>	TRUE,
			'page'		=>	uri_assoc('page', 4)
		);

		$data = array(
			'bill_groups' =>	$this->mdl_bill_groups->get($params),
		);

		$this->load->view('bill_group_index', $data);

	}

	function form() {

		if (!$this->mdl_bill_groups->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('bill_group_id', 4)) {

				$this->mdl_bill_groups->prep_validation(uri_assoc('bill_group_id', 4));

			}

			$this->load->view('bill_group_form');

		}

		else {

			$this->mdl_bill_groups->save($this->mdl_bill_groups->db_array(), uri_assoc('bill_group_id', 4));

			$this->redir->redirect('bills/bill_groups');

		}

	}

	function delete() {

		$bill_group_id = uri_assoc('bill_group_id', 4);

		if ($bill_group_id and $bill_group_id <> 1) {

			$this->mdl_bill_groups->delete(array('bill_group_id'=>$bill_group_id));

		}

		$this->redir->redirect('bills/bill_groups');

	}

	function _post_handler() {

		if ($this->input->post('btn_add')) {

			redirect('bills/bill_groups/form');

		}

		elseif ($this->input->post('btn_cancel')) {

			redirect('bills/bill_groups/index');

		}

	}

}

?>