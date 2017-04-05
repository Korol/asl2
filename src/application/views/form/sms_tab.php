<?php
/**
 * @var $employees
 */

if(!empty($employees)):
?>
<h3 style="margin-left: 30px; margin-bottom: 20px;">Доступ по СМС</h3>
<div style="width: 500px; margin-left: 30px;">
    <p class="help-block"><em>Заблокированные сотрудники выделены желтым цветом.</em></p>
    <p class="help-block"><em>Изменения сохраняются автоматически, без перезагрузки страницы.</em></p>
<table class="table table-bordered table-striped">
    <thead>
    <th>ФИО сотрудника</th>
    <th>Доступ по СМС</th>
    </thead>
    <tbody>
    <?php foreach($employees as $user): ?>
    <tr class="<?= ($user['IsBlocked'] > 0) ? 'warning' : ''; ?>">
        <td>
            <?= $user['SName'] . ' ' . $user['FName'] . ' ' . $user['MName']; ?>
        </td>
        <td>
            <input type="checkbox" onchange="sms_change(<?=$user['ID'];?>);" id="ch_<?=$user['ID'];?>" <?=(!empty($user['sms'])) ? 'checked="checked"' : ''; ?>/>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<script>
    function sms_change(u_id){
        var u_val = (document.getElementById('ch_'+u_id).checked) ? 1 : 0;
        $.post(
            '/sms_access/sms_change',
            { user_id : u_id, user_value : u_val },
            function(data){
                console.log(data);
            },
            'text'
        );
    }
</script>
<?php endif; ?>