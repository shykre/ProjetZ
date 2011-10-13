<?php $this->load->view('dashboard/header'); ?>

<?php echo modules::run('bills/widgets/generate_dialog'); ?>

<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('bill_search'); ?><?php $this->load->view('dashboard/btn_add', array('btn_name'=>'btn_add_bill', 'btn_value'=>$this->lang->line('create_bill'))); ?></h3>

		<div class="content toggle no_padding">

			<?php $this->load->view('dashboard/system_messages'); ?>

			<?php $this->load->view('bills/bill_table'); ?>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>'bills/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>