<div class="section_wrapper">

	<h3 class="title_white"><?php echo $this->lang->line('bills'); ?></h3>

	<ul class="quicklinks content toggle">
		<li><?php echo anchor('bills/index', $this->lang->line('view_bills')); ?></li>
		<li><?php echo anchor('bills/create', $this->lang->line('create_bill')); ?></li>
		<li><?php echo anchor('bills/index/is_quote/1', $this->lang->line('view_quotes')); ?></li>
		<li><?php echo anchor('bill_items', $this->lang->line('bill_items')); ?></li>
		<li><?php echo anchor('bill_search', $this->lang->line('bill_search')); ?></li>
		<li class="last"><?php echo anchor('templates/index/type/bills', $this->lang->line('bill_templates')); ?></li>
	</ul>

</div>