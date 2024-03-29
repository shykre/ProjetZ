<?php $this->load->view('dashboard/header'); ?>

<?php echo modules::run('bills/widgets/generate_dialog'); ?>

<div class="grid_10" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo (!uri_assoc('is_quote') ? $this->lang->line('bills') : $this->lang->line('quotes')); ?><?php $this->load->view('dashboard/btn_add', array('btn_name'=>(!uri_assoc('is_quote')) ? 'btn_add_bill' : 'btn_add_quote', 'btn_value'=>(!uri_assoc('is_quote')) ? $this->lang->line('create_bill') : $this->lang->line('create_quote'))); ?></h3>

		<div class="content toggle no_padding">

			<?php $this->load->view('dashboard/system_messages'); ?>

			<?php $this->load->view('bill_table'); ?>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/footer'); ?>