<?php
/**
 * @var $calls
 * @var $can_remove
 */
?>

<?php
if(!empty($calls)) {
    foreach ($calls as $call){
        $this->load->view(
            'form/reports/calls/calls_table_row',
            array(
                'call' => $call,
                'can_remove' => $can_remove,
            )
        );
    }
}
?>

<script>
    $(function() {
        // popover
        $('[data-toggle="popover"]').popover();
    });
</script>