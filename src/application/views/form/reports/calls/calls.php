<?php
/**
 * @var $employee
 */

// удалять может только Директор
$canRemove = (!empty($employee['UserRole']) && ($employee['UserRole'] == '10001'))
    ? true
    : false;
$cols = ($canRemove) ? 5 : 4;
$loader = '<tr><td colspan="' . $cols
    . '"><div id="callLoader"><img src="' . base_url('public/img/25.gif')
    . '" alt="Calls Loader"/></div></td></tr>';
?>

<style>
    #add_call,
    #reload_call {
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
    #callsTable {
        margin: 20px 0 30px;
    }
    .call-comment-text {
        width: 100%;
        height: 40px;
        overflow: hidden;
        cursor: pointer;
    }
    #callLoader {
        text-align: center;
    }
    .calls-no-data {
        text-align: center;
    }
    #callsTable .popover {
        max-width: 70%;
    }
    .refresh-rotate{
        -moz-transition: all 1s linear;
        -webkit-transition: all 1s linear;
        transition: all 1s linear;
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
        <div class="date-filter-block hide">
            <div class="form-group calendar-block">
                <label for="calls_year"> </label>
                <button class="btn btn-default" id="reload_call">
                    <span class="glyphicon glyphicon-refresh refresh-rotate"></span>
                    Обновить
                </button>
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
<?php
$table_class = $th_class = '';
$sortable = 0;
if($sortable){
    $table_class = 'tablesorter';
    $th_class = 'sortable';
}
?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped <?= $table_class; ?>" id="callsTable">
            <thead>
            <tr>
                <th class="<?= $th_class; ?>">Дата</th>
                <th class="<?= $th_class; ?>">Клиентка</th>
                <th class="<?= $th_class; ?>">Сотрудник</th>
                <th>Комментарий</th>
                <?php if($canRemove): ?>
                <th></th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
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
            clearCallForm();
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
            if(
                (customerId.length > 0) &&
                (customerName.length > 0)
            )
            {
                // сохраняем в БД
                $.post(
                    '/Reports_Calls/save/',
                    {
                        CustomerID: customerId,
                        CustomerName: customerName,
                        Comment: callComment
                    },
                    function (data) {
                        if(data.length > 0){
//                            $('#callsTable > tbody').prepend(data);
//                            setCallSortable(); // чего-то не срабатывает так сортировка после добавления нового звонка
                            loadCalls();
                            $('#addCallModal').modal('hide');
                            clearCallForm();
                        }
                    },
                    'html'
                );
            }
            else {
                // TODO: показываем в модальном окне ошибку
            }
        });

        // обновить таблицу
        var refresh_call_counter = 0;
        $('#reload_call').click(function (e) {
            e.preventDefault();
            refresh_call_counter += 360;
            $(this).find('.glyphicon.glyphicon-refresh').css('transform', 'rotate(' + refresh_call_counter + 'deg)');
            loadCalls();
        });
    });

    // загружаем таблицу с данными за выбранный месяц
    function loadCalls() {
        setCallLoader();
        var year = $('#calls_year_input').val();
        var month = $('#calls_month').val();
        $.post(
            '/Reports_Calls/data/',
            {
                year: year,
                month: month
            },
            function (data) {
                if(data.length > 0){
                    $('#callsTable > tbody').html(data);
                    setCallSortable();
                }
                else{
                    $('#callsTable > tbody').html('<tr><td colspan="<?= $cols; ?>" class="calls-no-data">Нет данных для отображения</td></tr>');
                }
            },
            'html'
        );
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

    // показываем лоадер вместо данных в таблице
    function setCallLoader() {
        $('#callsTable > tbody').html('<?= $loader; ?>');
    }

    // удаляем звонок
    function removeCall(id) {
        if(confirm('Вы уверены, что хотите удалить этот звонок?')) {
            $.post(
                '/Reports_Calls/remove/',
                {
                    ID: id
                },
                function (data) {
                    if (data * 1 > 0) {
                        $('#ctr_' + id).remove();
                    }
                },
                'text'
            );
        }
    }

    // очищаем форму
    function clearCallForm() {
        $('#addCallCustomerID').val('');
        $('#callCustomer').val('');
        $('#callComment').val('');
    }

    // сортировка для таблицы
    function setCallSortable() {
        $("#callsTable").tablesorter({
            selectorHeaders: 'thead th.sortable' // <-- здесь указываем класс, который определяет те столбцы, по которым будет работать сортировка
        });
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