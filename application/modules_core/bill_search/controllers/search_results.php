<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Search_Results extends Admin_Controller {

	function __construct() {

		parent::__construct();

		$this->load->model(
			array(
			'bills/mdl_bills',
			'templates/mdl_templates'
			)
		);

		$this->_post_handler();

	}

	function index() {

		if (!uri_assoc('search_hash', 4)) {

			redirect('bill_search');

		}

		$this->redir->set_last_index();

        $this->load->helper('text');

		$page = uri_assoc('page', 4);

		$params = array(
			'paginate'		=>	TRUE,
			'limit'			=>	$this->mdl_mcb_data->setting('results_per_page'),
			'page'			=>	$page
		);

		$bills = $this->_get_results($params);

		$data = array(
			'bills'		=>	$bills,
			'sort_links'	=>	TRUE
		);

		$this->load->view('results', $data);

	}

	function html() {

		$bills = $this->_get_results();

		$totals = $this->_get_totals($bills);

		$data = array(
			'bills'	=>	$bills,
			'totals'	=>	$totals
		);

		$this->load->view('html', $data);

	}

	function pdf() {

		$this->load->helper($this->mdl_mcb_data->setting('pdf_plugin'));

		$bills = $this->_get_results();

		$totals = $this->_get_totals($bills);

		$data = array(
			'bills'	=>	$bills,
			'totals'	=>	$totals
		);

		$html = $this->load->view('html', $data, TRUE);

		pdf_create($html, $this->lang->line('bill_summary_report'), TRUE);


	}

	function csv() {

		$bills = $this->_get_results();

		$lines = $this->lang->line('id') . ',' .
			$this->lang->line('date') . ',' .
			$this->lang->line('supplier') . ',' .
			$this->lang->line('total') . ',' .
			$this->lang->line('paid') . ',' .
			$this->lang->line('balance') . ',' .
			$this->lang->line('status') . "\r\n";

		foreach ($bills as $bill) {

			$lines .= $bill->bill_number . ',' .
				format_date($bill->bill_date_entered) . ',' .
				$bill->supplier_name . ',' .
				$bill->bill_total . ',' .
				$bill->bill_paid . ',' .
				$bill->bill_balance . ',' .
				$bill->bill_status . "\r\n";
		}

		$this->load->helper('download');

		force_download($this->lang->line('bill_summary_report') . '.csv', $lines);

	}

	function _get_results($params = array()) {

		$search_hash = $this->session->userdata('search_hash');

		$params = array_merge($params, $search_hash[uri_assoc('search_hash', 4)]);

		$params['set_supplier'] = TRUE;

		return $this->mdl_bills->get($params);

	}

	function _get_totals($bills) {

		$totals = array(
			'bill_item_subtotal'	=>	0.00,
			'bill_item_tax'		=>	0.00,
			'bill_subtotal'		=>	0.00,
			'bill_tax'			=>	0.00,
			'bill_shipping'		=>	0.00,
			'bill_discount'		=>	0.00,
			'bill_paid'			=>	0.00,
			'bill_total'			=>	0.00,
			'bill_balance'		=>	0.00
		);

		foreach ($bills as $bill) {

			$totals['bill_item_subtotal'] += $bill->bill_item_subtotal;
			$totals['bill_item_tax'] += $bill->bill_item_tax;
			$totals['bill_subtotal'] += $bill->bill_subtotal;
			$totals['bill_tax'] += $bill->bill_tax;
			$totals['bill_shipping'] += $bill->bill_shipping;
			$totals['bill_discount'] += $bill->bill_discount;
			$totals['bill_paid'] += $bill->bill_paid;
			$totals['bill_total'] += $bill->bill_total;
			$totals['bill_balance'] += $bill->bill_balance;

		}

		return $totals;

	}

	function _post_handler() {

		if ($this->input->post('btn_add_bill')) {

			redirect('bills/create');

		}

	}

}

?>