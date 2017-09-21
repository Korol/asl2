<script>
    var Sites = {};
    <?php foreach($sites as $site): ?>
    Sites["<?= $site['ID'] ?>"] = "<?= empty($site['Name']) ? $site['Domen'] : $site['Name'] ?>";
    <?php endforeach ?>
</script>

<style>
    .report-table {
        display: none;
    }
    .history-small-help {
        font-size: 11px;
        font-weight: normal;
        color: #5d5d5d;
        padding-top: 5px;
    }
</style>

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

<? if (IS_LOVE_STORY): ?>

<div class="reports-top-page">

    <script id="reportsTemplate" type="text/x-jquery-tmpl">
        {{if bread}}
        <ol class="breadcrumb assol-grey-panel">
            <li><a href="#" class="report-bread" level="0">Отчеты</a></li>
            {{tmpl($data.bread) "#reportsBreadTemplate"}}
        </ol>
        {{/if}}

        {{if data}}
            {{tmpl($data.data) "#reportsListTemplate"}}
        {{/if}}
    </script>

    <script id="reportsBreadTemplate" type="text/x-jquery-tmpl">
        {{if IsLast}}
            <li class="active">${Name}</li>
        {{else}}
            <li><a href="#" class="report-bread" level="${Level}">${Name}</a></li>
        {{/if}}
    </script>

    <script id="reportsListTemplate" type="text/x-jquery-tmpl">
        <div class="report-folder assol-grey-panel" level="${Level}" {{if ID}}id-employee="${ID}"{{/if}}>
            <a href="#" class="folder-img"><img src="<?= base_url('public/img') ?>/{{if IsDoc}}file2{{else}}folder2{{/if}}.png"></a>
            <a href="#" class="folder-name">${Name}</a>
        </div>
    </script>

    <div class="reports-folders-wrap" id="reports"></div>

</div>

    <!-- Ежедневный отчет -->
    <div id="ReportIndividualDaily" class="report-table">
        <div class="reports-title">Ежедневный отчет</div>
        <div class="panel assol-grey-panel">
            <div class="report-filter-wrap clear">

                <div class="date-filter-block">
                    <div class="form-group">
                        <label for="daily-month">Месяц</label>
                        <select class="assol-btn-style" id="daily-month">
                            <option value="0">Январь</option>
                            <option value="1">Февраль</option>
                            <option value="2">Март</option>
                            <option value="3">Апрель</option>
                            <option value="4">Май</option>
                            <option value="5">Июнь</option>
                            <option value="6">Июль</option>
                            <option value="7">Август</option>
                            <option value="8">Сентябрь</option>
                            <option value="9">Октябрь</option>
                            <option value="10">Ноябрь</option>
                            <option value="11">Декабрь</option>
                        </select>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="daily-year">Год</label>

                        <div class='input-group date' id='daily-year'>
                            <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        var years = $('#daily-year');
                        var months = $('#daily-month');

                        years.datetimepicker({
                            locale: 'ru',
                            format: 'YYYY',
                            viewMode: 'years',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function (e) {
                            $.ReportTranslate.ReloadReportMountMeta(e.date.year());
                        });

                        months.change(function () {
                            $.ReportTranslate.ReloadReportMountMeta();
                        });

                        months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                    });
                </script>

            </div>
        </div>

        <div class="love-report-scroll-block-wrap">
            <div class="love-report-scroll-block">
                <div class="lrsb-left">
                    <div class="love-report-table-wrap">

                        <style>
                            .is-edit input {
                                width: 90%;
                                margin-left: 5%;
                            }
                        </style>

                        <script id="reportIndividualDailyTemplate" type="text/x-jquery-tmpl">
                            <thead>
                                <tr>
                                    <th></th>

                                    {{each work_sites}}
                                        <th colspan="2">
                                            <div class="site-name">${Sites[$value.SiteID]}</div>
                                        </th>
                                    {{/each}}
                                </tr>
                                <tr>
                                    <th><div></div></th>

                                    {{each work_sites}}
                                        <th><div>письма</div></th>
                                        <th><div>чат</div></th>
                                    {{/each}}
                                </tr>
                                <tr>
                                    <th><div>План месяца</div></th>

                                    {{each work_sites}}
                                        <th id="plan_mail_${ID}" id-work-site="${ID}" class="is-edit mail"><div>0</div></th>
                                        <th id="plan_chat_${ID}" id-work-site="${ID}" class="is-edit chat"><div>0</div></th>
                                    {{/each}}
                                </tr>
                                <tr>
                                    <th><div>Выполнено</div></th>

                                    {{each work_sites}}
                                        <th><div>0</div></th>
                                        <th><div>0</div></th>
                                    {{/each}}
                                </tr>
                            </thead>
                            <tbody>
                                {{each days}}
                                    <tr day="${day}">
                                        <td><div>${day}</div></td>

                                        {{each work_sites}}
                                            <td id-work-site="${ID}" date="${date}" class="is-edit mail"><div>0</div></td>
                                            <td id-work-site="${ID}" date="${date}" class="is-edit chat"><div>0</div></td>
                                        {{/each}}
                                    </tr>
                                {{/each}}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><div>Итого</div></td>

                                    {{each work_sites}}
                                        <td id="foot_mail_total_${ID}">0</td>
                                        <td id="foot_chat_total_${ID}">0</td>
                                    {{/each}}
                                </tr>
                                <tr>
                                    <td><div>Остаток</div></td>

                                    {{each work_sites}}
                                        <td id="foot_mail_bal_${ID}">0</td>
                                        <td id="foot_chat_bal_${ID}">0</td>
                                    {{/each}}
                                </tr>
                            </tfoot>
                        </script>

                        <table id="ReportIndividualDaily_data" class="love-report-table love-report-day-table"></table>

                    </div>
                </div>
                <div class="lrsb-right">
                    <div class="love-report-table-wrap">

                        <script id="reportIndividualDaily_fixedWrapBody_Template" type="text/x-jquery-tmpl">
                            <tr id="rid_total_${day}">
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                        </script>

                        <table id="ReportIndividualDaily_fixedWrapBody" class="love-report-table love-report-day-total-table">
                            <thead>
                                <tr>
                                    <th>

                                    </th>
                                    <th colspan="2">
                                        <div></div>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <div>письма</div>
                                    </th>
                                    <th>
                                        <div>чат</div>
                                    </th>
                                    <th>
                                        <div>всего</div>
                                    </th>
                                </tr>
                                <tr id="rid_plan_total">
                                    <th>
                                        <div>0</div>
                                    </th>
                                    <th>
                                        <div>0</div>
                                    </th>
                                    <th>
                                        <div>0</div>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <div>0</div>
                                    </th>
                                    <th>
                                        <div>0</div>
                                    </th>
                                    <th>
                                        <div>0</div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody></tbody>

                            <tfoot>
                                <tr>
                                    <td><div>0</div></td>
                                    <td><div>0</div></td>
                                    <td><div>0</div></td>
                                </tr>
                                <tr>
                                    <td><div>0</div></td>
                                    <td><div>0</div></td>
                                    <td><div>0</div></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Отчет по зарплате -->
    <div id="ReportIndividualSalary" class="salary-report report-table" style="margin-bottom: 50px">
        <div class="reports-title">Отчет по зарплате</div>

        <div class="panel assol-grey-panel">
            <div class="report-filter-wrap clear">

                <div class="date-filter-block">
                    <div class="form-group">
                        <label for="salary-month">Месяц</label>
                        <select class="assol-btn-style" id="salary-month">
                            <option value="0">Январь</option>
                            <option value="1">Февраль</option>
                            <option value="2">Март</option>
                            <option value="3">Апрель</option>
                            <option value="4">Май</option>
                            <option value="5">Июнь</option>
                            <option value="6">Июль</option>
                            <option value="7">Август</option>
                            <option value="8">Сентябрь</option>
                            <option value="9">Октябрь</option>
                            <option value="10">Ноябрь</option>
                            <option value="11">Декабрь</option>
                        </select>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="salary-year">Год</label>

                        <div class='input-group date' id='salary-year'>
                            <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        var years = $('#salary-year');
                        var months = $('#salary-month');

                        var current = moment().subtract(1, 'months');

                        years.datetimepicker({
                            locale: 'ru',
                            format: 'YYYY',
                            viewMode: 'years',
                            defaultDate: current,
                            showTodayButton: true
                        }).on('dp.change', function () {
                            $.ReportTranslate.ReloadReportSalary();
                        });

                        months.change(function () {
                            $.ReportTranslate.ReloadReportSalary();
                        });

                        months.find("[value='" + current.month() + "']").attr("selected", "selected");
                    });
                </script>

            </div>
        </div>

        <style>
            .salary-report-table td[type] div:empty {
                height: 100%;
            }

            .salary-report-table td[type] {
                width: 80px;
            }

            .salary-report-table td[type] input {
                width: 90%;
                max-width: 80px;
            }

            .salary-report .salary-report-table table .salary-report-sum td.salary-total {
                background: #d7d7d7;
                border-color: #d7d7d7;
                text-align: center;
                font-weight: bold;
            }

            .site-name {
                color: #2067b0;
                font-weight: 700;
            }
        </style>

        <script id="reportIndividualSalaryTemplate" type="text/x-jquery-tmpl">
            <tr id-employee-site="${ID}">
                <td><span class="site-name">${Sites[SiteID]}</span></td>
                <td type="emailCount" class="decimal"><div>${emailCount}</div></td>
                <td type="emailAmount" class="decimal"><div>${emailAmount}</div></td>
                <td type="chatCount" class="decimal"><div>${chatCount}</div></td>
                <td type="chatAmount" class="decimal"><div>${chatAmount}</div></td>
                <td type="deliveryCount" class="decimal"><div>${deliveryCount}</div></td>
                <td type="deliveryAmount" class="decimal"><div>${deliveryAmount}</div></td>
                {{if IsDealer > 0}}
                    <td type="dealerCount" class="decimal"><div>${dealerCount}</div></td>
                    <td type="dealerAmount" class="decimal"><div>${dealerAmount}</div></td>
                {{else}}
                    <td>-</td>
                    <td>-</td>
                {{/if}}
                <td></td>
            </tr>
        </script>

        <div class="salary-report-table reports-table-wrap">
            <table id="ReportIndividualSalary_data">
                <thead>
                <tr>
                    <th rowspan="2">Сайт</th>
                    <th colspan="2">Письма</th>
                    <th colspan="2">Чат</th>
                    <th colspan="2">Доставка</th>
                    <th colspan="2">Дилерские</th>
                    <th></th>
                </tr>
                <tr>
                    <th>кол-во</th>
                    <th>сумма</th>
                    <th>кол-во</th>
                    <th>сумма</th>
                    <th>кол-во</th>
                    <th>сумма</th>
                    <th>кол-во</th>
                    <th>сумма</th>
                    <th>общее</th>
                </tr>
                </thead>

                <tbody></tbody>

                <tfoot class="salary-report-sum">
                <tr>
                    <td colspan="8"></td>
                    <td class="salary-total"><strong>итого:</strong></td>
                    <td class="salary-total"></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-group clear save-edit-wrap">
