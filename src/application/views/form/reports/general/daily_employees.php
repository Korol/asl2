<?php

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
            <!--                New-->
            <div class="date-filter-block">
                <div class="form-group calendar-block">
                    <label for="e-daily-from">С</label>

                    <div class='input-group date' id='e-daily-from'>
                        <input type='text' class="assol-btn-style" id="e_daily_from_input" />
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
                    <label for="e-daily-to">До</label>

                    <div class='input-group date' id='e-daily-to'>
                        <input type='text' class="assol-btn-style" id="e_daily_to_input" />
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
            </style>
            <div class="date-filter-block">
                <div class="form-group calendar-block">
                    <label for="daily-to">&nbsp;</label>

                    <div class='input-group date'>
                        <a href="#" class="btn btn-default" id="daily_employee_refresh">
                            <span class="glyphicon glyphicon-refresh refresh-rotate" aria-hidden="true"></span>
                            &nbsp;Обновить
                        </a>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                $(function() {
                    var daily_from = $('#e-daily-from');
                    var daily_to = $('#e-daily-to');

                    daily_from.datetimepicker({
                        locale: 'ru',
                        format: 'DD-MM-YYYY',
                        viewMode: 'days',
                        defaultDate: 'now',
                        showTodayButton: true
                    }).on('dp.change', function (e) {
                        reloadEmployeesTable();
                    });

                    daily_to.datetimepicker({
                        locale: 'ru',
                        format: 'DD-MM-YYYY',
                        viewMode: 'days',
                        defaultDate: 'now',
                        showTodayButton: true
                    }).on('dp.change', function (e) {
                        reloadEmployeesTable();
                    });

                    var refresh_de_counter = 0;
                    $('#daily_employee_refresh').click(function (e) {
                        e.preventDefault();
                        refresh_de_counter += 360;
                        $(this).find('.glyphicon.glyphicon-refresh').css('transform', 'rotate(' + refresh_de_counter + 'deg)');
//                            $(this).find('.glyphicon.glyphicon-refresh').toggleClass('refresh-down');
                        reloadEmployeesTable();
                    });
                });

                function reloadEmployeesTable(){
                    $.post(
                        '/reports/daily/employees',
                        {
                            from: $('#e_daily_from_input').val(),
                            to: $('#e_daily_to_input').val()
                        },
                        function(data){
                            if(data !== ''){
                                $('#dailyEmployeeTable').html(data);
                            }
                        },
                        'html'
                    );
                }
            </script>
            <!--                /New-->
        </div>
    </div>

<div class="row" style="margin-bottom: 50px;">
    <div class="col-md-12" id="dailyEmployeeTable">
        <?php $this->load->view('form/reports/general/de_table', array(
            'sites' => $sites,
            'translators' => $translators,
            'daily_reports' => $daily_reports,
            'title' => 'Показан отчет за сегодня, ' . date('d-m-Y'),
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