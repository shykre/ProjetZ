<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>
			<?php echo $this->lang->line('quote_number'); ?>
			<?php echo bill_id($bill); ?>
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
					<?php echo bill_logo($output_type); ?>
					<h2><?php echo bill_from_company_name($bill); ?></h2>
					<p class="notop">
						<?php echo bill_from_name($bill); ?><br />
						<?php echo bill_from_address($bill); ?><br />
						<?php if (bill_from_address_2($bill)) { echo bill_from_address_2($bill) . '<br />'; } ?>
						<?php echo bill_from_city_state_zip($bill); ?><br />
						<?php echo bill_from_country($bill); ?><br />
						<?php echo bill_tax_id ($bill); ?>
					</p>
				</td>
				<td width="40%" align="right" valign="top">
					<h2><?php echo $this->lang->line('quote'); ?></h2>
					<p class="notop">
						<?php echo $this->lang->line('quote_number'); ?>
						<?php echo bill_id($bill); ?><br />
						<?php echo $this->lang->line('date'); ?>:
						<?php echo bill_date_entered($bill); ?>
					</p>

				</td>
			</tr>
		</table>

		<p>
			<?php echo bill_to_supplier_name($bill); ?><br />
			<?php echo bill_to_address($bill); ?><br />
			<?php if (bill_to_address_2($bill)) { echo bill_to_address_2($bill) . '<br />'; } ?>
			<?php echo bill_to_city($bill) . ', ' . bill_to_state($bill) . ' ' . bill_to_zip($bill) . ' ' . bill_to_country($bill); ?><br />
			<?php echo bill_supplier_tax_id($bill); ?>
		</p>

		<br />

		<table style="width: 100%;">
			<tr>
				<th width="15%">
					<?php echo $this->lang->line('quantity'); ?>
				</th>
				<th width="40%">
					<?php echo nl2br($this->lang->line('item')); ?>
				</th>
				<th width="15%" align="right">
					<?php echo $this->lang->line('unit'); ?>
				</th>
				<th width="15%" align="right">
					<?php echo $this->lang->line('tax'); ?>
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
					<?php echo bill_item($item); ?>
				</td>
				<td align="right">
					<?php echo bill_item_unit_price($item); ?>
				</td>
				<td align="right">
					<?php echo bill_item_tax($item); ?>
				</td>
				<td align="right">
					<?php echo bill_item_total($item); ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="2"></td>
				<td colspan="3">
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

			<?php foreach ($bill->bill_tax_rates as $bill_tax_rate) { ?>
			<?php if (bill_has_tax($bill_tax_rate)) { ?>
			<tr>
				<td colspan="4" align="right">
					<?php echo bill_tax_rate_name($bill_tax_rate); ?>
				</td>
				<td align="right">
					<?php echo bill_tax_rate_amount($bill_tax_rate); ?>
				</td>
			</tr>
			<?php } ?>
			<?php } ?>

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

		</table>

	</body>
</html>