<!--            <div class="right">* отправка отчета доступна с 01 по 10 число каждого месяца</div>-->

            <?php if ($role['isTranslate']): ?>

                <br>
                <button id="submit-report-salary" class="btn assol-btn right add" role="button">
                    ОТПРАВИТЬ
                </button>

                <script>
//                    $(document).ready(function () {
//                        var day = moment().date();
//
//                        if (day >= 1 && day <= 10)
//                            $('#submit-report-salary').removeClass('disabled').addClass('add');
//                    });
                </script>

            <?php endif ?>
        </div>
    </div>

    <!-- Подтвержденная зарплата   -->
    <?php if($role['isTranslate']): ?>

        <div id="ReportApprovedSalary" class="approved-salary-report report-table" style="margin-bottom: 50px">
            <div class="reports-title">Подтвержденная зарплата</div>


            <div class="panel assol-grey-panel">
                <div class="report-filter-wrap clear">

                    <div class="date-filter-block">
                        <div class="form-group">
                            <label for="approved-salary-month">Месяц</label>
                            <select class="assol-btn-style" id="approved-salary-month">
                                <option value="0">Январь</option>
                                <option value="1">Февраль</option>
                                <option value="2">Март</option>
                                <option value="3">Апрель</option>
                                <option value="4">Май</option>
                                <option value="5">Июнь</option>
                                <option value="6">Июль</option>
                                <option value="7">Август</option>
                                <option value="8">Сентябрь</option>
                                <option value="9">Октябрь</option>
                                <option value="10">Ноябрь</option>
                                <option value="11">Декабрь</option>
                            </select>
                        </div>
                    </div>

                    <div class="date-filter-block">
                        <div class="form-group calendar-block">
                            <label for="approved-salary-year">Год</label>

                            <div class='input-group date' id='approved-salary-year'>
                                <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(function() {
                            var years = $('#approved-salary-year');
                            var months = $('#approved-salary-month');

                            years.datetimepicker({
                                locale: 'ru',
                                format: 'YYYY',
                                viewMode: 'years',
                                defaultDate: 'now',
                                showTodayButton: true
                            }).on('dp.change', function (e) {
                                $.ReportTranslate.ReloadReportApprovedSalary(e.date.year());
                            });

                            months.change(function () {
                                $.ReportTranslate.ReloadReportApprovedSalary();
                            });

                            months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                        });
                    </script>
                </div>
            </div>

            <script id="reportApprovedSalaryTemplate" type="text/x-jquery-tmpl">
                <tr paid="${paid}">
                    <td><span class="site-name">${Sites[SiteID]}</span></td>
                    <td>${value}</td>
                    <td>
                        {{if paid == 1}}
                        <div class="approved-salary-status on">
                            <div class="round-check on"></div>
                            <span>выдано</span>
                        </div>
                        {{/if}}
                    </td>
                </tr>
            </script>

            <div class="approved-salary-report-table reports-table-wrap">
                <table id="ReportApprovedSalary_data">
                    <thead>
                    <tr>
                        <th>Сайт</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    <?php endif ?>

    <!-- Таблица распределения клиентов   -->
    <div id="ReportAllocation" class="overall-allocation-report report-table">
        <div class="reports-title">Таблица распределения</div>

        <script id="reportAllocation_Template" type="text/x-jquery-tmpl">
            <tr>
                <td>${Sites[SiteID]}</td>
                <td>${customers}</td>
            </tr>
        </script>

        <div class="overall-allocation-report-table reports-table-wrap">
            <table id="ReportAllocation_data">
                <thead>
                    <tr>
                        <th>Сайт</th>
                        <th>Клиентки</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <? if($role['isDirector']): ?>

        <!-- Общая таблица по сотрудникам   -->
        <div id="ReportGeneralOfCustomers" class="report-table">
            <div class="reports-title">Общая таблица по сотрудникам</div>
            <div class="panel assol-grey-panel">
                <div class="report-filter-wrap clear">
                    <div>
                        <div class="form-group">
                            <label for="generalSite">Сайт</label>
                            <div class="btn-group assol-select-dropdown" id="generalSite">
                                <div class="label-placement-wrap">
                                    <button class="btn" data-label-placement="">Выбрать</button>
                                </div>
                                <button data-toggle="dropdown" class="btn dropdown-toggle">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php foreach($sites as $item): ?>
                                        <li>
                                            <input type="radio" id="GeneralSite_<?=$item['ID']?>" name="Site" value="<?=$item['ID']?>">
                                            <label for="GeneralSite_<?=$item['ID']?>"><?= empty($item['Name']) ? $item['Domen'] : $item['Name'] ?></label>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="date-filter-block">
                        <div class="form-group">
                            <label for="general-month">Месяц</label>
                            <select class="assol-btn-style" id="general-month">
                                <option value="0">Январь</option>
                                <option value="1">Февраль</option>
                                <option value="2">Март</option>
                                <option value="3">Апрель</option>
                                <option value="4">Май</option>
                                <option value="5">Июнь</option>
                                <option value="6">Июль</option>
                                <option value="7">Август</option>
                                <option value="8">Сентябрь</option>
                                <option value="9">Октябрь</option>
                                <option value="10">Ноябрь</option>
                                <option value="11">Декабрь</option>
                            </select>
                        </div>
                    </div>

                    <div class="date-filter-block">
                        <div class="form-group calendar-block">
                            <label for="general-year">Год</label>

                            <div class='input-group date' id='general-year'>
                                <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(function() {
                            var years = $('#general-year');
                            var months = $('#general-month');

                            years.datetimepicker({
                                locale: 'ru',
                                format: 'YYYY',
                                viewMode: 'years',
                                defaultDate: 'now',
                                showTodayButton: true
                            }).on('dp.change', function (e) {
                                $.ReportDirector.ReloadGeneralOfEmployeesMeta();
                            });

                            months.change(function () {
                                $.ReportDirector.ReloadGeneralOfEmployeesMeta();
                            });

                            months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                        });
                    </script>

                </div>
            </div>

            <div class="love-report-scroll-block-wrap">
                <div class="love-report-scroll-block">
                    <div class="lrsb-left">
                        <div class="love-report-table-wrap">

                            <script id="reportGeneralOfEmployees_data_Template" type="text/x-jquery-tmpl">
                                <thead>
                                    <tr>
                                        <th>
                                            Сотрудники
                                        </th>
                                        <th>
                                            План агенства
                                        </th>
                                        <th>
                                            План месяца
                                        </th>

                                        {{each days}}
                                            <th day="${day}">${day}</th>
                                        {{/each}}
                                    </tr>
                                </thead>
                                <tbody>
                                    {{each translators}}
                                        <tr id-employee="${ID}">
                                            <td>${SName} ${FName}</td>
                                            <td id="apm_${ID}" class="is-edit decimal"><div>0</div></td>
                                            <td class="plan">0</td>

                                            {{each days}}
                                                <td class="value" id="ger_${ID}_${gerDate(day)}" day="${day}">0</td>
                                            {{/each}}
                                        </tr>
                                    {{/each}}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Итог сумм переводчиков</td>
                                        <td id="rge_agent_plan_sum_total">0</td>
                                        <td id="rge_plan_sum_total">0</td>

                                        {{each days}}
                                            <td id="rge_sum_total_${day}">0</td>
                                        {{/each}}
                                    </tr>
                                </tfoot>
                            </script>

                            <script>
                                var years = $('#general-year');
                                var months = $('#general-month');

                                function getYear() {
                                    return years.data("DateTimePicker").date().year();
                                }

                                function gerDate(day) {
                                    return moment([getYear(), months.val(), day]).format('YYYY-MM-DD');
                                }
                            </script>

                            <table id="ReportGeneralOfEmployees_data" class="love-report-table love-report-general-staff-table"></table>
                        </div>
                    </div>
                    <div class="lrsb-right">
                        <div class="love-report-table-wrap">

                            <script id="reportGeneralOfEmployees_total_Template" type="text/x-jquery-tmpl">
                                <thead>
                                    <tr>
                                        <th>
                                            <div>Остаток по плану</div>
                                        </th>
                                        <th>
                                            <div>Итого</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{each translators}}
                                        <tr>
                                            <td id="rge_plan_${ID}">0</td>
                                            <td id="rge_total_${ID}">0</td>
                                        </tr>
                                    {{/each}}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td id="rge_plan_bal">0</td>
                                        <td id="rge_total_bal">0</td>
                                    </tr>
                                </tfoot>
                            </script>

                            <table id="ReportGeneralOfEmployees_total" class="love-report-table love-report-general-staff-total-table"></table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    <? endif ?>

