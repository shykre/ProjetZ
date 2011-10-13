<table style="width: 100%;">
    <tr>
        <th scope="col" class="first"><?php echo $this->lang->line('status'); ?></th>
        <?php if (isset($sort_links) and $sort_links <> FALSE) { ?>
        <th scope="col">
        <?php if ($this->uri->segment(3) == 'is_quote') {
            if ($this->uri->segment(6) == 'bill_id_asc') {
                echo anchor('bills/index/is_quote/1/order_by/bill_id_desc', $this->lang->line('bill_number'));
            } else {
                echo anchor('bills/index/is_quote/1/order_by/bill_id_asc', $this->lang->line('bill_number'));
            }
        } else {
            if ($this->uri->segment(4) == 'bill_id_asc') {
                echo anchor('bills/index/order_by/bill_id_desc', $this->lang->line('bill_number'));
            } else {
                echo anchor('bills/index/order_by/bill_id_asc', $this->lang->line('bill_number'));
            }
        } ?>
        </th>
        <th scope="col">
        <?php if ($this->uri->segment(3) == 'is_quote') {
            if ($this->uri->segment(6) == 'date_asc') {
                echo anchor('bills/index/is_quote/1/order_by/date_desc', $this->lang->line('date'));
            } else {
                echo anchor('bills/index/is_quote/1/order_by/date_asc', $this->lang->line('date'));
            }
        } else {
            if ($this->uri->segment(4) == 'date_asc') {
                echo anchor('bills/index/order_by/date_desc', $this->lang->line('date'));
            } else {
                echo anchor('bills/index/order_by/date_asc', $this->lang->line('date'));
            }
        } ?>
        </th>
        <th scope="col">
        <?php if ($this->uri->segment(3) == 'is_quote') {
            if ($this->uri->segment(6) == 'duedate_asc') {
                echo anchor('bills/index/is_quote/1/order_by/duedate_desc', $this->lang->line('due_date'));
            } else {
                echo anchor('bills/index/is_quote/1/order_by/duedate_asc', $this->lang->line('due_date'));
            }
        } else {
            if ($this->uri->segment(4) == 'duedate_asc') {
                echo anchor('bills/index/order_by/duedate_desc', $this->lang->line('due_date'));
            } else {
                echo anchor('bills/index/order_by/duedate_asc', $this->lang->line('due_date'));
            }
        } ?>
        </th>
        <th scope="col" class="supplier">
        <?php if ($this->uri->segment(3) == 'is_quote') {
            if ($this->uri->segment(6) == 'supplier_asc') {
                echo anchor('bills/index/is_quote/1/order_by/supplier_desc', $this->lang->line('supplier'));
            } else {
                echo anchor('bills/index/is_quote/1/order_by/supplier_asc', $this->lang->line('supplier'));
            }
        } else {
            if ($this->uri->segment(4) == 'supplier_asc') {
                echo anchor('bills/index/order_by/supplier_desc', $this->lang->line('supplier'));
            } else {
                echo anchor('bills/index/order_by/supplier_asc', $this->lang->line('supplier'));
            }
        } ?>
        </th>
        <th scope="col" class="col_amount">
        <?php if ($this->uri->segment(3) == 'is_quote') {
            if ($this->uri->segment(6) == 'amount_asc') {
                echo anchor('bills/index/is_quote/1/order_by/amount_desc', $this->lang->line('amount'));
            } else {
                echo anchor('bills/index/is_quote/1/order_by/amount_asc', $this->lang->line('amount'));
            }
        } else {
            if ($this->uri->segment(4) == 'amount_asc') {
                echo anchor('bills/index/order_by/amount_desc', $this->lang->line('amount'));
            } else {
                echo anchor('bills/index/order_by/amount_asc', $this->lang->line('amount'));
            }
        } ?>
        </th>
        <th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
        <?php } else { ?>
        <th scope="col"><?php echo (!uri_assoc('is_quote') ? $this->lang->line('bill_number') : $this->lang->line('quote_number')); ?></th>
        <th scope="col"><?php echo $this->lang->line('date'); ?></th>
        <th scope="col"><?php echo $this->lang->line('due_date'); ?></th>
        <th scope="col" class="supplier"><?php echo $this->lang->line('supplier'); ?></th>
        <th scope="col" class="col_amount"><?php echo $this->lang->line('amount'); ?></th>
        <th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
        <?php } ?>
    </tr>
    <?php foreach ($bills as $bill) { ?>

    <tr class="hoverall">
        <td class="first bill_<?php if ($bill->bill_is_overdue) { ?>4<?php } else { echo $bill->bill_status_type; } ?>"><?php echo ($bill->bill_is_overdue) ? $this->lang->line('overdue') : $bill->bill_status; ?></td>
        <td><?php echo bill_id($bill); ?></td>
        <td><?php echo format_date($bill->bill_date_entered); ?></td>
        <td><?php echo format_date($bill->bill_due_date); ?></td>
        <td class="supplier"><?php echo anchor('suppliers/details/supplier_id/' . $bill->supplier_id, character_limiter($bill->supplier_name, 25)); ?></td>
        <td class="col_amount"><?php echo display_currency($bill->bill_total); ?></td>
        <td class="last">
            <a href="<?php echo site_url('bills/edit/bill_id/' . $bill->bill_id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
            <?php echo icon('edit'); ?>
            </a>
            <a href="javascript:void(0)" class="output_link" id="<?php echo $bill->bill_id . ':' . $bill->supplier_id . ':' . $bill->bill_is_quote; ?>" title="<?php echo $this->lang->line('generate'); ?>">
            <?php echo icon('generate_bill'); ?>
            </a>
            <a href="<?php echo site_url('bills/delete/bill_id/' . $bill->bill_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
            <?php echo icon('delete'); ?>
            </a>
        </td>
    </tr>

        <?php } ?>
</table>

<?php if ($this->mdl_bills->page_links) { ?>
<div id="pagination">
<?php echo $this->mdl_bills->page_links; ?>
</div>
<?php } ?>