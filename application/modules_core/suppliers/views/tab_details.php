<dl>
	<dt><?php echo $this->lang->line('active_supplier'); ?>: </dt>
	<dd><input type="checkbox" name="supplier_active" id="supplier_active" value="1" <?php if ($this->mdl_suppliers->form_value('supplier_active') or (!$_POST and !uri_assoc('supplier_id'))) { ?>checked="checked"<?php } ?> /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('supplier_name'); ?>: </dt>
	<dd><input type="text" name="supplier_name" id="supplier_name" value="<?php echo $this->mdl_suppliers->form_value('supplier_name'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('tax_id_number'); ?>: </dt>
	<dd><input type="text" name="supplier_tax_id" id="supplier_tax_id" value="<?php echo $this->mdl_suppliers->form_value('supplier_tax_id'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('street_address'); ?>: </dt>
	<dd><input type="text" name="supplier_address" id="supplier_address" value="<?php echo $this->mdl_suppliers->form_value('supplier_address'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('street_address_2'); ?>: </dt>
	<dd><input type="text" name="supplier_address_2" id="supplier_address_2" value="<?php echo $this->mdl_suppliers->form_value('supplier_address_2'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('city'); ?>: </dt>
	<dd><input type="text" name="supplier_city" id="supplier_city" value="<?php echo $this->mdl_suppliers->form_value('supplier_city'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('state'); ?>: </dt>
	<dd><input type="text" name="supplier_state" id="supplier_state" value="<?php echo $this->mdl_suppliers->form_value('supplier_state'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('zip'); ?>: </dt>
	<dd><input type="text" name="supplier_zip" id="supplier_zip" value="<?php echo $this->mdl_suppliers->form_value('supplier_zip'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('country'); ?>: </dt>
	<dd><input type="text" name="supplier_country" id="supplier_country" value="<?php echo $this->mdl_suppliers->form_value('supplier_country'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('phone_number'); ?>: </dt>
	<dd><input type="text" name="supplier_phone_number" id="supplier_phone_number" value="<?php echo $this->mdl_suppliers->form_value('supplier_phone_number'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('fax_number'); ?>: </dt>
	<dd><input type="text" name="supplier_fax_number" id="supplier_fax_number" value="<?php echo $this->mdl_suppliers->form_value('supplier_fax_number'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('mobile_number'); ?>: </dt>
	<dd><input type="text" name="supplier_mobile_number" id="supplier_mobile_number" value="<?php echo $this->mdl_suppliers->form_value('supplier_mobile_number'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('email_address'); ?>: </dt>
	<dd><input type="text" name="supplier_email_address" id="supplier_email_address" value="<?php echo $this->mdl_suppliers->form_value('supplier_email_address'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('web_address'); ?>: </dt>
	<dd><input type="text" name="supplier_web_address" id="supplier_web_address" value="<?php echo $this->mdl_suppliers->form_value('supplier_web_address'); ?>" /></dd>
</dl>

<dl>
	<dt><?php echo $this->lang->line('notes'); ?>: </dt>
	<dd><textarea name="supplier_notes" id="supplier_notes" rows="5" cols="40"><?php echo form_prep($this->mdl_suppliers->form_value('supplier_notes')); ?></textarea></dd>
</dl>

<?php foreach ($custom_fields as $custom_field) { ?>
<dl>
	<dt><?php echo $custom_field->field_name; ?>: </dt>
	<dd><input type="text" name="<?php echo $custom_field->column_name; ?>" id="<?php echo $custom_field->column_name; ?>" value="<?php echo $this->mdl_suppliers->form_value($custom_field->column_name); ?>" /></dd>
</dl>
<?php } ?>

<div style="clear: both;">&nbsp;</div>

<input type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" />
<input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />

<div style="clear: both;">&nbsp;</div>