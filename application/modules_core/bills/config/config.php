<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config = array(
	'module_name'	=>	$this->lang->line('bills'),
	'module_path'	=>	'bills',
	'module_order'	=>	4,
	'module_config'	=>	array(
		'settings_view'	=>	'bills/bill_settings/display',
		'settings_save'	=>	'bills/bill_settings/save'
	)
);

?>