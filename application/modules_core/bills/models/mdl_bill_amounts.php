<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Invoice_Amounts extends CI_Model {

    /**
     * TABLE: mcb_bill_items
     * bill_item_id
     * bill_id
     * item_name
     * item_qty
     * item_price
     * tax_rate_id
     */

    /**
     * TABLE: mcb_bill_item_amounts
     * bill_item_amount_id
     * bill_item_id
     * item_subtotal
     * item_tax
     * item_total
     */

    /**
     * TABLE: mcb_tax_rates
     * tax_rate_id
     * tax_rate_name
     * tax_rate_percent
     */

    /**
     * TABLE: mcb_bill_tax_rates	Global taxes
     * bill_tax_rate_id
     * bill_id
     * tax_rate_id
     * tax_rate_option				1 = Normal, 2 = After last tax
     * tax_amount					Calculated amt of tax
     * taxed_amount					Current bill total
     */

    /**
     * TABLE: mcb_bill_amounts
     * bill_amount_id
     * bill_id
     * bill_item_subtotal	Sum of mcb_bill_item_amounts.item_total
     * bill_item_tax			Sum of mcb_bill_item_amounts.item_tax
     * bill_subtotal			(bill_item_subtotal + bill_item_tax)
     * bill_tax				Sum of global bill tax amounts
     * bill_shipping			bill_shipping
     * bill_discount			bill_discount
     * bill_paid				Sum of mcb_payments.payment_amount
     * bill_total			bill_subtotal + bill_tax + bill_shipping - bill_discount
     * bill_balance			bill_total - bill_paid
     */

    /** ITEM TAX OPTION
     * 0 - add to total
     * 1 - include in total
     */

    function adjust($bill_id = NULL) {

        if ($bill_id) {

            /* Adjust a single bill */
            $this->_adjust($bill_id);

        }

        else {

            /* Adjust all bills */
            $this->db->select('bill_id, supplier_id');

            $bills = $this->db->get('mcb_bills')->result();

            foreach ($bills as $bill) {

                $this->_adjust($bill->bill_id);

            }

        }

    }

    function _adjust($bill_id) {

        $this->db->join('mcb_tax_rates', 'mcb_tax_rates.tax_rate_id = mcb_bill_items.tax_rate_id', 'left');

        $this->db->where('bill_id', $bill_id);

        $bill_items = $this->db->get('mcb_bill_items')->result();

        foreach ($bill_items as $item) {

            $this->_update_bill_item_amounts($item);

        }

        $this->_update_bill_amounts($bill_id);

        $this->_update_bill_taxes($bill_id);

        $this->_update_bill_status($bill_id);

    }

    function _update_bill_item_amounts($item) {

        /* Calculations for mcb_bill_item_amounts table */

        $db_array = array(
            'bill_item_id'	=>	$item->bill_item_id,
            'item_subtotal'		=>	$item->item_qty * $item->item_price,
            'item_tax'          =>  '0.00',
            'item_total'        =>  $item->item_qty * $item->item_price
        );

        if ($item->tax_rate_percent) {

            if ($item->item_tax_option == 0) {

                $db_array['item_tax'] = $db_array['item_subtotal'] * ($item->tax_rate_percent / 100);
                $db_array['item_total'] = $db_array['item_subtotal'] + $db_array['item_tax'];

            }

            elseif ($item->item_tax_option == 1) {

                $tax_calc = ($item->tax_rate_percent / 100) + 1;

                $db_array['item_subtotal'] = $db_array['item_subtotal'] / $tax_calc;
                $db_array['item_tax'] = $db_array['item_subtotal'] * ($item->tax_rate_percent / 100);
                $db_array['item_total'] = $db_array['item_subtotal'] + $db_array['item_tax'];

            }

        }

        $this->db->where('bill_item_id', $item->bill_item_id);

        if ($this->db->get('mcb_bill_item_amounts')->num_rows()) {

            $this->db->where('bill_item_id', $item->bill_item_id);
            $this->db->update('mcb_bill_item_amounts', $db_array);

        }

        else {

            $this->db->insert('mcb_bill_item_amounts', $db_array);

        }

    }

    function _update_bill_amounts($bill_id) {

        /* Calculations for mcb_bill_amounts table */

        /**
         * TABLE: mcb_bill_item_amounts
         * bill_item_amount_id
         * bill_item_id
         * item_subtotal
         * item_tax
         * item_total
         */

        /**
         * bill_amount_id
         * bill_id
         * bill_item_subtotal	Sum of mcb_bill_item_amounts.item_total
         * bill_item_tax			Sum of mcb_bill_item_amounts.item_tax
         * bill_subtotal			(bill_item_subtotal + bill_item_tax)
         * bill_tax				Sum of global bill tax amounts
         * bill_shipping			bill_shipping
         * bill_discount			bill_discount
         * bill_paid				Sum of mcb_payments.payment_amount
         * bill_total			bill_subtotal + bill_tax + bill_shipping - bill_discount
         * bill_balance			bill_total - bill_paid
         */

        $this->db->select(
            'IFNULL(SUM(item_subtotal),0.00) AS bill_item_subtotal, ' .
            'IFNULL(SUM(item_tax),0.00) AS bill_item_tax, ' .
            'IFNULL((SELECT SUM(payment_amount) FROM mcb_payments WHERE bill_id = ' . $bill_id . '),0.00) AS bill_paid',
            FALSE
        );

        $this->db->join('mcb_bill_items', 'mcb_bill_items.bill_item_id = mcb_bill_item_amounts.bill_item_id');

        $this->db->where('bill_id', $bill_id);

        $bill_amounts = $this->db->get('mcb_bill_item_amounts')->result();

        foreach ($bill_amounts as $bill_amount) {

            $db_array = array(
                'bill_id'				=>	$bill_id,
                'bill_item_subtotal'		=>	$bill_amount->bill_item_subtotal,
                'bill_item_tax'			=>	$bill_amount->bill_item_tax,
                'bill_subtotal'			=>	$bill_amount->bill_item_subtotal + $bill_amount->bill_item_tax,
                'bill_paid'				=>	$bill_amount->bill_paid
            );

            $this->db->where('bill_id', $bill_id);

            if ($this->db->get('mcb_bill_amounts')->num_rows()) {

                $this->db->where('bill_id', $bill_id);

                $this->db->update('mcb_bill_amounts', $db_array);

            }

            else {

                $this->db->insert('mcb_bill_amounts', $db_array);

            }

        }

    }

    function _update_bill_status($bill_id) {

        $this->load->model('bills/mdl_bills');

        $params = array(
            'where'	=>	array(
                'mcb_bills.bill_id'	=>	$bill_id
            )
        );

        $bill = $this->mdl_bills->get($params);

        if ($bill->bill_balance > 0) {

            /* This bill has a balance */

            if ($bill->bill_status_type == 3) {

                /* This bill currently has a closed status. Update it. */

                $db_array = array(
                    'bill_status_id'	=>	$this->mdl_mcb_data->setting('default_open_status_id')
                );

                $this->db->where('bill_id', $bill_id);

                $this->db->update('mcb_bills', $db_array);

            }

        }

        else {

            /* This bill has no balance */

            if ($bill->bill_status_type <> 3 and $bill->bill_total > 0) {

                /* This bill needs a closed status */

                $db_array = array(
                    'bill_status_id'	=>	$this->mdl_mcb_data->setting('default_closed_status_id')
                );

                $this->db->where('bill_id', $bill_id);

                $this->db->update('mcb_bills', $db_array);

            }

        }

    }

    function _update_bill_taxes($bill_id) {

        /**
         * bill_tax				Sum of global bill tax amounts
         * bill_total			bill_subtotal + bill_tax + bill_shipping - bill_discount
         * bill_balance			bill_total - bill_paid
         */

        /**
         * bill_amount_id
         * bill_id
         * bill_item_subtotal	Sum of mcb_bill_item_amounts.item_total
         * bill_item_tax			Sum of mcb_bill_item_amounts.item_tax
         * bill_subtotal			(bill_item_subtotal + bill_item_tax)
         * bill_tax				Sum of global bill tax amounts
         * bill_shipping			bill_shipping
         * bill_discount			bill_discount
         * bill_paid				Sum of mcb_payments.payment_amount
         * bill_total			bill_subtotal + bill_tax + bill_shipping - bill_discount
         * bill_balance			bill_total - bill_paid
         */

        /** Select the item amount which is taxable **/
        $this->db->select('SUM(item_subtotal) AS bill_item_taxable');
        $this->db->join('mcb_bill_items', 'mcb_bill_items.bill_item_id = mcb_bill_item_amounts.bill_item_id');
        $this->db->where('bill_id', $bill_id);
        $this->db->where('is_taxable', 1);
        $bill_item_taxable = $this->db->get('mcb_bill_item_amounts')->row()->bill_item_taxable;

        /** Update the item amount which is taxable **/
        $this->db->where('bill_id', $bill_id);
        $this->db->set('bill_item_taxable', $bill_item_taxable);
        $this->db->update('mcb_bill_amounts');

        /** Get the bill level tax rates **/
        $this->db->join('mcb_bill_amounts', 'mcb_bill_amounts.bill_id = mcb_bill_tax_rates.bill_id');
        $this->db->join('mcb_tax_rates', 'mcb_tax_rates.tax_rate_id = mcb_bill_tax_rates.tax_rate_id');
        $this->db->where('mcb_bill_tax_rates.bill_id', $bill_id);
        $bill_tax_rates = $this->db->get('mcb_bill_tax_rates')->result();

        foreach ($bill_tax_rates as $rate) {

            if ($rate->tax_rate_option == 1) {

                /* Calculate w/o item taxes */

                $db_array = array(
                    'tax_amount'	=>	$rate->bill_item_taxable * ($rate->tax_rate_percent / 100)
                );

                $this->db->where('bill_tax_rate_id', $rate->bill_tax_rate_id);

                $this->db->update('mcb_bill_tax_rates', $db_array);

            }

            elseif ($rate->tax_rate_option == 2) {

                /* Calculate with item taxes */

                if ($rate->bill_item_taxable > 0) {

                    $db_array = array(
                        'tax_amount'	=>	($rate->bill_item_taxable + $rate->bill_item_tax) * ($rate->tax_rate_percent / 100)
                    );

                }

                else {

                    $db_array['tax_amount'] = 0;

                }

                $this->db->where('bill_tax_rate_id', $rate->bill_tax_rate_id);

                $this->db->update('mcb_bill_tax_rates', $db_array);

            }

            $this->db->select('SUM(tax_amount) AS bill_tax');
            $this->db->where('bill_id', $bill_id);
            $bill_tax = $this->db->get('mcb_bill_tax_rates')->row()->bill_tax;

            $this->db->where('bill_id', $bill_id);
            $this->db->set('bill_tax', $bill_tax);
            $this->db->set('bill_total', 'bill_subtotal + bill_tax - bill_discount + bill_shipping', FALSE);
            $this->db->set('bill_balance', 'bill_total - bill_paid', FALSE);
            $this->db->update('mcb_bill_amounts');

            $this->db->where('bill_balance <', '0.00');

            $db_array = array(
                'bill_balance'	=>	'0.00'
            );

            $this->db->update('mcb_bill_amounts', $db_array);

        }

    }

}


?>