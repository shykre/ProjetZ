<?php $this->load->view('dashboard/header'); ?>

<div class="grid_10" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('bill_group_form'); ?></h3>

		<?php $this->load->view('dashboard/system_messages'); ?>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

				<dl>
					<dt><label><?php echo $this->lang->line('name'); ?>: </label></dt>
					<dd><input type="text" name="bill_group_name" id="bill_group_name" value="<?php echo $this->mdl_bill_groups->form_value('bill_group_name'); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('group_prefix'); ?>: </label></dt>
					<dd><input type="text" name="bill_group_prefix" id="bill_group_prefix" value="<?php echo $this->mdl_bill_groups->form_value('bill_group_prefix'); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('group_prefix_year'); ?>: </label></dt>
					<dd><input type="checkbox" name="bill_group_prefix_year" id="bill_group_prefix_year" value="1" <?php if ($this->mdl_bill_groups->form_value('bill_group_prefix_year')) { ?>checked="checked"<?php } ?> /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('group_prefix_month'); ?>: </label></dt>
					<dd><input type="checkbox" name="bill_group_prefix_month" id="bill_group_prefix_month" value="1" <?php if ($this->mdl_bill_groups->form_value('bill_group_prefix_month')) { ?>checked="checked"<?php } ?> /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('group_next_id'); ?>: </label></dt>
					<dd><input type="text" name="bill_group_next_id" id="bill_group_next_id" value="<?php echo $this->mdl_bill_groups->form_value('bill_group_next_id'); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('group_left_pad'); ?>: </label></dt>
					<dd>
						<select id="bill_group_left_pad" name="bill_group_left_pad">
							<?php for ($x = 0; $x <= 10; $x++) { ?>
							<option value="<?php echo $x; ?>" <?php if ($this->mdl_bill_groups->form_value('bill_group_left_pad') == $x) { ?>selected="selected"<?php } ?>><?php echo $x; ?></option>
							<?php } ?>
						</select>
					</dd>
				</dl>

                <div style="clear: both;">&nbsp;</div>

				<input type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" />
				<input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />

			</form>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/footer'); ?>