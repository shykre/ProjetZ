<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>
			<?php echo $this->lang->line('invoice_number'); ?>
			<?php echo invoice_id($invoice); ?>
		</title>
		<style type="text/css">

			body {
				font-family: Verdana, Geneva, sans-serif;
				font-size: 12px;
				margin-left: 35px;
				margin-right: 45px;
			}

			th {
				border: 1px solid #666666;
				background-color: #D3D3D3;
			}

			h2 {
				margin-bottom: 0px;
			}

			p.notop {
				margin-top: 0px;
			}

		</style>
	</head>
	<body>

		<table width="100%">
			<tr>
				<td width="60%" valign="top">
					<?php echo invoice_logo($output_type); ?>
					<h2><?php echo invoice_from_company_name($invoice); ?></h2>
					<p class="notop">
						<?php echo invoice_from_name($invoice); ?><br />
						<?php echo invoice_from_address($invoice); ?><br />
						<?php if (invoice_from_address_2($invoice)) { echo invoice_from_address_2($invoice) . '<br />'; } ?>
						<?php echo invoice_from_city_state_zip($invoice); ?><br />
						<?php echo invoice_from_country($invoice); ?><br />
						<?php echo invoice_tax_id($invoice); ?>
					</p>
				</td>
				<td width="40%" align="right" valign="top">
					<h2><?php echo $this->lang->line('invoice'); ?></h2>
					<p class="notop">
						<?php echo $this->lang->line('invoice_number'); ?>
						<?php echo invoice_id($invoice); ?><br />
						<?php echo $this->lang->line('invoice_date'); ?>:
						<?php echo invoice_date_entered($invoice); ?><br />
						<?php echo $this->lang->line('due_date'); ?>:
						<?php echo invoice_due_date($invoice); ?>
					</p>

				</td>
			</tr>
		</table>

		<p>
			<?php echo invoice_to_supplier_name($invoice); ?><br />
			<?php echo invoice_to_address($invoice); ?><br />
			<?php if (invoice_to_address_2($invoice)) { echo invoice_to_address_2($invoice) . '<br />'; } ?>
			<?php echo invoice_to_city($invoice) . ', ' . invoice_to_state($invoice) . ' ' . invoice_to_zip($invoice) . ' ' . invoice_to_country($invoice); ?><br />
			<?php echo invoice_supplier_tax_id($bill); ?>
		</p>

		<br />

		<table style="width: 100%;">
			<tr>
				<th width="10%">
					<?php echo $this->lang->line('quantity'); ?>
				</th>
				<th width="25%">
					<?php echo $this->lang->line('item_name'); ?>
				</th>
				<th width="35%">
					<?php echo $this->lang->line('item_description'); ?>
				</th>
				<th width="15%" align="right">
					<?php echo $this->lang->line('unit'); ?>
				</th>
				<th width="15%" align="right">
					<?php echo $this->lang->line('cost'); ?>
				</th>
			</tr>
			<?php foreach ($bill->bill_items as $item) { ?>
			<tr>
				<td align="center">
					<?php echo bill_item_qty($item); ?>
				</td>
				<td>
					<?php echo bill_item_name($item); ?>
				</td>
				<td>
					<?php echo bill_item_description($item); ?>
				</td>
				<td align="right">
					<?php echo bill_item_unit_price($item); ?>
				</td>
				<td align="right">
					<?php echo bill_item_total($item); ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="3"></td>
				<td colspan="2">
					<hr />
				</td>
			</tr>

			<tr>
				<td colspan="4" align="right">
					<?php echo $this->lang->line('subtotal'); ?>
				</td>
				<td align="right">
					<?php echo bill_subtotal($bill); ?>
				</td>
			</tr>

			<?php if ($bill->bill_shipping > 0) { ?>
			<tr>
				<td colspan="4" align="right">
					<?php echo $this->lang->line('shipping'); ?>
				</td>
				<td align="right">
					<?php echo bill_shipping($bill); ?>
				</td>
			</tr>
			<?php } ?>

			<?php if ($bill->bill_discount > 0) { ?>
			<tr>
				<td colspan="4" align="right">
					<?php echo $this->lang->line('discount'); ?>
				</td>
				<td align="right">
					<?php echo bill_discount($bill); ?>
				</td>
			</tr>
			<?php } ?>

			<tr>
				<td colspan="4" align="right">
					<?php echo $this->lang->line('grand_total'); ?>
				</td>
				<td align="right">
					<?php echo bill_total($bill); ?>
				</td>
			</tr>

			<tr>
				<td colspan="4" align="right">
					<?php echo $this->lang->line('amount_paid'); ?>
				</td>
				<td align="right">
					<?php echo bill_paid($bill); ?>
				</td>
			</tr>

			<tr>
				<td colspan="4" align="right">
					<?php echo $this->lang->line('total_due'); ?>
				</td>
				<td align="right">
					<?php echo bill_balance($bill); ?>
				</td>
			</tr>
		</table>

	</body>
</html>