<? else: ?>

<div class="reports-top-page">

    <script id="reportsTemplate" type="text/x-jquery-tmpl">
        {{if bread}}
        <ol class="breadcrumb assol-grey-panel">
            <li><a href="#" class="report-bread" level="0">Отчеты</a></li>
            {{tmpl($data.bread) "#reportsBreadTemplate"}}
        </ol>
        {{/if}}

        {{if data}}
            {{tmpl($data.data) "#reportsListTemplate"}}
        {{/if}}
    </script>

    <script id="reportsBreadTemplate" type="text/x-jquery-tmpl">
    {{if IsLast}}
        <li class="active">${Name}</li>
    {{else}}
        <li><a href="#" class="report-bread" level="${Level}">${Name}</a></li>
    {{/if}}
    </script>

    <script id="reportsListTemplate" type="text/x-jquery-tmpl">
        <div class="report-folder assol-grey-panel" level="${Level}" {{if ID}}id-employee="${ID}"{{/if}}>
            <a href="#" class="folder-img"><img src="<?= base_url('public/img') ?>/{{if IsDoc}}file2{{else}}folder2{{/if}}.png"></a>
            <a href="#" class="folder-name">${Name}</a>
        </div>
    </script>

    <div class="reports-folders-wrap" id="reports"></div>

</div>

<div class="reports-page" >

	<style>
        .mail, .chat {
            font-weight: bold;
        }

        .is-edit div {
            color: red;
        }

		.is-edit input {
            width: 90%;
            margin-left: 5%;
        }
	</style>

	<div id="ReportIndividualDaily" class="day-reports report-table" style="margin-bottom: 50px">
        <?php
//        $this->load->view('form/reports/individual/daily_report'); // не пригодилось – но как вариант
        ?>
		<div class="reports-title">
            Ежедневный отчет
            <span class="pull-right history-small-help">Для скролла: "Наведите на таблицу и используйте Shift + прокрутка колесом мышки"</span>
        </div>

		<div class="panel assol-grey-panel">
			<div class="report-filter-wrap clear">
