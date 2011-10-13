<?php $this->load->view('dashboard/header'); ?>

<?php // $this->load->view('dashboard/jquery_date_picker'); ?>

<?php $this->load->view('invoices/jquery_choose_supplier'); ?>

<div class="grid_7" id="content_wrapper">

    <div class="section_wrapper">

        <h3 class="title_black"><?php echo ($this->uri->segment(3) <> 'quote') ? $this->lang->line('create_invoice') : $this->lang->line('create_quote'); ?></h3>

        <div class="content toggle">

            <form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

                <dl>
                    <dt><label><?php echo $this->lang->line('date'); ?>: </label></dt>
                    <dd><input id="datepicker" type="text" name="invoice_date_entered" value="<?php echo date($this->mdl_mcb_data->setting('default_date_format')); ?>" /></dd>
                </dl>
                <dl>
                    <dt><label><?php echo $this->lang->line('supplier'); ?>: </label></dt>
                    <dd>
                        <select name="supplier_id" id="supplier_id">
                            <option value=""></option>
                            <?php foreach ($suppliers as $supplier) { ?>
                            <option value="<?php echo $supplier->supplier_id; ?>" <?php if ($this->mdl_invoices->form_value('supplier_id') == $supplier->supplier_id) { ?>selected="selected"<?php } ?>><?php echo $supplier->supplier_name; ?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
                <dl>
                    <dt><label><?php echo $this->lang->line('group'); ?>: </label></dt>
                    <dd>
                        <select name="bill_group_id" id="bill_group_id">
                            <?php foreach ($bill_groups as $bill_group) { ?>
                            <?php if ($this->uri->segment(3) <> 'quote') { ?>
                            <option value="<?php echo $bill_group->bill_group_id; ?>" <?php if ($this->mdl_mcb_data->setting('default_bill_group_id') == $bill_group->bill_group_id) { ?>selected="selected"<?php } ?>><?php echo $bill_group->bill_group_name; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $bill_group->bill_group_id; ?>" <?php if ($this->mdl_mcb_data->setting('default_quote_group_id') == $bill_group->bill_group_id) { ?>selected="selected"<?php } ?>><?php echo $bill_group->bill_group_name; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>

                <?php if ($this->uri->segment(3) == 'quote') { ?>
                <input id="bill_is_quote" type="hidden" name="bill_is_quote" value="1" />
                <?php } ?>

                <div style="clear: both;">&nbsp;</div>

                <input type="submit" id="btn_submit" name="btn_submit" value="<?php echo ($this->uri->segment(3) <> 'quote') ? $this->lang->line('create_bill') : $this->lang->line('create_quote'); ?>" />
                <input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />

            </form>

        </div>

    </div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>'bills/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>