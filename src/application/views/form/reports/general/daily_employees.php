<?php
// TODO: изменение данных в таблице при работе фильтров по датам
?>

<div class="row">
    <div class="col-md-12 clearfix">
        <div class="reports-title">
            Ежедневный отчет по сотрудникам
            <span class="pull-right history-small-help">Для скролла: "Наведите на таблицу и используйте Shift + прокрутка колесом мышки"</span>
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
                        <option value="0">за месяц</option>
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
                        <input type='text' class="assol-btn-style" id="de-daily-year-input" />
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
//                        minDate: '2016',
//                        maxDate: 'now',
                        showTodayButton: true
                    }).on('dp.change', function (e) {
                        // действия при изменении Года
                        setDaysList();
                        reloadEmployeesTable('year');
                    });

                    months.change(function () {
                        // действия при изменении Месяца
                        reloadEmployeesTable('month');
                        setDaysList();
                    });

                    days.change(function () {
                        // действия при изменении Дня
                        reloadEmployeesTable('day');
                    });
                });

                function reloadEmployeesTable(mode){
                    $.post(
                        '/reports/daily/employees',
                        {
                            day: $('#de-daily-day').val(),
                            month: $('#de-daily-month').val(),
                            year: $('#de-daily-year-input').val(),
                            mode: mode
                        },
                        function(data){
                            if(data !== ''){
                                $('#dailyEmployeeTable').html(data);
                            }
                        },
                        'html'
                    );
                }

                function setDaysList(){
                    // подставляем в Дни корректное количество дней для выбранного месяца
                    // selected = за месяц
                    $.post(
                        '/reports/daily/days',
                        {
                            year: $('#de-daily-year-input').val(),
                            month: $('#de-daily-month').val()
                        },
                        function(data){
                            if(data !== ''){
                                $('#de-daily-day').html(data);
                            }
                            else{
                                $('#de-daily-day').find("[selected='selected']").removeAttr("selected");
                                $('#de-daily-day').val('0');
                            }
                        },
                        'html'
                    );
                }
            </script>

        </div>
    </div>

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