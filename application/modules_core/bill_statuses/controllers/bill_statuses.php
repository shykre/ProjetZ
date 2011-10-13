<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Invoice_Statuses extends Admin_Controller {

	function __construct() {

		parent::__construct();

		$this->_post_handler();

		$this->load->model('mdl_bill_statuses');

	}

	function index() {

		$this->redir->set_last_index();
		
		$params = array(
			'paginate'	=>	TRUE,
			'limit'		=>	$this->mdl_mcb_data->setting('results_per_page'),
			'page'		=>	uri_assoc('page')
		);

		$data = array(
			'bill_statuses' =>	$this->mdl_bill_statuses->get($params)
		);

		$this->load->view('index', $data);

	}

	function form() {

		if (!$this->mdl_bill_statuses->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('bill_status_id')) {

				$this->mdl_bill_statuses->prep_validation(uri_assoc('bill_status_id'));

			}

			$this->load->view('form');

		}

		else {

			$this->mdl_bill_statuses->save($this->mdl_bill_statuses->db_array(), uri_assoc('bill_status_id'));

			$this->redir->redirect('bill_statuses');

		}

	}

	function delete() {

		if (uri_assoc('bill_status_id')) {

			$this->mdl_bill_statuses->delete(array('bill_status_id'=>uri_assoc('bill_status_id')));

		}

		$this->redir->redirect('bill_statuses');

	}

	function _post_handler() {

		if ($this->input->post('btn_add')) {

			redirect('bill_statuses/form');

		}

		elseif ($this->input->post('btn_cancel')) {

			redirect('bill_statuses/index');

		}

	}

}

?>