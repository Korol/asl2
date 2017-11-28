<?php
/**
 * @var $employee
 */

//var_dump($employee);
// удалять может только Директор
$canRemove = (!empty($employee['UserRole']) && ($employee['UserRole'] == '10001'))
    ? true
    : false;
?>

<style>
    #add_call {
        margin-top: 19px;
    }
    .add-btn-block {
        text-align: right;
        margin-right: 0 !important;
    }
    #callCustomer {
        width: 250px;
        height: 30px;
    }
    #callComment {
        width: 100%;
    }
</style>

<div class="reports-title">Звонки</div>

<div class="panel assol-grey-panel">
    <div class="report-filter-wrap clear">

        <div class="date-filter-block">
            <div class="form-group">
                <label for="calls_month">Месяц</label>
                <select class="assol-btn-style" id="calls_month">
                    <option value="1">Январь</option>
                    <option value="2">Февраль</option>
                    <option value="3">Март</option>
                    <option value="4">Апрель</option>
                    <option value="5">Май</option>
                    <option value="6">Июнь</option>
                    <option value="7">Июль</option>
                    <option value="8">Август</option>
                    <option value="9">Сентябрь</option>
                    <option value="10">Октябрь</option>
                    <option value="11">Ноябрь</option>
                    <option value="12">Декабрь</option>
                </select>
            </div>
        </div>
        <div class="date-filter-block">
            <div class="form-group calendar-block">
                <label for="calls_year">Год</label>
                <div class='input-group date' id='calls_year'>
                    <input type='text' class="assol-btn-style" id="calls_year_input" />
                    <span class="input-group-addon">
                        <span class="fa fa-calendar">
                            <img src="<?= base_url() ?>/public/img/calendar-icon.png" alt="">
                        </span>
                    </span>
                </div>
            </div>
        </div>
        <div class="date-filter-block pull-right add-btn-block">
            <div class="form-group calendar-block">
                <label for="add_call">&nbsp;</label>
                <button class="btn btn-default" id="add_call">Добавить звонок</button>
            </div>
        </div>

    </div>
</div>

<script>
    $(function() {
        // datepicker настройки
        var years = $('#calls_year');
        var months = $('#calls_month');
        years.datetimepicker({
            locale: 'ru',
            format: 'YYYY',
            viewMode: 'years',
            defaultDate: 'now',
            showTodayButton: true
        }).on('dp.change', function (e) {
            loadCalls();
        });
        months.change(function () {
            loadCalls();
        });
        months.find("[value='" + (moment().month()+1) + "']").attr("selected", "selected");

        // модальное окно добавления звонка
        $('#add_call').click(function () {
            // TODO: очистка формы от предыдущего добавления и ошибок
            $('#addCallModal').modal('show');
        });

        // автокомплит
        $(document).on("keyup", ".user-id-input", function (e) {
            delay(function(){
                var userID = $(e.target).val();
                var fieldID = e.target.id;

                findCallUser(userID, fieldID, 'user');
            }, 500);
        });

        // выбор клиентки из результатов автокомплита
        $(document).on("click", ".action-append-customer", function (e) {
            var userRole = $(e.target).attr('user-role');
            $('.'+userRole+'-id-input').val($(e.target).text());
            $('#addCallCustomerID').val($(e.target).attr('id-customer'));
        });

        // сохранение нового звонка
        $('#saveCall').click(function () {
            var customerId = $('#addCallCustomerID').val();
            var customerName = $('#callCustomer').val();
            var callComment = $('#callComment').val();
            console.log(customerId, customerName, callComment);
            if(
                (customerId.length > 0) &&
                (customerName.length > 0)
            )
            {
                // сохраняем в БД
                $.post(
                    'Reports_Calls/save/',
                    {
                        CustomerID: customerId,
                        CustomerName: customerName,
                        Comment: callComment
                    },
                    function (data) {
                        if(data*1 > 0){
                            $('#addCallModal').modal('hide');
                            loadCalls();
                        }
                    },
                    'text'
                );
            }
            else {
                // TODO: показываем в модальном окне ошибку
            }
        });
    });

    // загружаем таблицу с данными за выбранный месяц
    function loadCalls() {
        var year = $('#calls_year_input').val();
        var month = $('#calls_month').val();
        var canRemove = <?= (!empty($canRemove)) ? 1 : 0; ?>;

    }

    // ищем клиентку для автокомплита
    function findCallUser(userID, fieldID, usrRole) {
        var targetSelector2 = '#' + fieldID + '_tg';

        $(targetSelector2).html('Поиск...');

        if (userID) {
            var urlData = BaseUrl + 'services/find';

            $.post(
                urlData,
                {user : userID, role : usrRole},
                function (data) {
                    if (data.status) {
                        if (data.records){
                            $(targetSelector2).html('');
                            var items = data.records.map(function (item) {
                                return '<a href="javascript: void(0);" class="action-append-customer" user-role="'+usrRole+'" id-customer="'+item.id+'">'+item.name+'</a>';
                            });
                            $(targetSelector2).html(items.join('<br>')+'<br>');
                        } else {
                            $(targetSelector2).html('Нет данных...');
                        }
                    } else {
                        console.log(data.message);
                        $(targetSelector2).html('Нет данных...');
                    }
                },
                'json'
            );
        } else {
            $(targetSelector2).html('<a href="javascript: void(0);" class="action-append-customer" id-customer="0">Введите ФИО или ID</a>');
        }
    }
</script>

<!-- Add Modal -->
<div class="modal fade" id="addCallModal" tabindex="-1" role="dialog" aria-labelledby="addCallModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addCallModalLabel">Добавить звонок</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="addCallCustomerID" value="">
                <div class="user-id-field-wrap">
                    <div class="form-group user-id-field" style="width: 100%; margin-left: 0;">
                        <label for="callCustomer">Клиентка:</label><br>
                        <input type="text" class="assol-input-style user-id-input" id="callCustomer" placeholder="Введите ФИО или ID клиентки">
                        <div class="user-id-tooltip"> <!-- Появляется на фокус поля, но можно єто и убрать.... -->
                            <div id="callCustomer_tg" class="tooltip-content">
                                <a href="javascript: void(0);" class="action-append-customer" id-customer="0">Введите ФИО или ID</a>
                            </div>
                            <div class="arrow"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="callComment">Комментарий:</label><br>
                    <textarea class="assol-input-style" id="callComment" rows="6" placeholder="Комментарий к звонку"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="saveCall">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<!-- /Add Modal -->