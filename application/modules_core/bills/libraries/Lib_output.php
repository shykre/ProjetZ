<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Lib_output {

    public $CI;

    function __construct() {

        $this->CI =& get_instance();

    }

    function html($bill_id, $bill_template) {

        $data = array(
            'bill'		=>	$this->get_bill($bill_id),
            'output_type'   =>  'html'
        );

        $this->CI->load->view('bills/bill_templates/' . $bill_template, $data);

    }

    function pdf($bill_id, $bill_template) {

        $this->CI->load->helper($this->CI->mdl_mcb_data->setting('pdf_plugin'));

        $bill = $this->get_bill($bill_id);

        $bill_number = $bill->bill_number;

        $data = array(
            'bill'		=>	$bill,
            'output_type'   =>  'pdf'
        );

        $html = $this->CI->load->view('bills/bill_templates/' . $bill_template, $data, TRUE);

        $file_prefix = (!$data['bill']->bill_is_quote) ? $this->CI->lang->line('bill') . '_' : $this->CI->lang->line('quote') . '_';

        pdf_create($html, $file_prefix . $bill_number, TRUE);

    }

    function get_bill($bill_id) {

        $params = array(
            'where'	=>	array(
                'mcb_bills.bill_id'	=>	$bill_id
            ),
            'get_bill_payments'  =>  TRUE,
            'get_bill_items'     =>  TRUE,
            'get_bill_tax_rates' =>  TRUE,
            'get_bill_tags'      =>  TRUE
        );

        return $this->CI->mdl_bills->get($params);

    }

}

?>