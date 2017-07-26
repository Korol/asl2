<link rel="stylesheet" href="/public/stickytable/jquery.stickytable.min.css">
<script src="/public/stickytable/jquery.stickytable.min.js?v=1"></script>
<script src="/public/tablesorter/jquery.tablesorter.min.js"></script>
<link rel="stylesheet" href="/public/tablesorter/blue/style.css">
<style>
    .sticky-table table td.sticky-cell, .sticky-table table th.sticky-cell,
    .sticky-table table tr.sticky-row td, .sticky-table table tr.sticky-row th {
        outline: #ddd solid 1px !important;
    }
    .site-table .table > thead > tr > th, .table > tbody > tr > td{
        border: 1px solid #ddd;
    }
    td.sticky-cell{
        font-weight: bold !important;
        background-color: #ecf0f3 !important;
        padding-right: 6px !important;
        padding-left: 6px !important;
    }
    tr.sticky-row > th{
        background-color: #ecf0f3 !important;
    }
    .thVal{
        max-width: 100%;
    }
    .editable-table>thead>tr>th{
        border-bottom-width: 1px !important;
    }
    .th-centered{
        text-align: center !important;
    }
    .th-vcentered{
        vertical-align: middle !important;
    }
    .th-sitename{
        color: #2067b0;
    }
    .td-bold{
        font-weight: bold !important;
    }
    .th-result-vertical{
        min-width: 100px;
    }
    .td-light-grey{
        color: lightgrey !important;
    }
    .td-result{
        background-color: #ecf0f3;
    }
</style>
<div style="margin: 10px 0;"><h5><?=(!empty($title)) ? $title : ''; ?></h5></div>
<div class="row">
    <div class="col-md-12">
        <div class="sticky-table sticky-headers sticky-ltr-cells">
            <table class="table table-bordered table-striped editable-table tablesorter" id="dailyEmployeesReport">
                <thead>
                <tr class="sticky-row">
                    <th rowspan="2" class="sticky-cell th-centered th-vcentered" nowrap="nowrap">ФИО</th>
                    <?php
                    $th_count = 0;
                    $th_stop = 0;
                    ?>
                    <?php foreach($sites as $th_site): ?>
                        <?php
                        $td_sum[$th_site['ID']]['emails'] = $td_sum[$th_site['ID']]['chat'] = 0;
                        $th_count++;
                        ?>
                        <th colspan="2" class="th-centered th-sitename" nowrap="nowrap"><?= $th_site['Name']; ?></th>
                    <?php endforeach; ?>
                    <?php
                    $th_count++;
                    $th_stop = $th_count;
                    ?>
                    <th rowspan="2" class="th-centered th-vcentered th-result-vertical sortable">Итого</th>
                </tr>
                <tr class="sticky-row">
                    <?php foreach($sites as $th_site): ?>
                        <th nowrap="nowrap">письма</th>
                        <th nowrap="nowrap">чат</th>
                        <?php
                        $th_count += 2;
                        ?>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach($translators as $cs_item): ?>
                    <?php
//                    if(empty($cs_item['SName'])) continue; // exclude Test employee
                    $tr_sum = 0;
                    $sname_ex = explode(' ', trim($cs_item['SName']));
                    $sname = $sname_ex[0];
                    $fname = mb_substr($cs_item['FName'], 0, 1, 'UTF-8');
                    $mname = mb_substr($cs_item['MName'], 0, 1, 'UTF-8');
                    ?>
                    <tr>
                        <td class="sticky-cell" nowrap="nowrap">
                            <a href="/reports#daily_<?= $cs_item['ID']; ?>" target="_blank">
                                <?= $sname . ' ' . $fname . '.' . $mname . '.'; ?>
                            </a>
                        </td>
                        <?php foreach($sites as $tb_site): ?>
                            <?php
                            $tb_text_emails = $tb_text_chat = 0;
                            $td_class = 'td-light-grey';
                            if(!empty($daily_reports[$cs_item['ID']][$tb_site['ID']])){
                                $tb_text_emails = (!empty($daily_reports[$cs_item['ID']][$tb_site['ID']]['emails']))
                                    ? $daily_reports[$cs_item['ID']][$tb_site['ID']]['emails']
                                    : 0;
                                $tb_text_chat = (!empty($daily_reports[$cs_item['ID']][$tb_site['ID']]['chat']))
                                    ? $daily_reports[$cs_item['ID']][$tb_site['ID']]['chat']
                                    : 0;
                                $td_class = 'td-bold';
                                $tr_sum += ($tb_text_chat + $tb_text_emails);
                                $td_sum[$tb_site['ID']]['emails'] += $tb_text_emails;
                                $td_sum[$tb_site['ID']]['chat'] += $tb_text_chat;
                            }
                            ?>
                            <td class="<?= $td_class; ?>" nowrap="nowrap"><?= $tb_text_emails; ?></td>
                            <td class="<?= $td_class; ?>" nowrap="nowrap"><?= $tb_text_chat; ?></td>
                        <?php endforeach; // ($sites as $td_site) ?>
                        <td class="td-bold td-result"><?= $tr_sum; ?></td>
                    </tr>
                <?php endforeach; // ($translators as $cs_item) ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="sticky-cell td-bold" nowrap="nowrap">Итого: </td>
                        <?php
                        $td_tr_sum = 0;
                        foreach($sites as $ti_site):
                            $td_tr_sum += ($td_sum[$ti_site['ID']]['emails'] + $td_sum[$ti_site['ID']]['chat']);
                            ?>
                            <td class="td-bold td-result"><?= $td_sum[$ti_site['ID']]['emails']; ?></td>
                            <td class="td-bold td-result"><?= $td_sum[$ti_site['ID']]['chat']; ?></td>
                        <?php endforeach; // ($sites as $ti_site) ?>
                        <td class="td-bold td-result"><?= $td_tr_sum; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php
// формируем псевдо-JS-объект для отключения сортировки на столбцах
$headers = '{ ';
$headers_arr = array();
for($i = 0; $i <= $th_count; $i++){
//    if(($i > 0) && ($i !== $th_stop)) // этот вариант разрешает сортировку по колонкам ФИО и Итого
    if($i !== $th_stop) // этот вариант разрешает сортировку только по колонке Итого
        $headers_arr[] = $i . ': { sorter: false}';
}
$headers .= implode(', ', $headers_arr) . ' }';
// P.S.: нашел норм вариант без долботни с JS-объектом:
// selectorHeaders: 'thead th.sortable'
// и просто для нужных th указываем класс sortable
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#dailyEmployeesReport").tablesorter({
            selectorHeaders: 'thead th.sortable'
        });
    });
</script>