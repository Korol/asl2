<?php
/**
 * @var $CustomerID
 * @var $isEditReservationContactPane
 */
?>
<style>
    .site-name {
        color: #2067b0;
        font-weight: 700;
    }
</style>
<script src="/public/tablesorter/jquery.tablesorter.min.js"></script>
<link rel="stylesheet" href="/public/tablesorter/blue/style.css">
<?php /* шаблон таблицы */ ?>
<script id="contactsTableTmpl" type="text/x-jquery-tmpl">
    {{if records.length > 0}}
    <table class="tablesorter" id="contactsTable">
        <thead>
            <tr>
                <th class="sortable">Дата</th>
                <th class="sortable">Сайт</th>
                <th class="sortable">Переводчик</th>
                <th class="sortable">Мужчина</th>
                <th>Описание</th>
                <?php if($isEditReservationContactPane): ?>
                <th></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            {{tmpl(records) '#contactsRowTmpl'}}
        </tbody>
    </table>
    {{else}}
        <h5 class="text-center">Нет данных для отображения</h5>
    {{/if}}
</script>
<?php /* шаблон строки в таблице */ ?>
<script id="contactsRowTmpl" type="text/x-jquery-tmpl">
    <tr id="contactsTableRow_${ID}">
        <td>${toClientDate(Date)}</td>
        <td><span class="site-name">${SiteName}</span></td>
        <td>${TSName}<br>${TFName}</td>
        <td>${Men}</td>
        <td>
            ${Description}
        </td>
        <?php if($isEditReservationContactPane): ?>
        <td style="padding: 10px">
            <a href="<?= base_url('services/contact') ?>/${ID}/edit" data-toggle="modal" data-target="#remoteDialog" class="btn" role="button" title="Редактировать">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            </a>
        </td>
        <?php endif; ?>
    </tr>
</script>
<div class="row" style="margin-bottom: 50px;">
    <div class="col-md-12">
        <div class="service-block" style="padding-top: 0;">
            <div class="service-block-info-table" id="contactsTabInfo"></div>
        </div>
        <?php if($isEditReservationContactPane): ?>
        <div class="service-block-settings-btns">
            <a href="<?=base_url('services/contact/add/' . $CustomerID)?>" data-toggle="modal" data-target="#remoteDialog"
               class="" role="button" title="Добавить поле">
                <button class="btn assol-btn add right">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    ДОБАВИТЬ ПОЛЕ
                </button>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
<script>
    // клик по табу Запрос контактов
    $(document).on('click', 'a[aria-controls=ReservationContactPane]', function(){
        getServiceContactsList();
    });

    function getServiceContactsList() {
        $.post(
            '/Services_Contact/customer',
            {
                id: <?= $CustomerID; ?>
            },
            function (data) {
                if(data.status){
                    $('#contactsTabInfo').html('');
                    $(function () {
                        $('#contactsTableTmpl').tmpl(data).appendTo('#contactsTabInfo');
                    });
                    $("#contactsTable").tablesorter({
                        selectorHeaders: 'thead th.sortable' // <-- здесь указываем класс, который определяет те столбцы, по которым будет работать сортировка
                    });
                }
                else{
                    $('#contactsTabInfo').html('<h5>Нет данных для отображения. Status 0</h5>');
                }
            },
            'json'
        );
    }
</script>