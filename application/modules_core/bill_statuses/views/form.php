<?php $this->load->view('dashboard/header'); ?>

<div class="grid_7" id="content_wrapper">

    <div class="section_wrapper">

        <h3 class="title_black"><?php echo $this->lang->line('bill_status_form'); ?></h3>

        <?php $this->load->view('dashboard/system_messages'); ?>

        <div class="content toggle">

            <form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

                <dl>
                    <dt><label><?php echo $this->lang->line('bill_status'); ?>: </label></dt>
                    <dd><input type="text" name="bill_status" id="bill_status" value="<?php echo $this->mdl_bill_statuses->form_value('bill_status'); ?>" /></dd>
                </dl>
                <dl>
                    <dt><label><?php echo $this->lang->line('bill_status_type'); ?>: </label></dt>
                    <dd>
                        <select name="bill_status_type">
                        <?php foreach ($this->mdl_bill_statuses->status_types as $key=>$value) { ?>
                            <option value="<?php echo $key; ?>" <?php if ($this->mdl_bill_statuses->form_value('bill_status_type') == $key) { ?>selected="selected"<?php } ?>><?php echo $value; ?></option>
                        <?php } ?>
                        </select>
                    </dd>
                </dl>

                <div style="clear: both;">&nbsp;</div>

                <input type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" />
                <input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />

            </form>

        </div>

    </div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>'bill_statuses/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>