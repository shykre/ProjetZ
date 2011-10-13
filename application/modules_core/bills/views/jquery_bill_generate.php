<script type="text/javascript">

    $(function() {

        $('#output_dialog').dialog({
            modal: true,
            draggable: false,
            resizable: false,
            autoOpen: false,
            width: 400,
            title: '<?php echo $this->lang->line('generate_bill'); ?>',
            buttons: {
                '<?php echo $this->lang->line('generate'); ?>': function() {
                    $(this).dialog('close');
                    generate_bill();
                },
                '<?php echo $this->lang->line('cancel'); ?>': function() {
                    $(this).dialog('close');
                }
            }
        });

        $('.output_link').click(function() {

            id_info = $(this).attr('id').split(':');

            bill_id = id_info[0];

            supplier_id = id_info[1];

            bill_is_quote = id_info[2];

            $.get('<?php echo site_url('bills/jquery_supplier_bill_template'); ?>/' + supplier_id + '/' + bill_is_quote, {}, function(bill_template) {
                $('#bill_template').val(bill_template);
            });

            $('#output_dialog').dialog('open');

        });

        function generate_bill() {

            var bill_output_type = $('#bill_output_type').val();

            var bill_template = $('#bill_template').val();

            if (bill_output_type != 'email') {

                download_url = '<?php echo site_url('bills/generate_'); ?>' + bill_output_type + '/bill_id/' + bill_id + '/bill_template/' + bill_template;

                window.open(download_url);

            }

            else {

                var email_url = '<?php echo site_url('mailer/bill_mailer/form/bill_id'); ?>' + '/' + bill_id + '/bill_template/' + bill_template;

                window.location = email_url;

            }

        }

    });

</script>

<div id="output_dialog">
    <table style="width: 100%;">
        <tr>
            <td><?php echo $this->lang->line('output_type'); ?>: </td>
            <td>
                <select name="bill_output_type" id="bill_output_type">
                    <option value="pdf"><?php echo $this->lang->line('pdf'); ?></option>
                    <option value="html"><?php echo $this->lang->line('html'); ?></option>
                    <option value="email"><?php echo $this->lang->line('email'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo $this->lang->line('bill_template'); ?>: </td>
            <td>
                <select name="bill_template" id="bill_template">
                    <?php foreach ($templates as $template) { ?>
                    <?php if (uri_assoc('is_quote') or isset($bill) and $bill->bill_is_quote) { ?>
                    <option <?php if ($template == $default_quote_template) { ?>selected="selected"<?php } ?>><?php echo $template; ?></option>
                    <?php } else { ?>
                    <option <?php if ($template == $default_bill_template) { ?>selected="selected"<?php } ?>><?php echo $template; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
            </td>
        </tr>
    </table>
</div>