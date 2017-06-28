<div class="row">
    <div class="col-md-12 clearfix">
        <div class="reports-title">
            Ежедневный отчет
            <span class="pull-right history-small-help">Для скролла: "Наведите на таблицу и используйте Shift + прокрутка колесом мышки"</span>
        </div>
    </div>
</div>

<div class="panel assol-grey-panel">
    <div class="report-filter-wrap clear">

        <div class="date-filter-block">
            <div class="form-group">
                <label for="daily-day">Число</label>
                <select class="assol-btn-style" id="dr-daily-day">
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
                <select class="assol-btn-style" id="dr-daily-month">
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
                <div class='input-group date' id='dr-daily-year'>
                    <input type='text' class="assol-btn-style" id="dr-daily-year-input" />
                    <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                    <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                                </span>
                            </span>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row" style="margin-bottom: 50px;">
    <div class="col-md-12" id="dailyReportTable"></div>
</div>

<script>
    $(function() {
        var years = $('#dr-daily-year');
        var months = $('#dr-daily-month');
        var days = $('#dr-daily-day');

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
            setRDaysList();
            reloadReportTable('year');
        });

        months.change(function () {
            // действия при изменении Месяца
            reloadReportTable('month');
            setRDaysList();
        });

        days.change(function () {
            // действия при изменении Дня
            reloadReportTable('day');
        });

        // обработка выбора переводчика – фиксируем в сессии ID переводчика
        $(document).on('click', '.report-folder>a, .report-bread', function (e) {
            var mrecord = $(e.target).closest('[level]');
            var midEmployee = mrecord.attr('id-employee');
            var mpathLevel = parseInt(mrecord.attr('level'));
            if(midEmployee !== undefined){
                // передаём ID переводчика на сервер
                // и там сохраняем его в сессии
                $.post(
                    '/reports/daily/savetranslator',
                    {
                        id: midEmployee
                    }
                );
            }
            else if(mpathLevel == 1){
                // 1 - это level для данного отчета для Переводчика
                reloadReportTable('day'); // загрузка таблицы при первом запуске
            }
            if(mpathLevel == 21){
                // 21 - это level для данного отчета для Директора
                reloadReportTable('day'); // загрузка таблицы при первом запуске
            }
        });
    });

    function reloadReportTable(mode){
        var noData = '<h5 class="text-center">Нет данных для отображения</h5>';

        $.post(
            '/reports/daily/report',
            {
                day: $('#dr-daily-day').val(),
                month: $('#dr-daily-month').val(),
                year: $('#dr-daily-year-input').val(),
                mode: mode
            },
            function(data){
                if(data !== ''){
                    $('#dailyReportTable').html(data);
                }
                else{
                    $('#dailyReportTable').html(noData);
                }
            },
            'html'
        );
    }

    function setRDaysList(){
        // подставляем в Дни корректное количество дней для выбранного месяца
        // selected = за месяц
        $.post(
            '/reports/daily/days',
            {
                year: $('#dr-daily-year-input').val(),
                month: $('#dr-daily-month').val()
            },
            function(data){
                if(data !== ''){
                    $('#dr-daily-day').html(data);
                }
                else{
                    $('#dr-daily-day').find("[selected='selected']").removeAttr("selected");
                    $('#dr-daily-day').val('0');
                }
            },
            'html'
        );
    }
</script>