<!--                New-->
                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="daily-from">С</label>

                        <div class='input-group date' id='daily-from'>
                            <input type='text' class="assol-btn-style" id="daily_from_input" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="daily-to">До</label>

                        <div class='input-group date' id='daily-to'>
                            <input type='text' class="assol-btn-style" id="daily_to_input" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <style>
                    .refresh-rotate{
                        -moz-transition: all 1s linear;
                        -webkit-transition: all 1s linear;
                        transition: all 1s linear;
                    }

                    .refresh-rotate.refresh-down{
                        -moz-transform:rotate(360deg);
                        -webkit-transform:rotate(360deg);
                        transform:rotate(360deg);
                    }
                </style>
                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="daily-to">&nbsp;</label>

                        <div class='input-group date'>
                            <a href="#" class="btn btn-default" id="daily_refresh">
                                <span class="glyphicon glyphicon-refresh refresh-rotate" aria-hidden="true"></span>
                                &nbsp;Обновить
                            </a>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    $(function() {
                        var daily_from = $('#daily-from');
                        var daily_to = $('#daily-to');

                        daily_from.datetimepicker({
                            locale: 'ru',
                            format: 'DD-MM-YYYY',
                            viewMode: 'days',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function (e) {
                            $.ReportTranslate.ReloadReportDailyMeta();
                        });

                        daily_to.datetimepicker({
                            locale: 'ru',
                            format: 'DD-MM-YYYY',
                            viewMode: 'days',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function (e) {
                            $.ReportTranslate.ReloadReportDailyMeta();
                        });

                        var refresh_counter = 0;
                        $('#daily_refresh').click(function (e) {
                            e.preventDefault();
                            refresh_counter += 360;
                            $(this).find('.glyphicon.glyphicon-refresh').css('transform', 'rotate(' + refresh_counter + 'deg)');
//                            $(this).find('.glyphicon.glyphicon-refresh').toggleClass('refresh-down');
                            $.ReportTranslate.ReloadReportDailyMeta();
                        });
                    });
                </script>
<!--                /New-->
				<?php /*div class="date-filter-block">
					<div class="form-group">
						<label for="daily-day">Число</label>
						<select class="assol-btn-style" id="daily-day">

						</select>
					</div>
				</div>

                <div class="date-filter-block">
                    <div class="form-group">
                        <label for="daily-month">Месяц</label>
                        <select class="assol-btn-style" id="daily-month">
                            <option value="0">Январь</option>
                            <option value="1">Февраль</option>
                            <option value="2">Март</option>
                            <option value="3">Апрель</option>
                            <option value="4">Май</option>
                            <option value="5">Июнь</option>
                            <option value="6">Июль</option>
                            <option value="7">Август</option>
                            <option value="8">Сентябрь</option>
                            <option value="9">Октябрь</option>
                            <option value="10">Ноябрь</option>
                            <option value="11">Декабрь</option>
                        </select>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="daily-year">Год</label>

                        <div class='input-group date' id='daily-year'>
                            <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        var years = $('#daily-year');
                        var months = $('#daily-month');
                        var days = $('#daily-day');

                        years.datetimepicker({
                            locale: 'ru',
                            format: 'YYYY',
                            viewMode: 'years',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function (e) {
                            $.ReportTranslate.ReloadReportDailyMeta(true);
                            $.ReportTranslate.RefreshReportDailyDate(e.date.year());
                        });

                        months.change(function () {
							$.ReportTranslate.ReloadReportDailyMeta(true);
                            $.ReportTranslate.RefreshReportDailyDate();
                        });

                        days.change(function () {
                            $.ReportTranslate.ReloadReportDailyMeta(true);
                            $.ReportTranslate.ReloadReportDailyData();
                        });

                        months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                    });
                </script*/?>

			</div>
		</div>

        <script id="reportIndividualDaily_fixedWrapBody_Template" type="text/x-jquery-tmpl">
            <tr id="rid_slide_total_${CustomerID}">
                <td>0.00</td>
            </tr>
        </script>

		<?php /*div class="day-main-fixed-wrap">
			<div class="day-main-fixed-table reports-table-wrap">
				<table id="ReportIndividualDaily_fixedWrapBody">
					<thead>
                        <tr><th>&nbsp;</th></tr>
                        <tr><th>&nbsp;</th></tr>
					</thead>
                    <tbody></tbody>
					<tfoot class="day-main-table-footer">
						<tr><td style="font-weight: bold">0.00</td></tr>
					</tfoot>
				</table>
			</div>
		</div*/?>
        <div style="margin: 10px 0;"><h5 class="day-report-my-title"></h5></div>
		<div class="day-reports-tables clear reports-table-wrap">

			<div class="day-reports-tables-in">

                <script id="reportIndividualDailyTemplate" type="text/x-jquery-tmpl">
                    <thead>
                        <tr class="sticky-row">
                            <th rowspan="2" class="sticky-cell th-centered">Клиенты</th>

                            {{each work_sites}}
                                <th colspan="2" class="th-centered"><span class="site-name">${Sites[$value.SiteID]}</span></th>
                            {{/each}}

                            <th rowspan="2" class="th-result-vertical th-centered sortable">Итого</th>
                        </tr>
                        <tr class="sticky-row">
                            {{each work_sites}}
                                <th><div>письма</div></th>
                                <th><div>чат</div></th>
                            {{/each}}
                        </tr>
                    </thead>

                    <tbody>
                        {{each customers}}
                            <tr>
                                <td class="sticky-cell" title="${SName} ${FName}"><div>${SName} ${FName}</div></td>

                                {{each work_sites}}
                                    <td class="decimal" id="mail_${CustomerID}_${ID}" title="${SName} ${FName}"><div>0</div></td>
                                    <td class="decimal" id="chat_${CustomerID}_${ID}" title="${SName} ${FName}"><div>0</div></td>
                                {{/each}}

                                <td class="td-bold td-result" style="padding-left: 10px;" id="rid_slide_total_${CustomerID}">0.00</td>
                            </tr>
                        {{/each}}
                    </tbody>

                    <tfoot class="day-main-table-footer">
                        <tr>
                            <td class="sticky-cell td-bold td-result text-right" style="padding-right: 10px;">Итого:</td>

                            {{each work_sites}}
                                <td class="td-bold td-result"><div id="foot_mail_${$value.ID}">0.00</div></td>
                                <td class="td-bold td-result"><div id="foot_chat_${$value.ID}">0.00</div></td>
                            {{/each}}

                            <td class="td-bold td-result" style="padding-left: 10px;" id="dr_foot_total">0.00</td>
                        </tr>
                    </tfoot>
                </script>

				<div class="day-main-table sticky-table sticky-headers sticky-ltr-cells">
                   	<table id="ReportIndividualDaily_data" class="tablesorter"></table>
				</div>

                <script id="reportIndividualDaily_total_Template" type="text/x-jquery-tmpl">
                    <tr id="rid_total_${CustomerID}">
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                    </tr>
                </script>

				<div class="day-total-table reports-table-wrap" style="display: none;">
					<table id="ReportIndividualDaily_total">
						<thead>
							<tr>
								<th colspan="3"></th>
							</tr>
							<tr>
                                <th>письма</th>
                                <th>чат</th>
                                <th>всего</th>
							</tr>
						</thead>

                        <tbody></tbody>

						<tfoot class="day-main-table-footer">
							<tr>
								<td>0.00</td>
								<td>0.00</td>
								<td>0.00</td>
							</tr>
						</tfoot>
					</table>
				</div>

			</div>

		</div>
	</div>

    <script id="workSitesTemplate" type="text/x-jquery-tmpl">
        <li>
            <input type="radio" id="Site_${SiteID}" name="Site" value="${SiteID}">
            <label for="Site_${SiteID}">${Sites[SiteID]}</label>
        </li>
    </script>

	<div id="ReportIndividualMailing" class="newsletter-report report-table" style="margin-bottom: 50px">
		<div class="reports-title">Отчет по рассылке </div>


		<div class="panel assol-grey-panel">
			<div class="report-filter-wrap clear">

				<div class="date-filter-block">
					<div class="form-group">
						<label for="mailing-month">Месяц</label>
						<select class="assol-btn-style" id="mailing-month">
							<option value="0">Январь</option>
							<option value="1">Февраль</option>
							<option value="2">Март</option>
							<option value="3">Апрель</option>
							<option value="4">Май</option>
							<option value="5">Июнь</option>
							<option value="6">Июль</option>
							<option value="7">Август</option>
							<option value="8">Сентябрь</option>
							<option value="9">Октябрь</option>
							<option value="10">Ноябрь</option>
							<option value="11">Декабрь</option>
						</select>
					</div>
				</div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="mailing-year">Год</label>

                        <div class='input-group date' id='mailing-year'>
                            <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        var years = $('#mailing-year');
                        var months = $('#mailing-month');

                        years.datetimepicker({
                            locale: 'ru',
                            format: 'YYYY',
                            viewMode: 'years',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function () {
                            $.ReportTranslate.ReloadReportMailingMeta();
                        });

                        months.change(function () {
                            $.ReportTranslate.ReloadReportMailingMeta();
                        });

                        months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                    });
                </script>

                <div>
                    <div class="form-group">
                        <label for="mailingSite">Сайт</label>
                        <div class="btn-group assol-select-dropdown" id="mailingSite">
                            <div class="label-placement-wrap">
                                <button class="btn" data-label-placement="">Выбрать</button>
                            </div>
                            <button data-toggle="dropdown" class="btn dropdown-toggle">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu"></ul>
                        </div>
                    </div>
                </div>
            </div>
		</div>

        <style>
            td[day] div:empty {
                height: 100%;
            }

            td[day] input {
                width: 90%;
                margin-left: 5%;
            }
        </style>

        <script id="reportIndividualMailingTemplate" type="text/x-jquery-tmpl">
            <thead>
                <tr class="sticky-row">
                    <th class="sticky-cell">Девушка</th>
                    <th>ID</th>
                    <th>Возраст</th>
                    {{each days}}
                        <th>${$value}</th>
                    {{/each}}
                </tr>
            </thead>
            <tbody>
                {{each customers}}
                    <tr id-cross="${es2cID}">
                        <td class="sticky-cell"><span class="nobr">${SName} ${FName}</span></td>
                        <td day="101"><div></div></td>
                        <td day="102"><div></div></td>
                        {{each days}}
                            <td day="${$value}"><div></div></td>
                        {{/each}}
                    </tr>
                {{/each}}
            </tbody>
        </script>

		<div class="newsletter-report-table reports-table-wrap sticky-table sticky-headers sticky-ltr-cells">
			<table id="ReportIndividualMailing_data"></table>
		</div>
	</div>

	<div id="ReportIndividualCorrespondence" class="correspondence-report report-table" style="margin-bottom: 50px">
		<div class="reports-title">Отчет по переписке</div>


		<div class="panel assol-grey-panel">
			<div class="report-filter-wrap clear">

				<div class="date-filter-block">
					<div class="form-group">
						<label for="correspondence-month">Месяц</label>
						<select class="assol-btn-style" id="correspondence-month">
							<option value="0">Январь</option>
							<option value="1">Февраль</option>
							<option value="2">Март</option>
							<option value="3">Апрель</option>
							<option value="4">Май</option>
							<option value="5">Июнь</option>
							<option value="6">Июль</option>
							<option value="7">Август</option>
							<option value="8">Сентябрь</option>
							<option value="9">Октябрь</option>
							<option value="10">Ноябрь</option>
							<option value="11">Декабрь</option>
						</select>
					</div>
				</div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="correspondence-year">Год</label>

                        <div class='input-group date' id='correspondence-year'>
                            <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="correspondenceSite">Сайт</label>
                        <div class="btn-group assol-select-dropdown" id="correspondenceSite">
                            <div class="label-placement-wrap">
                                <button class="btn" data-label-placement="">Выбрать</button>
                            </div>
                            <button data-toggle="dropdown" class="btn dropdown-toggle">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu"></ul>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        var years = $('#correspondence-year');
                        var months = $('#correspondence-month');

                        years.datetimepicker({
                            locale: 'ru',
                            format: 'YYYY',
                            viewMode: 'years',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function () {
                            $.ReportTranslate.ReloadReportCorrespondenceMeta();
                        });

                        months.change(function () {
                            $.ReportTranslate.ReloadReportCorrespondenceMeta();
                        });

                        months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                    });
                </script>
			</div>
		</div>

        <style>
            .btn-add {
                color: white;
                background: #2fc4f5;
            }
            .btn-add:focus {
                color: white;
            }

            .btn-remove {
                color: white;
                background: red;
            }
            .btn-remove:focus {
                color: white;
            }
        </style>

        <script id="reportIndividualCorrespondenceTemplate" type="text/x-jquery-tmpl">
            <thead>
                <tr>
                    <th>Девушка</th>
                    <th>ID</th>
                    <th>Мужчины</th>
                    <th>ID</th>
                    {{each days}}
                        <th>${$value}</th>
                    {{/each}}
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {{each info}}
                    <tr id-record="${id}">
                        <td><div>${SName} ${FName}</div></td>
                        <td day="102"><div>{{if $value['id-info'] > 0}}${$value['id-info']}{{/if}}</div></td>
                        <td day="103"><div>${$value['men-info']}</div></td>
                        <td day="104"><div>{{if $value['id-men-info'] > 0}}${$value['id-men-info']}{{/if}}</div></td>
                        {{each days}}
                            <td day="${$value}"><div></div></td>
                        {{/each}}
                        <td>
                            <button class="btn btn-remove action-correspondence-remove">
                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-add action-correspondence-append" data-toggle="modal" data-target="#addCorrespondenceRecordForm">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </button>
                        </td>
                    </tr>
                {{/each}}
            </tbody>
            {{if info.length > 10}}
                <thead>
                    <tr>
                        <th>Девушка</th>
                        <th>ID</th>
                        <th>Мужчины</th>
                        <th>ID</th>
                        {{each days}}
                            <th>${$value}</th>
                        {{/each}}
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
            {{/if}}
        </script>

		<div class="correspondence-report-table reports-table-wrap">
            <table id="ReportIndividualCorrespondence_data"></table>
		</div>

        <?php if ($role['isTranslate']): ?>
            <div class="form-group clear save-edit-wrap">
                <button class="btn assol-btn add right" role="button" data-toggle="modal" data-target="#addCorrespondenceRecordForm">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    ДОБАВИТЬ СТРОКУ
                </button>
            </div>
        <?php endif ?>

        <script id="correspondenceCustomerTemplate" type="text/x-jquery-tmpl">
            <li>
                <input type="radio" id="Customer_${ID}" name="Customer" value="${ID}">
                <label for="Customer_${ID}">${SName} ${FName}</label>
            </li>
        </script>

        <!-- Modal -->
        <div class="modal fade" id="addCorrespondenceRecordForm" tabindex="-1" role="dialog" aria-labelledby="addCorrespondenceRecordFormLabel">
            <div class="modal-dialog" role="document" style="width: 520px">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addCorrespondenceRecordFormLabel">Добавление отчетных данных клиента</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="addCorrespondenceRecordOffset" value="0">
                        <div class="form-group">
                            <label for="correspondenceCustomer">Клиент</label>
                            <div class="btn-group assol-select-dropdown" id="correspondenceCustomer">
                                <div class="label-placement-wrap">
                                    <button class="btn" data-label-placement="">Выбрать</button>
                                </div>
                                <button data-toggle="dropdown" class="btn dropdown-toggle">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="addCorrespondenceRecord" type="button" class="btn btn-primary">Добавить запись</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(function () {
                $(document).on('click', '.action-correspondence-append', function () {
                    $('#addCorrespondenceRecordOffset').val($(this).closest('[id-record]').attr('id-record'));
                });

                $(document).on('hidden.bs.modal', '#addCorrespondenceRecordForm', function () {
                    $('#addCorrespondenceRecordOffset').val(0);
                });
            });
        </script>

	</div>

	<div id="ReportIndividualSalary" class="salary-report report-table" style="margin-bottom: 50px">
		<div class="reports-title">Отчет по зарплате</div>

		<div class="panel assol-grey-panel">
			<div class="report-filter-wrap clear">

                <div class="date-filter-block">
                    <div class="form-group">
                        <label for="salary-month">Месяц</label>
                        <select class="assol-btn-style" id="salary-month">
                            <option value="0">Январь</option>
                            <option value="1">Февраль</option>
                            <option value="2">Март</option>
                            <option value="3">Апрель</option>
                            <option value="4">Май</option>
                            <option value="5">Июнь</option>
                            <option value="6">Июль</option>
                            <option value="7">Август</option>
                            <option value="8">Сентябрь</option>
                            <option value="9">Октябрь</option>
                            <option value="10">Ноябрь</option>
                            <option value="11">Декабрь</option>
                        </select>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="salary-year">Год</label>

                        <div class='input-group date' id='salary-year'>
                            <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        var years = $('#salary-year');
                        var months = $('#salary-month');

                        years.datetimepicker({
                            locale: 'ru',
                            format: 'YYYY',
                            viewMode: 'years',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function () {
                            $.ReportTranslate.ReloadReportSalary();
                            checkOverlayReport();
                        });

                        months.change(function () {
                            $.ReportTranslate.ReloadReportSalary();
                            checkOverlayReport();
                        });

                        months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                        checkOverlayReport();
                    });

                    // проверка отправки отчета по зарплате
                    // если отчет за выбранный год и месяц уже отправлен – то кнопка отправки становится disabled
                    // если отчет за выбранный год и месяц ещё не отправлен – то кнопка отправки становится активной
                    function checkOverlayReport(){
                        var year = $('#salary-year').find('input').val();
                        var month = $('#salary-month').val();
                        $.post(
                            '/Reports_Salary/checkoverlay/',
                            {
                                year: year,
                                month: month
                            },
                            function(data){
                                if(data*1 > 0){
                                    $('#submit-report-salary').removeClass('add').addClass('disabled');
                                }
                                else{
                                    $('#submit-report-salary').removeClass('disabled').addClass('add');
                                }
                            },
                            'text'
                        );
                    }
                </script>

			</div>
		</div>

        <style>
            .salary-report-table td[type] div:empty {
                height: 100%;
            }

            .salary-report-table td[type] {
                width: 105px;
            }

            .salary-report-table td[type] input {
                width: 90%;
                max-width: 80px;
            }

            .salary-report .salary-report-table table .salary-report-sum td.salary-total {
                background: #d7d7d7;
                border-color: #d7d7d7;
                text-align: center;
                font-weight: bold;
            }

            .site-name {
                color: #2067b0;
                font-weight: 700;
            }
        </style>

        <script id="reportIndividualSalaryTemplate" type="text/x-jquery-tmpl">
            <tr id-employee-site="${ID}">
                <td><span class="site-name">${Sites[SiteID]}</span></td>
                <td type="emailCount" class="decimal"><div>${emailCount}</div></td>
                <td type="emailAmount" class="decimal"><div>${emailAmount}</div></td>
                <td type="chatCount" class="decimal"><div>${chatCount}</div></td>
                <td type="chatAmount" class="decimal"><div>${chatAmount}</div></td>
                <td type="deliveryCount" class="decimal"><div>${deliveryCount}</div></td>
                <td type="deliveryAmount" class="decimal"><div>${deliveryAmount}</div></td>
                {{if IsDealer > 0}}
                    <td type="dealerCount" class="decimal"><div>${dealerCount}</div></td>
                    <td type="dealerAmount" class="decimal"><div>${dealerAmount}</div></td>
                {{else}}
                    <td>-</td>
                    <td>-</td>
                {{/if}}
                <td></td>
            </tr>
        </script>

        <div class="salary-report-table reports-table-wrap">
			<table id="ReportIndividualSalary_data">
				<thead>
					<tr>
						<th rowspan="2">Сайт</th>
						<th colspan="2">Письма</th>
						<th colspan="2">Чат</th>
						<th colspan="2">Доставка</th>
                        <th colspan="2">Дилерские</th>
						<th></th>
					</tr>
					<tr>
						<th>кол-во</th>
						<th>сумма</th>
						<th>кол-во</th>
						<th>сумма</th>
						<th>кол-во</th>
						<th>сумма</th>
                        <th>кол-во</th>
                        <th>сумма</th>
						<th>общее</th>
					</tr>
				</thead>

                <tbody></tbody>

				<tfoot class="salary-report-sum">
					<tr>
						<td colspan="8"></td>
						<td class="salary-total"><strong>итого:</strong></td>
						<td class="salary-total"></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class="form-group clear save-edit-wrap">
