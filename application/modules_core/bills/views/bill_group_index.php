<?php $this->load->view('dashboard/header'); ?>

<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('bill_groups'); ?><?php $this->load->view('dashboard/btn_add', array('btn_value'=>$this->lang->line('add_bill_group'))); ?></h3>

		<?php $this->load->view('dashboard/system_messages'); ?>

		<div class="content toggle no_padding">

			<table>
				<tr>
					<th scope="col" class="first"><?php echo $this->lang->line('name'); ?></th>
					<th scope="col"><?php echo $this->lang->line('group_prefix'); ?></th>
					<th scope="col"><?php echo $this->lang->line('group_next_id'); ?></th>
					<th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
				</tr>
				<?php foreach ($bill_groups as $bill_group) { ?>
				<tr>
					<td class="first"><?php echo $bill_group->bill_group_name; ?></td>
					<td><?php echo $bill_group->bill_group_prefix; ?></td>
					<td><?php echo $bill_group->bill_group_next_id; ?></td>
					<td class="last">
						<a href="<?php echo site_url('bills/bill_groups/form/bill_group_id/' . $bill_group->bill_group_id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
							<?php echo icon('edit'); ?>
						</a>
						<a href="<?php echo site_url('bills/bill_groups/delete/bill_group_id/' . $bill_group->bill_group_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
							<?php echo icon('delete'); ?>
						</a>
					</td>
				</tr>
				<?php } ?>
			</table>

			<?php if ($this->mdl_bill_groups->page_links) { ?>
			<div id="pagination">
				<?php echo $this->mdl_bill_groups->page_links; ?>
			</div>
			<?php } ?>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>array('bills/bill_group_sidebar'),'hide_quicklinks'=>TRUE)); ?>

<?php $this->load->view('dashboard/footer'); ?>