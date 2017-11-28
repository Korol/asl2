<?php
/**
 * @var $call
 * @var $can_remove
 */
?>

<?php if(!empty($call)): ?>
    <tr id="ctr_<?= $call['ID']; ?>">
        <td><?= date('d.m.y', strtotime($call['CreatedDate'])); ?></td>
        <td><?= $call['CustomerName']; ?></td>
        <td><?= $call['EmployeeName']; ?></td>
        <td>
            <div data-toggle="popover"
                 data-trigger="hover"
                 data-content="<?= $call['Comment']; ?>"
                 data-placement="top"
                 class="call-comment-text"
            ><?= $call['Comment']; ?></div>
        </td>
        <?php if(!empty($can_remove)): ?>
            <td>
                <button class="btn btn-default" onclick="removeCall(<?= $call['ID']; ?>);">
                    <span class="glyphicon glyphicon-trash"></span>
                </button>
            </td>
        <?php endif; ?>
    </tr>

    <?php if(!empty($load_single)): ?>
    <script>
        $(function() {
            // popover
            $('[data-toggle="popover"]').popover();
        });
    </script>
    <?php endif; ?>
<?php endif; ?>