<!--			<div class="right">* отправка отчета доступна с 01 по 07 число каждого месяца</div>-->
			<div class="text-right"><strong>Внимание!</strong> Отправка отчета выполняется только один раз в месяц!</div>

			<?php if ($role['isTranslate']): ?>

                <br>
                <button id="submit-report-salary" class="btn assol-btn right add" role="button">
                    ОТПРАВИТЬ
                </button>

                <script>
//                    $(document).ready(function () {
//                        var day = moment().date();
//
//                        if (day >= 1 && day <= 7)
//                            $('#submit-report-salary').removeClass('disabled').addClass('add');
//                    });
                </script>

            <?php endif ?>
		</div>
	</div>

<?php if($role['isTranslate']): ?>

    <div id="ReportApprovedSalary" class="approved-salary-report report-table" style="margin-bottom: 50px">
        <div class="reports-title">Подтвержденная зарплата</div>


        <div class="panel assol-grey-panel">
            <div class="report-filter-wrap clear">

                <div class="date-filter-block">
                    <div class="form-group">
                        <label for="approved-salary-month">Месяц</label>
                        <select class="assol-btn-style" id="approved-salary-month">
                            <option value="0">Январь</option>
                            <option value="1">Февраль</option>
                            <option value="2">Март</option>
                            <option value="3">Апрель</option>
                            <option value="4">Май</option>
                            <option value="5">Июнь</option>
                            <option value="6">Июль</option>
                            <option value="7">Август</option>
                            <option value="8">Сентябрь</option>
                            <option value="9">Октябрь</option>
                            <option value="10">Ноябрь</option>
                            <option value="11">Декабрь</option>
                        </select>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="approved-salary-year">Год</label>

                        <div class='input-group date' id='approved-salary-year'>
                            <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        var years = $('#approved-salary-year');
                        var months = $('#approved-salary-month');

                        years.datetimepicker({
                            locale: 'ru',
                            format: 'YYYY',
                            viewMode: 'years',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function (e) {
                            $.ReportTranslate.ReloadReportApprovedSalary(e.date.year());
                        });

                        months.change(function () {
                            $.ReportTranslate.ReloadReportApprovedSalary();
                        });

                        months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                    });
                </script>
            </div>
        </div>

        <script id="reportApprovedSalaryTemplate" type="text/x-jquery-tmpl">
            <tr paid="${paid}">
                <td><span class="site-name">${Sites[SiteID]}</span></td>
                <td>${value}</td>
                <td>
                    {{if paid == 1}}
                    <div class="approved-salary-status on">
                        <div class="round-check on"></div>
                        <span>выдано</span>
                    </div>
                    {{/if}}
                </td>
            </tr>
        </script>

        <div class="approved-salary-report-table reports-table-wrap">
            <table id="ReportApprovedSalary_data">
                <thead>
                    <tr>
                        <th>Сайт</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot class="approved-salary-report-sum">
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="approved-salary-conclusion">
                                <div>
                                    Начислено: <strong>0</strong>
                                </div>
                                <div>
                                    Выдано: <strong>0</strong>
                                </div>
                                <div>
                                    Остаток: <strong>0</strong>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

