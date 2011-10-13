<?php $this->load->view('dashboard/header', array('header_insert'=>'suppliers/details_header')); ?>

<?php echo modules::run('bills/widgets/generate_dialog'); ?>

<script type="text/javascript">
	$(function(){
		$('#tabs').tabs({ selected: <?php echo isset($tab_index) ? $tab_index : 0; ?> });
	});
</script>

<div class="container_10" id="center_wrapper">

	<div class="grid_10" id="content_wrapper">

		<div class="section_wrapper">

			<h3 class="title_black"><?php echo $supplier->supplier_name; ?>
				
				<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" style="display: inline;">
				<input type="submit" name="btn_add_contact" style="float: right; margin-top: 10px; margin-right: 10px;" value="<?php echo $this->lang->line('add_contact'); ?>" />
				<input type="submit" name="btn_edit_supplier" style="float: right; margin-top: 10px; margin-right: 10px;" value="<?php echo $this->lang->line('edit_supplier'); ?>" />
				<?php if ($supplier->supplier_active) { ?>
                <input type="submit" name="btn_add_bill" style="float: right; margin-top: 10px; margin-right: 10px;" value="<?php echo $this->lang->line('create_bill'); ?>" />
				<input type="submit" name="btn_add_quote" style="float: right; margin-top: 10px; margin-right: 10px;" value="<?php echo $this->lang->line('create_quote'); ?>" />
                <?php } ?>
				</form>

			</h3>

			<div class="content toggle">

				<div id="tabs">

					<ul>
						<li><a href="#tab_supplier"><?php echo $this->lang->line('supplier'); ?></a></li>
						<li><a href="#tab_contacts"><?php echo $this->lang->line('contacts'); ?></a></li>
						<li><a href="#tab_bills"><?php echo $this->lang->line('bills'); ?></a></li>
					</ul>

					<div id="tab_supplier">

						<div class="left_box">

							<dl>
								<dt><?php echo $this->lang->line('street_address'); ?>: </dt>
								<dd><?php echo $supplier->supplier_address; ?><?php if ($supplier->supplier_address_2) { ?><br /><?php echo $supplier->supplier_address_2;} ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('city'); ?>: </dt>
								<dd><?php echo $supplier->supplier_city; ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('state'); ?>: </dt>
								<dd><?php echo $supplier->supplier_state; ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('zip'); ?>: </dt>
								<dd><?php echo $supplier->supplier_zip; ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('country'); ?>: </dt>
								<dd><?php echo $supplier->supplier_country; ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('email_address'); ?>: </dt>
								<dd><?php echo auto_link($supplier->supplier_email_address); ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('web_address'); ?>: </dt>
								<dd><?php echo auto_link($supplier->supplier_web_address, 'both', TRUE); ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('phone_number'); ?>: </dt>
								<dd><?php echo $supplier->supplier_phone_number; ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('fax_number'); ?>: </dt>
								<dd><?php echo $supplier->supplier_fax_number; ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('mobile_number'); ?>: </dt>
								<dd><?php echo $supplier->supplier_mobile_number; ?></dd>
							</dl>

						</div>

						<div class="right_box">

							<dl>
								<dt><?php echo $this->lang->line('total_billed'); ?>: </dt>
								<dd><?php echo display_currency($supplier->supplier_total_bill); ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('total_paid'); ?>: </dt>
								<dd><?php echo display_currency($supplier->supplier_total_payment); ?></dd>
							</dl>

							<dl>
								<dt><?php echo $this->lang->line('total_balance'); ?>: </dt>
								<dd><?php echo display_currency($supplier->supplier_total_balance); ?></dd>
							</dl>
							<dl>
								<dt><?php echo $this->lang->line('tax_id_number'); ?>: </dt>
								<dd><?php echo $supplier->supplier_tax_id; ?></dd>
							</dl>
							<dl>
								<dt><?php echo $this->lang->line('notes'); ?>: </dt>
								<dd><?php echo nl2br($supplier->supplier_notes); ?></dd>
							</dl>

						</div>

						<div style="clear: both;">&nbsp;</div>


					</div>

					<div id="tab_contacts">

						<?php $this->load->view('contact_table'); ?>

					</div>

					<div id="tab_bills">
						<?php $this->load->view('bills/bill_table'); ?>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/footer'); ?>