<script type="text/javascript">

    $(function() {

        $('#supplier_id').change(function() {

            supplier_id = $('#supplier_id').val();

            d_type = '<?php echo $this->uri->segment(3); ?>';

            if (!d_type) {

                d_type = 'bill';

            }

            $.get('<?php echo site_url('bills/jquery_supplier_bill_group'); ?>/' + supplier_id + '/' + d_type, {}, function(bill_group_id) {
                $('#bill_group_id').val(bill_group_id);
            });

        });

    });

</script>