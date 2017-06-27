<?php
// TODO: изменение данных в таблице при работе фильтров по датам
?>

<div class="row">
    <div class="col-md-12 clearfix">
        <div class="reports-title">
            Ежедневный отчет по сотрудникам
        </div>
    </div>
</div>
<?php if(!empty($sites) && !empty($translators)): ?>

    <div class="panel assol-grey-panel">
        <div class="report-filter-wrap clear">

            <div class="date-filter-block">
                <div class="form-group">
                    <label for="daily-day">Число</label>
                    <select class="assol-btn-style" id="de-daily-day">
                        <?php
                        $num_days = date('t');
                        for($i = 1; $i <= $num_days; $i++):
                            $selected = ($i == (int)date('j')) ? 'selected="selected"' : '';
                        ?>
                        <option value="<?= $i; ?>" <?= $selected; ?>><?= $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="date-filter-block">
                <div class="form-group">
                    <label for="daily-month">Месяц</label>
                    <select class="assol-btn-style" id="de-daily-month">
                        <?php
                        $months = array(
                            "01"=>"Январь", "02"=>"Февраль", "03"=>"Март", "04"=>"Апрель",
                            "05"=>"Май", "06"=>"Июнь", "07"=>"Июль", "08"=>"Август",
                            "09"=>"Сентябрь", "10"=>"Октябрь", "11"=>"Ноябрь", "12"=>"Декабрь",
                        );
                        foreach ($months as $mk => $month):
                            $selected = ($mk === date('m')) ? 'selected="selected"' : '';
                        ?>
                        <option value="<?= $mk; ?>" <?= $selected; ?>><?= $month; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="date-filter-block">
                <div class="form-group calendar-block">
                    <label for="daily-year">Год</label>
                    <div class='input-group date' id='de-daily-year'>
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
                    var years = $('#de-daily-year');
                    var months = $('#de-daily-month');
                    var days = $('#de-daily-day');

                    years.datetimepicker({
                        locale: 'ru',
                        format: 'YYYY',
                        viewMode: 'years',
                        defaultDate: 'now',
                        showTodayButton: true
                    }).on('dp.change', function (e) {
                        // действия при изменении Года
                    });

                    months.change(function () {
                        // действия при изменении Месяца
                    });

                    days.change(function () {
                        // действия при изменении Дня
                    });
                });
            </script>

        </div>
    </div>

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
<div class="row" style="margin-bottom: 50px;">
    <div class="col-md-12" id="dailyEmployeeTable">
        <?php $this->load->view('form/reports/general/de_table', array(
            'sites' => $sites,
            'translators' => $translators,
            'daily_reports' => $daily_reports,
        )); ?>
    </div>
</div>

<?php else: ?>
    <div class="row">
        <div class="clo-md-12">
            <h5 class="text-center">Нет данных для отображения</h5>
        </div>
    </div>
<?php endif; ?>