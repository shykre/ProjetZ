<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Lib_output {

    public $CI;

    function __construct() {

        $this->CI =& get_instance();

    }

    function html($invoice_id, $invoice_template) {

        $data = array(
            'invoice'		=>	$this->get_invoice($invoice_id),
            'output_type'   =>  'html'
        );

        $this->CI->load->view('invoices/invoice_templates/' . $invoice_template, $data);

    }

    function pdf($invoice_id, $invoice_template) {

        $this->CI->load->helper($this->CI->mdl_mcb_data->setting('pdf_plugin'));

        $invoice = $this->get_invoice($invoice_id);

        $invoice_number = $invoice->invoice_number;

        $data = array(
            'invoice'		=>	$invoice,
            'output_type'   =>  'pdf'
        );

        $html = $this->CI->load->view('invoices/invoice_templates/' . $invoice_template, $data, TRUE);

        $file_prefix = (!$data['invoice']->invoice_is_quote) ? $this->CI->lang->line('invoice') . '_' : $this->CI->lang->line('quote') . '_';

        pdf_create($html, $file_prefix . $invoice_number, TRUE);

    }

    function get_invoice($invoice_id) {

        $params = array(
            'where'	=>	array(
                'mcb_invoices.invoice_id'	=>	$invoice_id
            ),
            'get_invoice_payments'  =>  TRUE,
            'get_invoice_items'     =>  TRUE,
            'get_invoice_tax_rates' =>  TRUE,
            'get_invoice_tags'      =>  TRUE
        );

        return $this->CI->mdl_invoices->get($params);

    }

}

?>