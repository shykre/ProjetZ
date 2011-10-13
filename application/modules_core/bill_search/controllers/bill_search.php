<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Invoice_Search extends Admin_Controller {

	function index() {

		$this->load->model(
			array(
			'mdl_bill_search',
			'suppliers/mdl_suppliers',
			'bill_statuses/mdl_bill_statuses'
			)
		);

		if (!$this->mdl_bill_search->validate()) {

			$this->load->model('suppliers/mdl_suppliers');

			$this->load->model('bill_statuses/mdl_bill_statuses');

            $supplier_params = array(
                'select'    =>  'mcb_suppliers.supplier_id, mcb_suppliers.supplier_name'
            );

			$data = array(
				'suppliers'			=>	$this->mdl_suppliers->get($supplier_params),
				'bill_statuses'	=>	$this->mdl_bill_statuses->get()
			);

			$this->load->view('search', $data);

		}

		else {

			$params = array();

			if (!$this->session->userdata('global_admin')) {

				$params['where']['mcb_bills.user_id'] = $this->session->userdata('user_id');

			}

			if (!$this->input->post('include_quotes')) {

				$params['where']['mcb_bills.bill_is_quote'] = 0;

			}

			/* Parse tags if posted */
			if ($this->input->post('tags')) {

				/* Remove any apostrophes and trim */
				$tags = trim(str_replace("'", '', $this->input->post('tags')));

				/**
				 * Explode into an array and trim each individual element
				 * if comma separated tags are provided
				 */

				if (strpos($tags, ',')) {

					$tags = explode(',', $tags);

					foreach ($tags as $key=>$tag) {

						$tags[$key] = trim($tag);

					}

					$tags = implode("','", $tags);

				}

				/* Add the tag where $params array element */
				$params['where'][] = "mcb_bills.bill_id IN (SELECT bill_id FROM mcb_bill_tags WHERE tag_id IN (SELECT tag_id FROM mcb_tags WHERE tag IN('" . $tags . "')))";

			}

			/* Add any suppliers if selected */
			if ($this->input->post('supplier_id')) {

				$params['where_in']['mcb_bills.supplier_id'] = $this->input->post('supplier_id');

			}

			/* Add any bill statuses if selected */
			if ($this->input->post('bill_status_id')) {

				$params['where_in']['mcb_bills.bill_status_id'] = $this->input->post('bill_status_id');

			}

			/* Add from date if provided */
			if ($this->input->post('from_date')) {

				$params['where']['mcb_bills.bill_date_entered >='] = strtotime(standardize_date($this->input->post('from_date')));

			}

			/* Add to date if provided */
			if ($this->input->post('to_date')) {

				$params['where']['mcb_bills.bill_date_entered <='] = strtotime(standardize_date($this->input->post('to_date')));

			}

			/* Add bill id if provided */
			if ($this->input->post('bill_number')) {

				$params['where'][] = "mcb_bills.bill_number LIKE '%" . $this->input->post('bill_number') . "%'";

			}

			/* Add amount if provided */
			if ($this->input->post('amount_operator') and check_clean_number($this->input->post('amount'))) {

				if ($this->input->post('amount_operator') <> '=') {

					$params['where']['mcb_bill_amounts.bill_total ' . $this->input->post('amount_operator')] = standardize_number($this->input->post('amount'));

				}

				else {

					$params['where']['mcb_bill_amounts.bill_total'] = standardize_number($this->input->post('amount'));

				}

			}

			if (!$params) {

				redirect('bill_search');

			}

			/* Generate a simple hash value */
			$hash = md5(time());

			/* Stick this stuff in the users session data */
			$userdata = array(
				'search_hash'	=>	array(
					$hash	=>	$params
				)
			);

			$this->session->set_userdata($userdata);

			/* Redirect to display results */
			redirect('bill_search/search_results/' . $this->input->post('output_type') . '/search_hash/' . $hash);

		}

	}

}

?>