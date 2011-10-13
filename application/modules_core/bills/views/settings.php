<dl>
    <dt><?php echo $this->lang->line('default_bill_group'); ?></dt>
    <dd>
	<select name="default_bill_group_id">
	    <?php foreach ($bill_groups as $bill_group) { ?>
	    <option value="<?php echo $bill_group->bill_group_id; ?>" <?php if($this->mdl_mcb_data->setting('default_bill_group_id') == $bill_group->bill_group_id) { ?>selected="selected"<?php } ?>><?php echo $bill_group->bill_group_name; ?></option>
	    <?php } ?>
	</select>
    </dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('default_quote_group'); ?></dt>
    <dd>
        <select name="default_quote_group_id">
            <?php foreach ($bill_groups as $bill_group) { ?>
            <option value="<?php echo $bill_group->bill_group_id; ?>" <?php if($this->mdl_mcb_data->setting('default_quote_group_id') == $bill_group->bill_group_id) { ?>selected="selected"<?php } ?>><?php echo $bill_group->bill_group_name; ?></option>
            <?php } ?>
        </select>
    </dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('default_bill_tax_rate'); ?></dt>
    <dd>
	<select name="default_tax_rate_id">
	    <?php foreach ($tax_rates as $tax_rate) { ?>
	    <option value="<?php echo $tax_rate->tax_rate_id; ?>" <?php if($this->mdl_mcb_data->setting('default_tax_rate_id') == $tax_rate->tax_rate_id) { ?>selected="selected"<?php } ?>><?php echo $tax_rate->tax_rate_percent; ?>% - <?php echo $tax_rate->tax_rate_name; ?></option>
	    <?php } ?>
	</select>
    </dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('default_bill_tax_placement'); ?></dt>
    <dd>
		<select name="default_tax_rate_option">
			<option value="1" <?php if ($this->mdl_mcb_data->setting('default_tax_rate_option') == 1) { ?>selected="selected"<?php } ?>><?php echo $this->lang->line('bill_tax_option_1'); ?></option>
			<option value="2" <?php if ($this->mdl_mcb_data->setting('default_tax_rate_option') == 2) { ?>selected="selected"<?php } ?>><?php echo $this->lang->line('bill_tax_option_2'); ?></option>
		</select>
    </dd>
