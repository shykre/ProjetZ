<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Upload_Logo extends Admin_Controller {

	function index() {

		if ($this->input->post('btn_upload_logo')) {

			$config = array(
				'upload_path'	=>	'./uploads/bill_logos/',
				'allowed_types'	=>	'gif|jpg|png',
				'max_size'		=>	'100',
				'max_width'		=>	'500',
				'max_height'	=>	'300'
			);

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload()) {

				$data = array(
					'static_error'	=>	$this->upload->display_errors()
				);

				$this->load->view('upload_logo', $data);

			}

			else {
				
				$upload_data = $this->upload->data();

				$this->mdl_mcb_data->save('bill_logo', $upload_data['file_name']);

				redirect('settings');

			}

		}

		else {

			$this->load->view('upload_logo');

		}

	}

	function delete() {

		unlink('./uploads/bill_logos/' . uri_assoc('bill_logo', 4));

		if ($this->mdl_mcb_data->setting('bill_logo') == uri_assoc('bill_logo', 4)) {

			$this->mdl_mcb_data->delete('bill_logo');

			$this->session->unset_userdata('bill_logo');

		}

		redirect('settings');

	}

}

?>