<?php $this->load->view('dashboard/jquery_table_dnd'); ?>

<table style="width: 100%;" id="dnd">

	<tr>
		<th scope="col" class="first" style="width: 25%;"><?php echo $this->lang->line('item_name'); ?></th>
		<th scope="col" style="width: 35%;"><?php echo $this->lang->line('item_description'); ?></th>
		<th scope="col" style="width: 10%;"><?php echo $this->lang->line('quantity'); ?></th>
		<th scope="col"  style="width: 10%;"><?php echo $this->lang->line('unit_price'); ?></th>
		<th scope="col" style="width: 10%;"><?php echo $this->lang->line('taxable'); ?></th>
		<th scope="col" class="last" style="width: 10%;"><?php echo $this->lang->line('actions'); ?></th>
	</tr>

	<?php foreach ($bill_items as $bill_item) {
		if(!uri_assoc('bill_item_id', 4) OR uri_assoc('bill_item_id', 4) <> $bill_item->bill_item_id) { ?>
		<tr id="<?php echo $bill_item->bill_item_id; ?>">
			<td class="first"><?php echo $bill_item->item_name; ?></td>
			<td><?php echo character_limiter($bill_item->item_description, 40); ?></td>
			<td><?php echo format_qty($bill_item->item_qty); ?></td>
			<td><?php echo display_currency($bill_item->item_price); ?></td>
			<td><?php if ($bill_item->is_taxable) { echo icon('check'); } ?></td>
			<td class="last">
				<a href="<?php echo site_url('bills/items/form/bill_id/' . uri_assoc('bill_id') . '/bill_item_id/' . $bill_item->bill_item_id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
					<?php echo icon('edit'); ?>
				</a>
				<a href="<?php echo site_url('bills/items/delete/bill_id/' . uri_assoc('bill_id') . '/bill_item_id/' . $bill_item->bill_item_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
					<?php echo icon('delete'); ?>
				</a>
			</td>
		</tr>
	<?php } } ?>

</table>