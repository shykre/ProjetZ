<div class="left_box">

    <dl>
        <dt><label><?php echo $this->lang->line('bill_number'); ?>: </label></dt>
        <dd><input type="text" name="bill_number" value="<?php echo $bill->bill_number; ?>" /></dd>
    </dl>

	<dl>
		<dt><label><?php echo $this->lang->line('supplier'); ?>: </label></dt>
		<dd>
			<?php if ($bill->supplier_active) { ?>
			<select name="supplier_id">
				<?php foreach ($suppliers as $supplier) { ?>
				<option value="<?php echo $supplier->supplier_id; ?>" <?php if($bill->supplier_id == $supplier->supplier_id) { ?>selected="selected"<?php } ?>><?php echo character_limiter($supplier->supplier_name, 25); ?></option>
					<?php } ?>
			</select>
			<?php } else { ?>
			<?php echo $bill->supplier_name; ?>
			<?php } ?>
		</dd>
	</dl>

	<dl>
		<dt><label><?php echo $this->lang->line('user'); ?>: </label></dt>
		<dd>
			<select name="user_id">
                <?php if (!$bill->from_first_name) { ?>
                <option value=""><?php echo $this->lang->line('unassigned'); ?></option>
                <?php } ?>
				<?php foreach ($users as $user) { ?>
				<option value="<?php echo $user->user_id; ?>" <?php if($bill->user_id == $user->user_id) { ?>selected="selected"<?php } ?>><?php echo $user->first_name . ' ' . $user->last_name; ?></option>
					<?php } ?>
			</select>
        </dd>
	</dl>

	<?php if (!$bill->bill_is_quote) { ?>
	<dl>
		<dt><label><?php echo $this->lang->line('bill_status'); ?>: </label></dt>
		<dd>
			<select name="bill_status_id">
				<?php foreach ($bill_statuses as $bill_status) { ?>
				<option value="<?php echo $bill_status->bill_status_id; ?>" <?php if($bill_status->bill_status_id == $bill->bill_status_id) { ?>selected="selected"<?php } ?>><?php echo $bill_status->bill_status; ?></option>
				<?php } ?>
			</select>
		</dd>
	</dl>
	<?php } ?>

	<dl>
		<dt><label><?php echo (!$bill->bill_is_quote ? $this->lang->line('bill_date') : $this->lang->line('date')); ?>: </label></dt>
		<dd><input class="datepicker" type="text" name="bill_date_entered" value="<?php echo format_date($bill->bill_date_entered); ?>" /></dd>
	</dl>

	<?php if (!$bill->bill_is_quote) { ?>
	<dl>
		<dt><label><?php echo $this->lang->line('due_date'); ?>: </label></dt>
		<dd><input class="datepicker" type="text" name="bill_due_date" value="<?php echo format_date($bill->bill_due_date); ?>" /></dd>
	</dl>
	<?php } ?>

	<?php if ($bill->bill_is_overdue and $bill->bill_days_overdue > 0) { ?>
	<dl>
		<dt><label style="color: red; font-weight: bold;"><?php echo $this->lang->line('days_overdue'); ?>: </label></dt>
		<dd><span style="color: red; font-weight: bold;"><?php echo $bill->bill_days_overdue; ?></span></dd>
	</dl>
	<?php } elseif ($bill->bill_days_overdue <= 0) { ?>
	<dl>
		<dt><label><?php echo $this->lang->line('days_until_due'); ?>: </label></dt>
		<dd><?php echo ($bill->bill_days_overdue * -1); ?></dd>
	</dl>
	<?php } ?>

	<dl>
		<dt><label><?php echo $this->lang->line('generate'); ?>: </label></dt>
		<dd>
			<a href="javascript:void(0)" class="output_link" id="<?php echo $bill->bill_id . ':' . $bill->supplier_id . ':' . $bill->bill_is_quote; ?>"><?php echo $this->lang->line('generate'); ?></a>
		</dd>
	</dl>

    <div style="clear: both;">&nbsp;</div>

	<input type="submit" id="btn_submit" name="btn_submit_options_general" value="<?php echo $this->lang->line('save_options'); ?>" />

</div>

<div class="right_box">

	<dl>
		<dt><label><?php echo $this->lang->line('subtotal'); ?>: </label></dt>
		<dd><?php echo bill_item_subtotal($bill); ?></dd>
	</dl>

	<dl>
		<dt><label><?php echo $this->lang->line('tax'); ?>: </label></dt>
		<dd><?php echo bill_tax_total($bill); ?></dd>
	</dl>

	<?php if ($bill->bill_shipping > 0) { ?>
	<dl>
		<dt><label><?php echo $this->lang->line('shipping'); ?>: </label></dt>
		<dd><?php echo bill_shipping($bill); ?></dd>
	</dl>
	<?php } ?>

	<?php if ($bill->bill_discount > 0) { ?>
	<dl>
		<dt><label><?php echo $this->lang->line('discount'); ?>: </label></dt>
		<dd><?php echo bill_discount($bill); ?></dd>
	</dl>
	<?php } ?>

	<dl>
		<dt><label><?php echo $this->lang->line('grand_total'); ?>: </label></dt>
		<dd><?php echo bill_total($bill); ?></dd>
	</dl>

	<?php if (!$bill->bill_is_quote) { ?>
	<dl>
		<dt><label><?php echo $this->lang->line('paid'); ?>: </label></dt>
		<dd><?php echo bill_paid($bill); ?></dd>
	</dl>

	<dl>
		<dt><label><?php echo $this->lang->line('bill_balance'); ?>: </label></dt>
		<dd><?php echo bill_balance($bill); ?></dd>
	</dl>
	<?php } ?>

</div>

<div style="clear: both;">&nbsp;</div>