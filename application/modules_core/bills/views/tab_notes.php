	<dl>
		<dt><label><?php echo $this->lang->line('notes'); ?>: </label></dt>
		<dd><textarea name="bill_notes" id="bill_notes" rows="5" cols="40"><?php echo $bill->bill_notes; ?></textarea></dd>
	</dl>
	<dl>
		<dt><label><?php echo $this->lang->line('tags'); ?>: </label></dt>
		<dd><input type="text" id="tags" name="tags" value="<?php echo $tags; ?>" /></dd>
	</dl>

	<?php foreach ($custom_fields as $field) { ?>
	<dl>
		<dt><label><?php echo $field->field_name ?>: </label></dt>
		<dd><input type="text" id="<?php echo $field->column_name; ?>" name="<?php echo $field->column_name; ?>" value="<?php echo $bill->{$field->column_name}; ?>" /></dd>
	</dl>
	<?php } ?>

    <div style="clear: both;">&nbsp;</div>

	<input type="submit" id="btn_submit" name="btn_submit_notes" value="<?php echo $this->lang->line('save_options'); ?>" />

	<div style="clear: both;">&nbsp;</div>