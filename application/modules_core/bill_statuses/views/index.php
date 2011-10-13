<?php $this->load->view('dashboard/header'); ?>

<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('bill_statuses'); ?><?php $this->load->view('dashboard/btn_add', array('btn_value'=>$this->lang->line('add_bill_status'))); ?></h3>

		<div class="content toggle no_padding">

			<table>
				<tr>
					<th scope="col" class="first"><?php echo $this->lang->line('id'); ?></th>
					<th scope="col"><?php echo $this->lang->line('bill_status'); ?></th>
					<th scope="col"><?php echo $this->lang->line('bill_status_type'); ?></th>
					<th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
				</tr>
				<?php foreach ($bill_statuses as $bill_status) { ?>
				<tr>
					<td class="first"><?php echo $bill_status->bill_status_id; ?></td>
					<td><?php echo $bill_status->bill_status; ?></td>
					<td><?php echo $this->mdl_bill_statuses->status_types[$bill_status->bill_status_type]; ?></td>
					<td class="last">
						<a href="<?php echo site_url('bill_statuses/form/bill_status_id/' . $bill_status->bill_status_id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
							<?php echo icon('edit'); ?>
						</a>
						<a href="<?php echo site_url('bill_statuses/delete/bill_status_id/' . $bill_status->bill_status_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
							<?php echo icon('delete'); ?>
						</a>
					</td>
				</tr>
				<?php } ?>
			</table>

			<?php if ($this->mdl_bill_statuses->page_links) { ?>
			<div id="pagination">
				<?php echo $this->mdl_bill_statuses->page_links; ?>
			</div>
			<?php } ?>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>array('bill_statuses/sidebar', 'settings/sidebar'),'hide_quicklinks'=>TRUE)); ?>

<?php $this->load->view('dashboard/footer'); ?>