<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo application_title(); ?></title>
		<link href="<?php echo base_url(); ?>assets/style/css/styles.css" rel="stylesheet" type="text/css" media="screen" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/style/css/ie6.css" /><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/style/css/ie7.css" /><![endif]-->
		<link type="text/css" href="<?php echo base_url(); ?>assets/jquery/ui-themes/myclientbase1/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-ui-1.8.4.custom.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/jquery/jquery.maskedinput-1.2.2.min.js" type="text/javascript"></script>
	</head>
	<body>

		<div id="header_wrapper">

			<div class="container_10" id="header_content">

				<h1><?php echo application_title(); ?></h1>

			</div>

		</div>

		<div id="navigation_wrapper">

			<ul class="container_10" id="navigation">

				<li <?php if (!$this->uri->segment(2) or $this->uri->segment(2) == 'index') { ?>class="selected"<?php } ?>><?php echo anchor('client_center', $this->lang->line('client_center')); ?></li>
				<li <?php if ($this->uri->segment(2) == 'invoices') { ?>class="selected"<?php } ?>><?php echo anchor('client_center/invoices', $this->lang->line('invoices')); ?></li>
				<li <?php if ($this->uri->segment(2) == 'account') { ?>class="selected"<?php } ?>><?php echo anchor('client_center/account', $this->lang->line('my_account')); ?></li>
				<li><?php echo anchor('sessions/logout', $this->lang->line('log_out')); ?></li>
				
			</ul>

		</div>

		<div class="container_10" id="center_wrapper">