<?php endif ?>

<?php if($role['isDirector']): ?>

	<div id="ReportGeneralOfCustomers" class="day-reports report-table">
		<div class="reports-title">
            Общая таблица по клиентам
            <span class="pull-right history-small-help">Для скролла: "Наведите на таблицу и используйте Shift + прокрутка колесом мышки"</span>
        </div>

        <div class="panel assol-grey-panel">
            <div class="report-filter-wrap clear">

                <!--                New-->
                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="goc-daily-from">С</label>

                        <div class='input-group date' id='goc-daily-from'>
                            <input type='text' class="assol-btn-style" id="goc_daily_from_input" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="goc-daily-to">До</label>

                        <div class='input-group date' id='goc-daily-to'>
                            <input type='text' class="assol-btn-style" id="goc_daily_to_input" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <style>
                    .refresh-rotate{
                        -moz-transition: all 1s linear;
                        -webkit-transition: all 1s linear;
                        transition: all 1s linear;
                    }

                    .refresh-rotate.refresh-down{
                        -moz-transform:rotate(360deg);
                        -webkit-transform:rotate(360deg);
                        transform:rotate(360deg);
                    }
                </style>
                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="goc-daily-to">&nbsp;</label>

                        <div class='input-group date'>
                            <a href="#" class="btn btn-default" id="goc_daily_refresh">
                                <span class="glyphicon glyphicon-refresh refresh-rotate" aria-hidden="true"></span>
                                &nbsp;Обновить
                            </a>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    $(function() {
                        var daily_from = $('#goc-daily-from');
                        var daily_to = $('#goc-daily-to');

                        daily_from.datetimepicker({
                            locale: 'ru',
                            format: 'DD-MM-YYYY',
                            viewMode: 'days',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function (e) {
                            $.ReportDirector.ReloadGeneralOfCustomersMeta();
                        });

                        daily_to.datetimepicker({
                            locale: 'ru',
                            format: 'DD-MM-YYYY',
                            viewMode: 'days',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function (e) {
                            $.ReportDirector.ReloadGeneralOfCustomersMeta();
                        });

                        var goc_refresh_counter = 0;
                        $('#goc_daily_refresh').click(function (e) {
                            e.preventDefault();
                            goc_refresh_counter += 360;
                            $(this).find('.glyphicon.glyphicon-refresh').css('transform', 'rotate(' + goc_refresh_counter + 'deg)');
//                            $(this).find('.glyphicon.glyphicon-refresh').toggleClass('refresh-down');
                            $.ReportDirector.ReloadGeneralOfCustomersMeta();
                        });
                    });
                </script>
                <!--                /New-->
                <?php /*div class="date-filter-block">
                    <div class="form-group">
                        <label for="general-customers-day">Число</label>
                        <select class="assol-btn-style" id="general-customers-day">

                        </select>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group">
                        <label for="general-customers-month">Месяц</label>
                        <select class="assol-btn-style" id="general-customers-month">
                            <option value="0">Январь</option>
                            <option value="1">Февраль</option>
                            <option value="2">Март</option>
                            <option value="3">Апрель</option>
                            <option value="4">Май</option>
                            <option value="5">Июнь</option>
                            <option value="6">Июль</option>
                            <option value="7">Август</option>
                            <option value="8">Сентябрь</option>
                            <option value="9">Октябрь</option>
                            <option value="10">Ноябрь</option>
                            <option value="11">Декабрь</option>
                        </select>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="general-customers-year">Год</label>

                        <div class='input-group date' id='general-customers-year'>
                            <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        var years = $('#general-customers-year');
                        var months = $('#general-customers-month');
                        var days = $('#general-customers-day');

                        years.datetimepicker({
                            locale: 'ru',
                            format: 'YYYY',
                            viewMode: 'years',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function (e) {
                            $.ReportDirector.ReloadGeneralOfCustomersMeta(true);
                            $.ReportDirector.ReloadReportGeneralOfCustomersData(e.date.year());
                        });

                        months.change(function () {
                            $.ReportDirector.ReloadGeneralOfCustomersMeta(true);
                            $.ReportDirector.RefreshReportGeneralOfCustomersDate();
                        });

                        days.change(function () {
                            $.ReportDirector.ReloadGeneralOfCustomersMeta(true);
                            $.ReportDirector.ReloadReportGeneralOfCustomersData();
                        });

                        months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                    });
                </script*/?>

            </div>
        </div>

        <script id="reportGeneralOfCustomers_fixedWrapBody_Template" type="text/x-jquery-tmpl">
            <tr id="gc_slide_total_${CustomerID}">
                <td>0</td>
            </tr>
        </script>

        <div class="day-main-fixed-wrap" style="display: none">
            <div class="day-main-fixed-table reports-table-wrap">
                <table id="ReportGeneralOfCustomers_fixedWrapBody">
                    <thead>
                        <tr><th>&nbsp;</th></tr>
                        <tr><th>&nbsp;</th></tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot class="day-main-table-footer">
                        <tr><td style="font-weight: bold">0</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div style="margin: 10px 0;"><h5 class="goc-report-my-title"></h5></div>
		<div class="day-reports-tables clear reports-table-wrap">

			<div class="day-reports-tables-in">

                <script id="reportGeneralOfCustomersTemplate" type="text/x-jquery-tmpl">
                    <thead>
                        <tr class="sticky-row">
                            <th rowspan="2" class="sticky-cell">Клиенты</th>

                            {{each work_sites}}
                                <th colspan="2"><span class="site-name">${Sites[$value.SiteID]}</span></th>
                            {{/each}}

                            <th rowspan="2" class="th-result-vertical th-centered sortable">Итого</th>
                        </tr>
                        <tr class="sticky-row">
                            {{each work_sites}}
                                <th><div>письма</div></th>
                                <th><div>чат</div></th>
                            {{/each}}
                        </tr>
                    </thead>

                    <tbody>
                        {{each customers}}
                            <tr>
                                <td title="${SName} ${FName}" class="sticky-cell"><div>${SName} ${FName}</div></td>

                                {{each work_sites}}
                                    <td id="gc_mail_${CustomerID}_${SiteID}" title="${SName} ${FName}"><div>0</div></td>
                                    <td id="gc_chat_${CustomerID}_${SiteID}" title="${SName} ${FName}"><div>0</div></td>
                                {{/each}}

                                <td class="td-bold td-result" style="padding-left: 10px;" id="goc_slide_total_${CustomerID}">0.00</td>
                            </tr>
                        {{/each}}
                    </tbody>

                    <tfoot class="day-main-table-footer">
                        <tr>
                            <td class="sticky-cell td-bold td-result text-right" style="padding-right: 10px;">Итого:</td>

                            {{each work_sites}}
                                <td class="td-bold td-result"><div id="gc_foot_mail_${$value.SiteID}">0.00</div></td>
                                <td class="td-bold td-result"><div id="gc_foot_chat_${$value.SiteID}">0.00</div></td>
                            {{/each}}

                            <td class="td-bold td-result" style="padding-left: 10px;" id="goc_foot_total">0.00</td>
                        </tr>
                    </tfoot>
                </script>

                <div class="day-main-table sticky-table sticky-headers sticky-ltr-cells">
                    <table id="ReportGeneralOfCustomers_data" class="tablesorter"></table>
                </div>

                <script id="reportGeneralOfCustomers_total_Template" type="text/x-jquery-tmpl">
                    <tr id="gc_total_${CustomerID}">
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                </script>

                <div class="day-total-table reports-table-wrap" style="display: none;">
                    <table id="ReportGeneralOfCustomers_total">
                        <thead>
                        <tr>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                            <th>письма</th>
                            <th>чат</th>
                            <th>всего</th>
                        </tr>
                        </thead>

                        <tbody></tbody>

                        <tfoot class="day-main-table-footer">
                        <tr>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
			</div>

		</div>

	</div>

<?php endif ?>

</div>

<? endif ?>

<? if($role['isDirector']): ?>

    <div id="ReportOverallSalary" class="overlay-salary-report report-table">
        <div class="reports-title">Подтвержденная зарплата</div>


        <div class="panel assol-grey-panel">
            <div class="report-filter-wrap clear">

                <div class="date-filter-block">
                    <div class="form-group">
                        <label for="overlay-salary-month">Месяц</label>
                        <select class="assol-btn-style" id="overlay-salary-month">
                            <option value="0">Январь</option>
                            <option value="1">Февраль</option>
                            <option value="2">Март</option>
                            <option value="3">Апрель</option>
                            <option value="4">Май</option>
                            <option value="5">Июнь</option>
                            <option value="6">Июль</option>
                            <option value="7">Август</option>
                            <option value="8">Сентябрь</option>
                            <option value="9">Октябрь</option>
                            <option value="10">Ноябрь</option>
                            <option value="11">Декабрь</option>
                        </select>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="overlay-salary-year">Год</label>

                        <div class='input-group date' id='overlay-salary-year'>
                            <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="overlaySalarySite">Сайт</label>
                        <div class="btn-group assol-select-dropdown" id="overlaySalarySite">
                            <div class="label-placement-wrap">
                                <button class="btn" data-label-placement="">Выбрать</button>
                            </div>
                            <button data-toggle="dropdown" class="btn dropdown-toggle">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach($sites as $item): ?>
                                    <li>
                                        <input type="radio" id="OverlaySalarySite_<?=$item['ID']?>" name="Site" value="<?=$item['ID']?>">
                                        <label for="OverlaySalarySite_<?=$item['ID']?>"><?= empty($item['Name']) ? $item['Domen'] : $item['Name'] ?></label>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        var years = $('#overlay-salary-year');
                        var months = $('#overlay-salary-month');

                        years.datetimepicker({
                            locale: 'ru',
                            format: 'YYYY',
                            viewMode: 'years',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function () {
                            $.ReportDirector.ReloadReportOverallSalary();
                        });

                        months.change(function () {
                            $.ReportDirector.ReloadReportOverallSalary();
                        });

                        months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                    });
                </script>

            </div>
        </div>

        <style>

            .overlay-salary-report-table .not-data {
                color: lightgrey;
                font-style: italic;
            }

            .overlay-salary-report-table td[type] div:empty {
                height: 100%;
            }

            .overlay-salary-report-table td[type] {
                width: 70px;
            }

            .overlay-salary-report-table td[type] input {
                width: 90%;
                max-width: 70px;
            }

            .overlay-salary-report .overlay-salary-report-table table .overlay-salary-report-sum td.salary-total {
                background: #d7d7d7;
                border-color: #d7d7d7;
                text-align: center;
                font-weight: bold;
            }

        </style>

        <script id="reportOverallSalaryTemplate" type="text/x-jquery-tmpl">
            <tr id-employee="${EmployeeID}">
                <td>${SName} ${FName}</td>

                {{if cross}}
                    <td type="emailCount"  class="decimal" original="${emailCountOriginal}" {{if emailCount != emailCountOriginal}}class="editable-cell"{{/if}} ><div>${emailCount}</div></td>
                    <td type="emailAmount" class="decimal" original="${emailAmountOriginal}" {{if emailAmount != emailAmountOriginal}}class="editable-cell"{{/if}} ><div>${emailAmount}</div></td>
                    <td type="chatCount"  class="decimal" original="${chatCountOriginal}" {{if chatCount != chatCountOriginal}}class="editable-cell"{{/if}} ><div>${chatCount}</div></td>
                    <td type="chatAmount" class="decimal" original="${chatAmountOriginal}" {{if chatAmount != chatAmountOriginal}}class="editable-cell"{{/if}} ><div>${chatAmount}</div></td>
                    <td type="deliveryCount"  class="decimal" original="${deliveryCountOriginal}" {{if deliveryCount != deliveryCountOriginal}}class="editable-cell"{{/if}} ><div>${deliveryCount}</div></td>
                    <td type="deliveryAmount" class="decimal" original="${deliveryAmountOriginal}" {{if deliveryAmount != deliveryAmountOriginal}}class="editable-cell"{{/if}} ><div>${deliveryAmount}</div></td>
                    {{if IsDealer > 0}}
                        <td type="dealerCount"  class="decimal" original="${dealerCountOriginal}" {{if dealerCount != dealerCountOriginal}}class="editable-cell"{{/if}} ><div>${dealerCount}</div></td>
                        <td type="dealerAmount" class="decimal" original="${dealerAmountOriginal}" {{if dealerAmount != dealerAmountOriginal}}class="editable-cell"{{/if}} ><div>${dealerAmount}</div></td>
                    {{else}}
                        <td>-</td>
                        <td>-</td>
                    {{/if}}
                {{else}}
                    <td><div>{{if emailCount}}${emailCount}{{else}}-{{/if}}</div></td>
                    <td><div>{{if emailAmount}}${emailAmount}{{else}}-{{/if}}</div></td>
                    <td><div>{{if chatCount}}${chatCount}{{else}}-{{/if}}</div></td>
                    <td><div>{{if chatAmount}}${chatAmount}{{else}}-{{/if}}</div></td>
                    <td><div>{{if deliveryCount}}${deliveryCount}{{else}}-{{/if}}</div></td>
                    <td><div>{{if deliveryAmount}}${deliveryAmount}{{else}}-{{/if}}</div></td>
                    {{if IsDealer > 0}}
                        <td><div>{{if dealerCount}}${dealerCount}{{else}}-{{/if}}</div></td>
                        <td><div>{{if dealerAmount}}${dealerAmount}{{else}}-{{/if}}</div></td>
                    {{else}}
                        <td>-</td>
                        <td>-</td>
                    {{/if}}
                {{/if}}


                <td></td>
                <td class="status">
                    {{if confirmation}}
                        {{if confirmation == 0}}
                            <a href="#" class="confirm">подтвердить</a>
                        {{else}}
                            подтверждено
                        {{/if}}
                    {{else}}
                        <span class="not-data">нет данных</span>
                    {{/if}}
                </td>
            </tr>
        </script>

        <div class="overlay-salary-report-table reports-table-wrap">
            <table id="ReportOverallSalary_data">
                <thead>
                <tr>
                    <th rowspan="2">Переводчики</th>
                    <th colspan="2">Письма</th>
                    <th colspan="2">Чат</th>
                    <th colspan="2">Доставка</th>
                    <th colspan="2">Дилерские</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>кол-во</th>
                    <th>сумма</th>
                    <th>кол-во</th>
                    <th>сумма</th>
                    <th>кол-во</th>
                    <th>сумма</th>
                    <th>кол-во</th>
                    <th>сумма</th>
                    <th>общее</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot class="overlay-salary-report-sum">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="salary-total"><strong>итого:</strong></td>
                    <td class="salary-total"></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-group clear save-edit-wrap">
            <div class="right"><span class="gray-square"></span> * Редактированая ячейка</div>
        </div>
    </div>

    <div id="ReportGeneralSalary" class="total-salary-report report-table">
        <div class="reports-title">Выплата зарплаты</div>


        <div class="panel assol-grey-panel">
            <div class="report-filter-wrap clear">

                <div class="date-filter-block">
                    <div class="form-group">
                        <label for="general-salary-month">Месяц</label>
                        <select class="assol-btn-style" id="general-salary-month">
                            <option value="0">Январь</option>
                            <option value="1">Февраль</option>
                            <option value="2">Март</option>
                            <option value="3">Апрель</option>
                            <option value="4">Май</option>
                            <option value="5">Июнь</option>
                            <option value="6">Июль</option>
                            <option value="7">Август</option>
                            <option value="8">Сентябрь</option>
                            <option value="9">Октябрь</option>
                            <option value="10">Ноябрь</option>
                            <option value="11">Декабрь</option>
                        </select>
                    </div>
                </div>

                <div class="date-filter-block">
                    <div class="form-group calendar-block">
                        <label for="general-salary-year">Год</label>

                        <div class='input-group date' id='general-salary-year'>
                            <input type='text' class="assol-btn-style" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        var years = $('#general-salary-year');
                        var months = $('#general-salary-month');

                        years.datetimepicker({
                            locale: 'ru',
                            format: 'YYYY',
                            viewMode: 'years',
                            defaultDate: 'now',
                            showTodayButton: true
                        }).on('dp.change', function () {
                            $.ReportDirector.ReloadReportGeneralSalary();
                        });

                        months.change(function () {
                            $.ReportDirector.ReloadReportGeneralSalary();
                        });

                        months.find("[value='" + moment().month() + "']").attr("selected", "selected");
                    });
                </script>

            </div>
        </div>

        <style>

            .total-salary-report-table thead tr:last-child th {
                background-color: white;
                text-align: left;
                padding-left: 10px;
            }

            .total-salary-report-table thead tr:last-child td {
                background-color: white;
                text-align: center;
            }

            .total-salary-report-table tfoot tr:last-child td {
                font-weight: bold;
            }

            .total-salary-report-table td[id-site] div:empty {
                height: 100%;
            }

            .total-salary-report-table td[id-site] {
                width: 70px;
            }

            .total-salary-report-table td[id-site] input {
                width: 90%;
            }

        </style>

        <script id="reportGeneralSalarySumTemplate" type="text/x-jquery-tmpl">
            <div class="sum-num-wrap" id-record="${id}">
                <a href="#">${value}</a>
                <div class="is-repay-tooltip">
                    <div class="tooltip-content">
                        <div>Выдана сумма <strong>${value}</strong></div>
                        <a class="yes" href="#">Да</a>&nbsp;&nbsp;
                        <a class="no" href="#">Нет</a>
                    </div>
                    <div class="arrow"></div>
                </div>
            </div>
        </script>

        <div class="total-salary-report-table reports-table-wrap sticky-table sticky-headers sticky-ltr-cells">
            <table id="ReportGeneralSalary_data">
                <thead>
                <tr class="sticky-row">
                    <th class="sticky-cell">ФИО</th>
                    <th class="sticky-cell">итого</th>

                    <? foreach($sites as $item): ?>
                        <th nowrap><span class="site-name"><?= empty($item['Name']) ? $item['Domen'] : $item['Name'] ?></span></th>
                    <? endforeach ?>
                </tr>
                <tr class="sticky-row" id-employee="0">
                    <th class="sticky-cell" style="text-align: right !important; text-transform: uppercase; padding-right: 6px;">Зашло:</th>
                    <th class="sticky-cell" data-total id="pay_enter_total"></th>

                    <? foreach($sites as $site): ?>
                        <td class="decimal" id-site="<?= $site['ID'] ?>"><div></div></td>
                    <? endforeach ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach($translators as $translator): ?>
                    <tr id-employee="<?= $translator['ID'] ?>">
                        <td class="sticky-cell" nowrap style="padding-right: 6px;">
                            <?= $translator['SName'] ?> <?= $translator['FName'] ?>
                        </td>
                        <td class="sticky-cell" data-total id="pay_row_total_<?= $translator['ID'] ?>"></td>

                        <? foreach($sites as $site): ?>
                            <td id-site="<?= $site['ID'] ?>"><div></div></td>
                        <? endforeach ?>
                    </tr>
                <?php endforeach ?>
                </tbody>

                <tfoot class="total-salary-report-sum">
                <tr>
                    <td class="sticky-cell"></td>
                    <td class="sticky-cell"></td>

                    <? foreach($sites as $item): ?>
                        <td></td>
                    <? endforeach ?>
                </tr>

                <tr id="pay_total_row">
                    <td class="sticky-cell" style="text-align: right !important; text-transform: uppercase; padding-right: 6px;">Остаток:</td>
                    <td class="sticky-cell" data-total id="pay_cell_rows_total"></td>

                    <? foreach($sites as $site): ?>
                        <td id-site="<?= $site['ID'] ?>">0</td>
                    <? endforeach ?>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-group clear save-edit-wrap">
            <br>
            <div class="right"><span class="blue-square"></span> * Выданная сумма</div>
        </div>
    </div>