<dl>
    <dt><?php echo $this->lang->line('default_item_tax_rate'); ?></dt>
    <dd>
	<select name="default_item_tax_rate_id">
	    <?php foreach ($tax_rates as $tax_rate) { ?>
	    <option value="<?php echo $tax_rate->tax_rate_id; ?>" <?php if($this->mdl_mcb_data->setting('default_item_tax_rate_id') == $tax_rate->tax_rate_id) { ?>selected="selected"<?php } ?>><?php echo $tax_rate->tax_rate_percent; ?>% - <?php echo $tax_rate->tax_rate_name; ?></option>
	    <?php } ?>
	</select>
    </dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('default_open_bill_status'); ?></dt>
    <dd>
	<select name="default_open_status_id">
	    <?php foreach ($open_bill_statuses as $status) { ?>
	    <option value="<?php echo $status->bill_status_id; ?>" <?php if($this->mdl_mcb_data->setting('default_open_status_id') == $status->bill_status_id) { ?>selected="selected"<?php } ?>><?php echo $status->bill_status; ?></option>
	    <?php } ?>
	</select>
    </dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('default_closed_bill_status'); ?></dt>
    <dd>
	<select name="default_closed_status_id">
	    <?php foreach ($closed_bill_statuses as $status) { ?>
	    <option value="<?php echo $status->bill_status_id; ?>" <?php if($this->mdl_mcb_data->setting('default_closed_status_id') == $status->bill_status_id) { ?>selected="selected"<?php } ?>><?php echo $status->bill_status; ?></option>
	    <?php } ?>
	</select>
    </dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('bills_due_after'); ?></dt>
    <dd><input type="text" name="bills_due_after" value="<?php echo $this->mdl_mcb_data->setting('bills_due_after'); ?>" /></dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('default_bill_template'); ?></dt>
    <dd>
	<select name="default_bill_template">
	    <?php foreach ($templates as $template) { ?>
	    <option <?php if($this->mdl_mcb_data->setting('default_bill_template') == $template) { ?>selected="selected"<?php } ?>><?php echo $template; ?></option>
	    <?php } ?>
	</select>
    </dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('default_quote_template'); ?></dt>
    <dd>
	<select name="default_quote_template">
	    <?php foreach ($templates as $template) { ?>
	    <option <?php if($this->mdl_mcb_data->setting('default_quote_template') == $template) { ?>selected="selected"<?php } ?>><?php echo $template; ?></option>
	    <?php } ?>
	</select>
    </dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('tax_rate_decimals'); ?></dt>
    <dd>
	<select name="decimal_taxes_num">
	    <option value="2" <?php if ($this->mdl_mcb_data->setting('decimal_taxes_num') == 2) { ?>selected="selected"<?php } ?>>2</option>
	    <option value="3" <?php if ($this->mdl_mcb_data->setting('decimal_taxes_num') == 3) { ?>selected="selected"<?php } ?>>3</option>
	</select>
	<input type="checkbox" name="update_decimal_taxes" value="1" /> <?php echo $this->lang->line('update'); ?>
    </dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('bill_logo'); ?></dt>
    <dd>
	<?php if ($this->mdl_mcb_data->setting('bill_logo')) { ?>
	<img src="<?php echo base_url(); ?>uploads/bill_logos/<?php echo $this->mdl_mcb_data->setting('bill_logo'); ?>" /><br />
	    <?php echo anchor('bills/upload_logo', $this->lang->line('upload_another_bill_logo')) . ' | ' . anchor('bills/upload_logo/delete/bill_logo/' . $this->mdl_mcb_data->setting('bill_logo'), $this->lang->line('delete_bill_logo'));
	} else {
    echo anchor('bills/upload_logo', $this->lang->line('upload_bill_logo'));
} ?>
    </dd>
</dl>
<?php if (count($bill_logos)) { ?>
<dl>
    <dt><?php echo $this->lang->line('change_bill_logo'); ?></dt>
    <dd>
	<select name="bill_logo">
		<?php foreach ($bill_logos as $logo) { ?>
	    <option <?php if ($logo == $this->mdl_mcb_data->setting('bill_logo')) { ?>selected="selected"<?php } ?>><?php echo $logo; ?></option>
	<?php } ?>
	</select>
    </dd>
</dl>
<?php } ?>

<?php if ($this->mdl_mcb_data->setting('bill_logo')) { ?>
<dl>
    <dt><?php echo $this->lang->line('include_logo_on_bill'); ?></dt>
    <dd><input type="checkbox" name="include_logo_on_bill" value="TRUE" <?php if($this->mdl_mcb_data->setting('include_logo_on_bill') == "TRUE") { ?>checked="checked"<?php } ?> /></dd>
</dl>
<?php } ?>

<dl>
    <dt><?php echo $this->lang->line('recalculate_bills'); ?></dt>
    <dd><?php echo anchor('bills/recalculate', $this->lang->line('recalculate_bills')); ?></dd>
</dl>

<dl>
    <dt><?php echo $this->lang->line('display_quantity_decimals'); ?></dt>
    <dd><input type="checkbox" name="display_quantity_decimals" value="1" <?php if ($this->mdl_mcb_data->setting('display_quantity_decimals')) { ?>checked="checked"<?php } ?>/></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('disable_bill_audit_history'); ?></dt>
	<dd><input type="checkbox" name="disable_bill_audit_history" value="1" <?php if ($this->mdl_mcb_data->setting('disable_bill_audit_history')) { ?>checked="checked"<?php } ?>/></dd>
</dl>