<?php $this->load->view('dashboard/header'); ?>

<div class="grid_7" id="content_wrapper">

    <div class="section_wrapper">

        <h3 class="title_black"><?php echo $this->lang->line('suppliers'); ?><?php $this->load->view('dashboard/btn_add', array('btn_name'=>'btn_add_supplier', 'btn_value'=>$this->lang->line('add_supplier'))); ?></h3>

        <?php $this->load->view('dashboard/system_messages'); ?>

        <div class="content toggle no_padding">

            <table>
                <tr>
                    <th scope="col" class="first">
                        <?php if ($this->uri->segment(4) == 'supplier_id_desc') {
                            echo anchor('suppliers/index/order_by/supplier_id_asc', $this->lang->line('id'));
                        } else {
                            echo anchor('suppliers/index/order_by/supplier_id_desc', $this->lang->line('id'));
                        } ?>
                    </th>
                    <th scope="col" >
                        <?php if ($this->uri->segment(4) == 'supplier_name_desc') {
                            echo anchor('suppliers/index/order_by/supplier_name_asc', $this->lang->line('name'));
                        } else {
                            echo anchor('suppliers/index/order_by/supplier_name_desc', $this->lang->line('name'));
                        } ?>
                    </th>
                    <th scope="col" >
                        <?php if ($this->uri->segment(4) == 'balance_desc') {
                            echo anchor('suppliers/index/order_by/balance_asc', $this->lang->line('balance'));
                        } else {
                            echo anchor('suppliers/index/order_by/balance_desc', $this->lang->line('balance'));
                        } ?>
                    </th>
                    <th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
                </tr>
                <?php foreach ($suppliers as $supplier) { ?>
                <tr class="hoverall">
                    <td class="first"><?php echo $supplier->supplier_id; ?></td>
                    <td nowrap="nowrap"><?php echo $supplier->supplier_name; ?></td>
                    <td><?php echo display_currency($supplier->supplier_total_balance); ?></td>
                    <td class="last">
                        <a href="<?php echo site_url('suppliers/details/supplier_id/' . $supplier->supplier_id); ?>" title="<?php echo $this->lang->line('view'); ?>">
                        <?php echo icon('zoom'); ?>
                        </a>
                        <a href="<?php echo site_url('suppliers/form/supplier_id/' . $supplier->supplier_id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
                        <?php echo icon('edit'); ?>
                        </a>
                        <a href="<?php echo site_url('suppliers/delete/supplier_id/' . $supplier->supplier_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('supplier_delete_warning'); ?>')) return false">
                        <?php echo icon('delete'); ?>
                        </a>
                        <?php if  ($supplier->supplier_active) { ?>
                        <a href="<?php echo site_url('bills/create/supplier_id/' . $supplier->supplier_id); ?>" title="<?php echo $this->lang->line('create_bill'); ?>">
                        <?php echo icon('bill'); ?>
                        </a>
                        <a href="<?php echo site_url('bills/create/quote/supplier_id/' . $supplier->supplier_id); ?>" title="<?php echo $this->lang->line('create_quote'); ?>">
                        <?php echo icon('quote'); ?>
                        </a>
                        <?php } ?>
                    </td>
                </tr>
                    <?php } ?>
            </table>

            <?php if ($this->mdl_suppliers->page_links) { ?>
            <div id="pagination">
            <?php echo $this->mdl_suppliers->page_links; ?>
            </div>
            <?php } ?>

        </div>

    </div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>'suppliers/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>