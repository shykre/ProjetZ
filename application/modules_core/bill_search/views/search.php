<?php $this->load->view('dashboard/header'); ?>

<?php $this->load->view('dashboard/jquery_date_picker'); ?>

<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('bill_search'); ?></h3>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

				<dl>
					<dt><label><?php echo $this->lang->line('bill_number'); ?>: </label></dt>
					<dd><input type="text" name="bill_number" value="<?php echo $this->mdl_bill_search->form_value('bill_number'); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('from_date'); ?>: </label></dt>
					<dd><input type="text" class="datepicker" name="from_date" value="<?php echo $this->mdl_bill_search->form_value('from_date'); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('to_date'); ?>: </label></dt>
					<dd><input type="text" class="datepicker" name="to_date" value="<?php echo $this->mdl_bill_search->form_value('to_date'); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('suppliers'); ?>: </label></dt>
					<dd>
						<select name="supplier_id[]" id="supplier_id" multiple size="10" style="width: 400px;">
							<?php foreach ($suppliers as $supplier) { ?>
								<option value="<?php echo $supplier->supplier_id; ?>" <?php if ($this->mdl_bill_search->form_value('supplier_id') == $supplier->supplier_id) { ?>selected="selected"<?php } ?>><?php echo $supplier->supplier_name; ?></option>
							<?php } ?>
						</select>
					</dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('bill_statuses'); ?>: </label></dt>
					<dd>
						<select name="bill_status_id[]" id="bill_status_id" multiple size="5" style="width: 400px;">
							<?php foreach ($bill_statuses as $bill_status) { ?>
								<option value="<?php echo $bill_status->bill_status_id; ?>" <?php if ($this->mdl_bill_search->form_value('bill_status_id') == $bill_status->bill_status_id) { ?>selected="selected"<?php } ?>><?php echo $bill_status->bill_status; ?></option>
							<?php } ?>
						</select>
					</dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('amount'); ?>: </label></dt>
					<dd>
						<select name="amount_operator" id="amount_operator">
							<option></option>
							<option>=</option>
							<option>>=</option>
							<option>></option>
							<option><=</option>
							<option><</option>
						</select>
						<input type="text" name="amount" value="<?php echo $this->mdl_bill_search->form_value('amount'); ?>" />
					</dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('tags'); ?>: </label></dt>
					<dd><input id="tags" type="text" name="tags" value="<?php echo $this->mdl_bill_search->form_value('tags'); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('include_quotes'); ?>: </label></dt>
					<dd><input id="include_quotes" type="checkbox" name="include_quotes" value="1" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('output_type'); ?>: </label></dt>
					<dd>
						<select name="output_type" id="output_type">
							<option value="index"><?php echo $this->lang->line('view'); ?></option>
							<option value="html"><?php echo $this->lang->line('html'); ?></option>
							<option value="pdf"><?php echo $this->lang->line('pdf'); ?></option>
							<option value="csv"><?php echo $this->lang->line('csv'); ?></option>
						</select>
					</dd>
				</dl>

                <div style="clear: both;">&nbsp;</div>

				<input type="submit" id="btn_submit" name="btn_search" value="<?php echo $this->lang->line('search'); ?>" />

				<div style="clear: both;">&nbsp;</div>

			</form>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>'bills/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>