<? endif ?>

<? if($role['isDirector'] || $role['isSecretary']): ?>

    <div id="ReportOverallAllocation" class="overall-allocation-report report-table">
    <div class="reports-title">Сводная таблица распределения </div>


    <div class="panel assol-grey-panel">
        <div class="report-filter-wrap clear">
            <div>
                <div class="form-group">
                    <label for="overallAllocationSite">Сайт</label>
                    <div class="btn-group assol-select-dropdown" id="overallAllocationSite">
                        <div class="label-placement-wrap">
                            <button class="btn" data-label-placement="">Выбрать</button>
                        </div>
                        <button data-toggle="dropdown" class="btn dropdown-toggle">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach($sites as $item): ?>
                                <li>
                                    <input type="radio" id="OverallAllocationSite_<?=$item['ID']?>" name="Site" value="<?=$item['ID']?>">
                                    <label for="OverallAllocationSite_<?=$item['ID']?>"><?= empty($item['Name']) ? $item['Domen'] : $item['Name'] ?></label>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script id="reportOverallAllocation_Template" type="text/x-jquery-tmpl">
            {{each employee}}
                <tr id-employee="${$value.ID}">
                    <td><span class="nobr">${$value.SName} ${$value.FName}</span></td>
                    <td>${$value.customers}</td>

                    {{if $value.IsFirst}}
                        <td width="20%" rowspan="1000">${freeCustomers}</td>
                    {{/if}}
                </tr>
            {{/each}}
        </script>

    <div class="overall-allocation-report-table reports-table-wrap">
        <table id="ReportOverallAllocation_data" style="width:100%">
            <thead>
            <tr>
                <th style="width:20%">Сотрудник</th>
                <th style="width:55%">Клиентки</th>
                <th style="width:25%">Свободные</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
        <br>
        <table id="ReportOverallNotAllocation_data" style="width:100%">
            <thead>
            <tr>
                <th style="text-align: center">Нет на сайте</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

    <div id="ReportOverallCustomersSites" class="report-table overall-customer-sites">
        <div class="row">
            <style>
                .cs-table > tbody > tr > td {
                    border: 1px solid #ddd;
                }
                .cs-tb-link {
                    color: #000;
                    text-decoration: none;
                }
                .cs-tb-link:hover {
                    text-decoration: none;
                }
            </style>
            <div class="col-md-12 clearfix">
                <div class="reports-title">
                    Клиенты &harr; Сайты
                    <span class="pull-right history-small-help">Для скролла: "Наведите на таблицу и используйте Shift + прокрутка колесом мышки"</span>
                </div>
            </div>
        </div>
        <div class="row" style="padding-right: 15px;">
        <?php if(!empty($sites) && !empty($cs_customers)): ?>
            <div class="col-md-12 clearfix" style="padding-right: 0;">
                <div class="sticky-table sticky-headers sticky-ltr-cells" style="margin-bottom: 50px;">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr class="sticky-row">
                            <th class="sticky-cell">ФИО</th>
                            <?php foreach($sites as $th_site): ?>
                                <th nowrap class="site-name"><?= $th_site['Name']; ?></th>
                            <?php endforeach; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($cs_customers as $cs_item): ?>
                            <tr>
                                <?php
                                $fname = mb_substr($cs_item['FName'], 0, 1, 'UTF-8');
                                $mname = mb_substr($cs_item['MName'], 0, 1, 'UTF-8');
                                ?>
                                <td nowrap class="sticky-cell">
                                    <?= $cs_item['SName'] . ' ' . $fname . '.' . $mname . '.'; ?>
                                </td>
                                <?php foreach($sites as $tb_site): ?>
                                    <td nowrap>
                                        <?php
                                        $tb_text = '&dash;';
                                        if(!empty($cs_item['CS'][$tb_site['ID']])){
                                            $tb_text = $cs_item['CS'][$tb_site['ID']];
                                        }
                                        elseif(in_array($tb_site['ID'], array_keys($cs_item['CS']))){
                                            $tb_text = 'Есть';
                                        }
                                        echo ($tb_text !== '&dash;')
                                            ? '<a class="cs-tb-link" href="/customer/' . $cs_item['ID'] . '/profile#Sites" target="_blank">' . $tb_text . '</a>'
                                            : $tb_text;
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="col-md-12">
                <h4>Нет данных для отображения</h4>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <div id="ReportDailyEmployees" class="report-table overall-customer-sites">
        <?php $this->load->view('form/reports/general/daily_employees', array(
                'sites' => $sites,
                'translators' => $translators,
                'daily_reports' => $daily_reports,
        )); ?>
    </div>

<? endif ?>