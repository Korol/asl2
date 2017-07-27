$(document).ready(function(){
    "use strict";

    // Объект для публичного использования
    $.AssolCalendar = {
        /** Инициализация объекта */
        Init: function() {
            this.InitActions();
            this.InitDynamicData();
            this.InitTemplate();
            this.InitCalendar();
        },
        /** Инициализация событий */
        InitActions: function() {
            $('#btnAddCalendarEvent').click(this.SaveCalendarEvent);
            $('#btnShowDayReport').click(this.ShowReportDay);
            $('#btnSaveDayReport').click(this.SaveReportDay);
            $('#AllDayCheckbox').click(this.UpdateEventTimeFormat);
        },
        /** Инициализация динамичных данных */
        InitDynamicData: function() {

        },
        /** Предварительная компиляция шаблонов */
        InitTemplate: function() {
            $("#reportTemplate").template('reportTemplate');
        },
        InitCalendar: function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                select: function(start, end) {
                    $.AssolCalendar.DialogCalendarEvent(start, end);
                },
                eventClick: function(calEvent, jsEvent, view) {
                    if(calEvent.uid){
                        if(calEvent.utype == 'customer'){
                            var win = window.open(BaseUrl + 'customer/' + calEvent.uid + '/profile', '_blank');
                            win.focus();
                        }
                        else if(calEvent.utype == 'employee'){
                            var win = window.open(BaseUrl + 'employee/' + calEvent.uid + '/profile', '_blank');
                            win.focus();
                        }
                    }
                    else{
                        $.AssolCalendar.DialogCalendarEvent(
                            calEvent.start, calEvent.end, calEvent.id, calEvent.title,
                            calEvent.description, calEvent.remind, true,
                            (jQuery.inArray('action-birthday', calEvent.className) > -1),
                            calEvent.forall
                        );
                    }
                },
                selectable: true,
                columnFormat: 'dddd',
                eventLimit: true, // allow "more" link when too many events
                slotLabelFormat: 'H:mm',
                events: {
                    url: BaseUrl + 'calendar/data',
                    beforeSend: function() {
                        hideAlerts();
                    },
                    success: function(data) {
                        if (data.error)
                            showErrorAlert('Ошибка загрузки событий: ' + data.message);
                    },
                    error: function() {
                        showErrorAlert('Ошибка загрузки событий!');
                    }
                }
            });
        },
        /** Добавление метки завершения события */
        DoneEvent: function(id) {
            bootbox.confirm("Поставить метку выполнено для события?", function(result) {
                function callback() {
                    $('#ShowCalendarEvent').modal('hide');
                    $('#calendar').fullCalendar('refetchEvents');
                }

                if (result) {
                    $.post(BaseUrl + 'calendar/done', {id: id}, callback, 'json');
                }
            });
        },
        /** Добавление нового события */
        SaveCalendarEvent: function() {
            hideAlerts();

            var data = {
                id: $('#AddCalendarEventID').val(),
                title: $('#AddCalendarEventTitle').val(),
                remind: $('#Remind').find("input:checked").val(),
                description: $('#AddCalendarEventDescription').val()
            };

            if($('#AllDayCheckbox').prop('checked')) {
                data['start'] = $('#event-start').data("DateTimePicker").date().format('YYYY-MM-DD') + ' 00:00:00';
                data['end'] = $('#event-end').data("DateTimePicker").date().format('YYYY-MM-DD') + ' 23:59:59';
            } else {
                data['start'] = $('#event-start').data("DateTimePicker").date().format('YYYY-MM-DD HH:mm');
                data['end'] = $('#event-end').data("DateTimePicker").date().format('YYYY-MM-DD HH:mm');
            }

            // checkbox «видят все сотрудники»
            if($('#ForAllCheckbox').prop('checked')){
                data['forall'] = 1;
            }
            else{
                data['forall'] = 0;
            }

            function callback(data) {
                if (data.status) {
                    $('#AddCalendarEvent').modal('hide');
                    $('#calendar').fullCalendar('refetchEvents');
                    updateAllEventsList(); // обновляем информацию об общих событиях в блоке «События дня»
                } else {
                    alert(data.message)
                }
            }

            $.post(BaseUrl + 'calendar/save', data, callback, 'json');
        },
        /** Отображение отчетной формы */
        ShowReportDay: function() {
            $('#btnShowDayReport').hide();
            $('#formDayReport').slideDown("slow", function() {
                $('#reportItems').html('<tr><td>Загрузка данных...</td></tr>');

                function callback(data) {
                    $('#reportItems').empty();
                    $.tmpl('reportTemplate', data).appendTo('#reportItems');
                    $('html, body').animate({
                        scrollTop: $("#formDayReport").offset().top
                    }, 1000);
                }

                $.getJSON(BaseUrl + 'calendar/data', callback);
            });
        },
        /** Сохранение отчетной формы */
        SaveReportDay: function() {
            function callback() {
                $('#formDayReport').slideUp("slow", function() {
                    $('#btnShowDayReport').show();
                });
            }

            var data = {};

            $('#formDayReport').find('input').each(function() {
                data[$(this).attr('id-event')] = $(this).val();
            });

            $.post(BaseUrl + 'calendar/report', {data: data}, callback, 'json');
        },
        DialogCalendarEvent: function(start, end, id, title, description, remind, readonly, isBirthday, forall) {
            if (id) {
                end = end || start;
            } else { // Если это новое событие
                // Если указано окончание, то приводим к красивому виду: 07.03.2016 00:00:00 => 06.03.2016 23:59:59
                if (end) {
                    end.subtract(1, 'seconds');
                } else {
                    end = start;
                }
            }

            var isAllDay = $.AssolCalendar.IsAllDay(start, end);
            remind = remind || 0;

            if (readonly) {
                // 1. Заполнение полей формы
                var format = isAllDay ? 'DD.MM.YYYY' : 'DD.MM.YYYY HH:mm';

                $('#ShowCalendarEventLabel').html(title);
                $('#showTaskDescription').html(description);
                $('#showEventStart').html(start.format(format));
                $('#showEventEnd').html(end.format(format));
                $('#showRemind').html($('label[for="Remind_' + remind + '"]').html());
                $('#showRemind').closest('tr').css('display', (remind > 0) ? 'table-row' : 'none');
                $('#showAllEvent').html((forall > 0) ? 'Да' : 'Нет');

                // 2. Подключение событий к кнопкам
                $('#btnEditEvent')
                    .off('click.edit-event')
                    .on('click.edit-event', function() {
                        $('#ShowCalendarEvent').modal('hide');
                        $.AssolCalendar.DialogCalendarEvent(start, end, id, title, description, remind, false, false, forall);
                    });

                checkCompleted(id); // для выполненных задач меняем кнопки

                if(forall*1 > 0){
                    // для общих событий – показываем создателю только 2 кнопки:
                    // Удалить и Редактировать
                    checkAllEvents(id);
                }

                // 3. Отображение формы с отключением части формы для дней рождения
                $('#ShowCalendarEvent')
                    .modal('show')
                    .find('.change-task-description-wrap, .save-edit-wrap')
                        .css('display', isBirthday ? 'none' : 'block');


            } else {
                $('#AddCalendarEventID').val(id);
                $('#AddCalendarEventLabel').html(id ? 'РЕДАКТИРОВАНИЕ СОБЫТИЯ' : 'НОВОЕ СОБЫТИЕ');
                $('#AddCalendarEventTitle').val(title);
                $('#AddCalendarEventDescription').val(description);
                $('#event-start').data("DateTimePicker").date(start);
                $('#event-end').data("DateTimePicker").date(end);
                $('#AllDayCheckbox').prop('checked', isAllDay);
                $('#ForAllCheckbox').prop('checked', forall>0 ? true : false);
                $('#Remind_' + remind).click();

                $.AssolCalendar.UpdateEventTimeFormat();

                $('#AddCalendarEvent').modal('show');
            }
        },
        /**
         * @return {boolean}
         */
        IsAllDay: function(start, end) {
            var isStartDay = $.AssolCalendar.IsStartDay(start);
            var isEndDay =  $.AssolCalendar.IsEndDay(end) || $.AssolCalendar.IsStartDay(end);  // 23:59:59 || 00:00:00

            return isStartDay && isEndDay;
        },
        /**
         * @return {boolean} <code>true</code> если время 00:00:00
         */
        IsStartDay: function(m) {
            return !(m.hour() || m.minute() || m.seconds());
        },
        /**
         * @return {boolean} <code>true</code> если время 23:59:59
         */
        IsEndDay: function(m) {
            return (m.hour() == 23) && (m.minute() == 59) && (m.seconds() == 59);
        },
        UpdateEventTimeFormat: function() {
            var format = $('#AllDayCheckbox').prop('checked') ? 'DD.MM.YYYY' : 'DD.MM.YYYY HH:mm';

            var dpStart = $('#event-start').data("DateTimePicker");
            var dpEnd = $('#event-end').data("DateTimePicker");

            if($('#AllDayCheckbox').prop('checked')) {
                dpStart.format(format);
                dpEnd.format(format);
            } else {
                var mStart = moment(dpStart.date());
                var mEnd = moment(dpEnd.date());

                // Если дата старта и окончания совподают и это начало суток
                if (mStart.isSame(mEnd) && $.AssolCalendar.IsStartDay(mStart)) {
                    dpEnd.date(mEnd.hours(23).minute(59).seconds(59)); // приводим дату окончания к 23:59:59
                }

                dpStart.format(format);
                dpEnd.format(format);
            }
        }
    };

    //var mcompleted = 0;
    // Инициализация объекта
    $.AssolCalendar.Init();

    function showErrorAlert(message) {
        $('#alertErrorMessage').text(message);
        $('#alertError').slideDown();
    }
    function hideAlerts() {
        $('#alertError').hide();
    }
    function checkCompleted(id) {
        $.post(
            BaseUrl + 'calendar/completed',
            { id: id },
            function(data){
                if(data*1 === 1){
                    // задача завершена
                    // скрываем кнопку Выполнено
                    $('#btnDoneEvent').addClass('hide');
                    // показываем кнопки Восстановить и Удалить
                    $('#btnRestoreEvent').removeClass('hide');
                    $('#btnRemoveEvent').removeClass('hide');
                    // Восстанавливаем событие
                    $('#btnRestoreEvent')
                        .off('click.restore-event')
                        .on('click.restore-event', function() {
                            bootbox.confirm("Снять метку «Выполнено» для этого события?", function(result) {
                                if(result){
                                    $.post(
                                        BaseUrl + 'calendar/restore',
                                        { id: id },
                                        function (data) {
                                            if (data * 1 === 1) {
                                                $('#ShowCalendarEvent').modal('hide');
                                                $('#calendar').fullCalendar('refetchEvents');
                                            }
                                            else{
                                                console.log(data);
                                            }
                                        },
                                        'text'
                                    );
                                }
                            });
                        });
                    // Удаляем событие
                    $('#btnRemoveEvent')
                        .off('click.remove-event')
                        .on('click.remove-event', function() {
                            bootbox.confirm("Удалить это событие безвозвратно?", function(result) {
                                if(result){
                                    $.post(
                                        BaseUrl + 'calendar/remove',
                                        { id: id },
                                        function (data) {
                                            if (data * 1 === 1) {
                                                $('#ShowCalendarEvent').modal('hide');
                                                $('#calendar').fullCalendar('refetchEvents');
                                            }
                                            else{
                                                console.log(data);
                                            }
                                        },
                                        'text'
                                    );
                                }
                            });
                        });
                }
                else{
                    // задача не завершена
                    // скрываем кнопки Восстановить и Удалить
                    $('#btnRestoreEvent').addClass('hide');
                    $('#btnRemoveEvent').addClass('hide');
                    // показываем кнопку Выполнено
                    $('#btnDoneEvent').removeClass('hide');
                    $('#btnDoneEvent')
                        .off('click.done-event')
                        .on('click.done-event', function() {
                            $.AssolCalendar.DoneEvent(id);
                        });
                }
            },
            'text'
        );
    }

    /*
    обновляем информацию об общих событиях в блоке «События дня»
     */
    function updateAllEventsList() {
        $.post(
            '/calendar/allevents',
            function (data) {
                if(data !== ''){
                    $('#AllEventsList').html(data);
                }
            },
            'html'
        );
    }

    /*
     для общих событий – показываем создателю только 2 кнопки: Удалить и Редактировать
     */
    function checkAllEvents(id) {
        $.post(
            '/calendar/checkalluser',
            { ID: id },
            function(data){
                if(data === 'y'){
                    // скрываем кнопки Выполнено и Восстановить
                    $('#btnDoneEvent').addClass('hide');
                    $('#btnRestoreEvent').addClass('hide');
                    // показываем кнопку Удалить
                    $('#btnRemoveEvent').removeClass('hide');
                    // Удаляем событие
                    $('#btnRemoveEvent')
                        .off('click.remove-event')
                        .on('click.remove-event', function() {
                            bootbox.confirm("Удалить это событие безвозвратно?", function(result) {
                                if(result){
                                    $.post(
                                        BaseUrl + 'calendar/remove',
                                        { id: id },
                                        function (data) {
                                            if (data * 1 === 1) {
                                                $('#ShowCalendarEvent').modal('hide');
                                                $('#calendar').fullCalendar('refetchEvents');
                                                updateAllEventsList();  // обновляем информацию об общих событиях в блоке «События дня»
                                            }
                                            else{
                                                console.log(data);
                                            }
                                        },
                                        'text'
                                    );
                                }
                            });
                        });
                }
            },
            'text'
        );
    }
});