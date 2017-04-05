<style>
    .chat-list {
        padding-left: 0;
    }

    .massage-main-block .massage-left-block .chat-list-wrap .chat-list .chat-user .chat-user-in {
        padding-left: 5px;
    }

    .chat-nums {
        background: #eded05;
        color: #77563D;
        padding: 2px 4px;
        font-size: 12px;
        text-align: center;
        border-radius: 20%;
        overflow: hidden;
        position: absolute;
        bottom: 0;
        right: 0;
        display: none;
        /*display: block;*/
    }
</style>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title" id="messageDialogLabel">СООБЩЕНИЯ</h4>
</div>

<div class="modal-body">
	<?php if (isset($errorMessage)): ?>
		<div class="alert alert-danger" role="alert">
			<strong>Ошибка!</strong> <?= $errorMessage ?>
		</div>
	<?php endif; ?>

    <div class="massage-main-block clear">
        <div class="massage-left-block assol-grey-panel">
            <div class="sidebar-search">
                <div class="search-block">
                    <input id="search-field" class="search-field" type="search" placeholder="поиск">
                    <button type="button" class="search-btn">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </div>
            </div>
            <div class="chat-list-wrap" id="header">
                <ul class="chat-list" id="employee-list">
                    <li class="chat-user" id-employee="0">
                        <a href="#" class="chat-user-in clear">
                            <div class="chat-user-img-wrap">
                                <div class="chat-user-img">
                                    <img src="<?=  base_url("public/img/chat.png") ?>" alt="">
                                </div>
                                <div class="online chat-user-status"></div>
                                <div class="chat-nums" id="ChatNum_0"></div>
                            </div>
                            <div class="chat-user-name">
                                Общий чат
                            </div>
                        </a>
                    </li>
                    <? foreach ($employees as $employee): ?>
                        <li class="chat-user" id-employee="<?= $employee['ID'] ?>">
                            <a href="#" class="chat-user-in clear">
                                <div class="chat-user-img-wrap">
                                    <div class="chat-user-img">
                                        <img src="<?= empty($employee['Avatar'])
                                            ? base_url("public/img/avatar.jpeg")
                                            : base_url("thumb/?id=".$employee['Avatar']) ?>" alt="">
                                    </div>
                                    <div class="online chat-user-status"></div>
                                    <div class="chat-nums" id="ChatNum_<?= $employee['ID'] ?>"></div>
                                </div>
                                <div class="chat-user-name">
                                    <?= $employee['FName'] ?><br><?= $employee['SName'] ?>
                                </div>
                            </a>
                        </li>
                    <? endforeach ?>
                </ul>
            </div>
        </div>
        <div class="massage-right-block">

            <style>
                .chat-select-user .chat-user-img-wrap .chat-user-img {
                    width: 39px;
                    height: 39px;
                    border-radius: 50%;
                    overflow: hidden;
                }

                .chat-select-user .chat-user-img-wrap .chat-user-img img {
                    width: 100%;
                    height: 100%;
                }

                .chat-select-user .chat-user-name {
                    font-size: 12px;
                    padding-left: 10px;
                }

                .chat-select-user {
                    min-height: 49px;
                    margin-bottom: 10px;
                    padding: 5px;
                }

                .massage-main-block .massage-right-block .main-chat-block {
                    height: 350px;
                }

                .chat-select {
                    width: 200px;
                    padding-left: 10px;
                }
            </style>

            <div class="chat-select-user assol-grey-panel">
                <table id="ChatSelectUserInfo" style="width: 100%">
                    <tr>
                        <td style="width: 45px">
                            <div class="chat-user-img-wrap">
                                <div class="chat-user-img">
                                    <img src="" alt="">
                                </div>
                                <div class="online chat-user-status"></div>
                            </div>
                        </td>
                        <td style="text-align: left">
                            <div class="chat-user-name"></div>
                        </td>
                        <td style="padding-right: 20px; text-align: right; text-decoration: underline">
                            <a class="action-load-history" href="#">ранее</a>
                        </td>
                    </tr>
                </table>
            </div>

            <script id="messageDateBlock_Template" type="text/x-jquery-tmpl">
                <div class="chat-date-block-wrap" chat-date="${date}">
                    <div class="chat-date">${date}</div>
                    <div class="chat-date-block"></div>
                </div>
            </script>

            <script id="messageBlock_Template" type="text/x-jquery-tmpl">
                <div class="chat-date-message-line {{if IsSender}} my-message {{/if}}" id="Message_${id}" id-employee="${employee}">
                    <div class="chat-date-message-user">
                        <a href="#" class="chat-user">
                            <div class="chat-user-img-wrap">
                                <div class="chat-user-img">
                                    {{if Avatar > 0}}
                                        <img src="<?= base_url('thumb') ?>/?id=${Avatar}" alt="">
                                    {{else}}
                                        <img src="<?= base_url("public/img/avatar.jpeg") ?>" alt="">
                                    {{/if}}
                                </div>
                                <div class="online chat-user-status"></div>
                            </div>
                        </a>
                    </div>
                    <div class="chat-date-message-text-wrap">
                        <div class="chat-date-message-text">
                            ${message}
                        </div>
                    </div>
                    <? if (IS_LOVE_STORY): ?>
                    <div class="message-time" title="${dateMoment.format('DD MMMM YYYY HH:mm:ss')}">
                        ${dateMoment.format('HH:mm')}
                    </div>
                    <? endif ?>

                </div>
            </script>

            <? if (IS_LOVE_STORY): ?>
            <style>
                .message-time {
                    float: right;
                    padding: 10px;
                    color: #6b89a1;
                }

                .chat-date-message-text-wrap {
                    width: 100%;
                }
            </style>
            <? endif ?>

            <div class="main-chat-block" id="MainChatBlock"></div>

            <div class="main-chat-settings-wrap">
                <div class="main-chat-settings assol-grey-panel">
                    <div class="main-chat-settings-in">

                        <div class="main-chat-user-settings" id="ChatUserSettings">
                            <div>
                                <input type="text" class="assol-input-style" id="TextSendMessage" placeholder="" value="">
                            </div>
                            <div>
                                <button class="btn assol-btn add" title="Отправить сообщение" id="BtnSendMessage">
                                    <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
                                </button>
                            </div>
                            <? if($role['isDirector'] && !IS_LOVE_STORY): ?>
                            <div>
                                <button class="btn assol-btn eye" title="" id="ShowEmployeeChat">
                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                </button>
                            </div>
                            <? endif ?>
                        </div>

                        <? if($role['isDirector']): ?>
                        <div class="director-show-block" id="DirectorShowChat" style="display: none;">
                            <div class="form-group">
                                <select class="assol-btn-style chat-select" id="InChatEmployee">
                                    <option value="0">Выбрать</option>
                                    <? foreach ($employees as $employee): ?>
                                        <option value="<?= $employee['ID'] ?>"><?= $employee['FName'] ?> <?= $employee['SName'] ?></option>
                                    <? endforeach ?>
                                </select>
                            </div>

                            <div>
                                <span class="glyphicon glyphicon-sort" aria-hidden="true"></span>
                            </div>

                            <div class="form-group">
                                <select class="assol-btn-style chat-select" id="OutChatEmployee">
                                    <option value="0">Выбрать</option>
                                    <? foreach ($employees as $employee): ?>
                                        <option value="<?= $employee['ID'] ?>"><?= $employee['FName'] ?> <?= $employee['SName'] ?></option>
                                    <? endforeach ?>
                                </select>
                            </div>
                            <div>
                                <button class="btn assol-btn remove" title="Очистить выбор" id="HideEmployeeChat">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                        <? endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="alertError" class="alert alert-danger" role="alert" style="display: none; margin-top: 20px">
        <h4>Ошибка!</h4>
        <p id="alertErrorMessage"></p>
    </div>
</div>

<script>

    // ID текущего пользователя
    var currentEmployeeID = <?= $user['ID'] ?>;
    var currentEmployeeAvatar = <?= $user['Avatar'] ?>;
    // Минимальное ID сообщения
    var minMessageID = Number.MAX_SAFE_INTEGER;
    var limitMessageChat = 50;

    // Информация о сотрудниках
    var employees = {
    <? foreach ($allEmployees as $employee): ?>
        <?= $employee['ID'] ?>: {
            fName: '<?= $employee['FName'] ?>',
            sName: '<?= $employee['SName'] ?>',
            avatar: <?= $employee['Avatar'] ?>},
    <? endforeach ?>
    };

    // Подготовка шаблонов
    $("#messageDateBlock_Template").template('messageDateBlock_Template');
    $("#messageBlock_Template").template('messageBlock_Template');

    // Таймер подгрузки оповещений о новых сообщениях и онлайне сотрудников
    $.TimerEventsMessage = {
        timer: null,
        start : function () {
            this.load();
            this.timer = setInterval(this.load, 2500);
        },
        stop: function () {
            clearTimeout(this.timer);
        },
        load: function () {
            function callback(data) {
                if (data.status) {
                    // Скрываем и очищаем
                    $('.chat-nums').hide().empty();

                    // Обновление информации о непрочитанных сообщениях
                    $.each(data.records.messages, function () {
                        if (this.count > 0)
                            $('#ChatNum_' + this.sender).html(this.count).show();
                    });

                    // Обновление информации об онлайне сотрудников
                    $('.chat-user-status').removeClass('online');
                    $.each(data.records.online, function () {
                        $('.chat-user[id-employee="'+this.id+'"] .chat-user-status').addClass('online');
                    });
                } else {
                    alert('Ошибка получения данных');
                }
            }

            $.post(BaseUrl + 'messages/events', {}, callback, 'json');
        }
    };

    // Таймер подгрузки новых сообщенией для открытого чата
    $.TimerLoadNewMessage = {
        timer: null,
        recipient: null,
        lastMessageID: 0,
        start : function (recipient, lastMessageID) {
            this.recipient = recipient;
            this.lastMessageID = lastMessageID;
            this.timer = setInterval(this.load, 5000);
        },
        stop: function () {
            clearTimeout(this.timer);
        },
        getRecipient: function () {return this.recipient},
        getLastMessageID: function () {return this.lastMessageID},
        load: function () {
            // Отключаем таймер в случае если выбран режим просмотра чужих сообщений
            <? if($role['isDirector']): ?>
            if (showEmployeeChatMode) {
                $.TimerLoadNewMessage.stop();
                return;
            }
            <? endif ?>

            function callback(data) {
                if (data.status) {
                    $.each(data.records, function () {
                        $.TimerLoadNewMessage.lastMessageID = this.id;

                        var Employee = $.TimerLoadNewMessage.getRecipient() > 0
                            ? $.TimerLoadNewMessage.getRecipient()
                            : this.sender;

                        var message = {
                            id: this.id,
                            employee: Employee,
                            dateMoment: moment(this.ts),
                            message: this.message,
                            IsSender: false,
                            Avatar: employees[Employee].avatar
                        };

                        printMessage(message);

                        var block = $('#MainChatBlock');
                        var height = block[0].scrollHeight;
                        block.animate({ scrollTop: height }, 'normal');
                    })
                } else {
                    alert('Ошибка получения данных');
                }
            }

            var data = {
                recipient: $.TimerLoadNewMessage.getRecipient(),
                lastMessageID: $.TimerLoadNewMessage.getLastMessageID()
            };

            $.post(BaseUrl + 'messages/now', data, callback, 'json');
        }
    };

    function updateUserChatInfo() {
        // Сбрасываем минимаольный ID сообщения для возможности подгрузки истории в новом окне
        minMessageID = Number.MAX_SAFE_INTEGER;

        var selectEmployee = $('.chat-user-name.current').closest('li');

        var recipient = selectEmployee.attr('id-employee');

        $('.chat-select-user').attr('id-employee', recipient);

        var urlAvatar = (recipient > 0)
            ? (employees[recipient].avatar > 0)
                ? '<?= base_url('thumb') ?>?id=' + employees[recipient].avatar
                : '<?= base_url("public/img/avatar.jpeg") ?>'
            : '<?= base_url("public/img/chat.png") ?>';

        $('.chat-select-user img').attr('src', urlAvatar);
        $('.chat-select-user .chat-user-name').html((recipient > 0)
            ? employees[recipient].fName + '<br>' + employees[recipient].sName
            : 'Общий чат'
        );

        $('#ChatSelectUserInfo').show();

        $('#TextSendMessage').val('');

        reloadUserChat(currentEmployeeID, recipient);

        // Запуск обновления информации о прочтение
        setTimeout(function () {
            $.post(BaseUrl + 'messages/read', {recipient: recipient});
        }, 3000);
    }

    function printMessage(message, isPrepend) {
        // Получаем дату сообщения
        var date = message.dateMoment.format('DD.MM.YYYY');
        // Флаг добавление в начало чата
        isPrepend = isPrepend || false;

        // Поиск блока сообщение за указанную дату
        var dateBlock = $('[chat-date="'+date+'"]');

        // Если блок не найден, то создаем его
        if (dateBlock.length == 0) {
            if (isPrepend) {
                $.tmpl('messageDateBlock_Template', {date: date}).prependTo('#MainChatBlock');
            } else {
                $.tmpl('messageDateBlock_Template', {date: date}).appendTo('#MainChatBlock');
            }
            dateBlock = $('[chat-date="'+date+'"]');
        }

        if (isPrepend) {
            $.tmpl('messageBlock_Template', message).prependTo(dateBlock.find('.chat-date-block'));
        } else {
            $.tmpl('messageBlock_Template', message).appendTo(dateBlock.find('.chat-date-block'));
        }
    }

    function reloadUserChat(sender, recipient) {
        // Останавливаем таймер подгрузки новых сообщений в чат
        $.TimerLoadNewMessage.stop();

        $('.main-chat-block').empty();

        function callback(data) {
            if (data.status) {
                var recipientLastMessageID = 0;

                // Обновение видимости кнопки подгрузки истории
                $('.action-load-history').toggle(data.records.length >= limitMessageChat);

                $.each(data.records, function () {
                    var IsChat = parseInt(recipient) == 0;
                    var IsSender = this.sender == sender;

                    if (!IsSender || IsChat)
                        recipientLastMessageID = parseInt(this.id);

                    // Обновление минимального ID сообщеия для возможности подгрузки сообщений
                    if (minMessageID > parseInt(this.id))
                        minMessageID = parseInt(this.id);

                    var messageEmployee = IsChat ? this.sender : parseInt(IsSender ? sender : recipient);

                    var message = {
                        id: this.id,
                        employee: messageEmployee,
                        dateMoment: moment(this.ts),
                        message: this.message,
                        IsSender: IsSender,
                        Avatar: employees[messageEmployee].avatar
                    };

                    printMessage(message);
                });

                var block = $('#MainChatBlock');
                var height = block[0].scrollHeight;
                block.scrollTop(height);

                // Инициализация и запуск таймера подгрузки новых сообщений в чат
                $.TimerLoadNewMessage.start(recipient, recipientLastMessageID);
            } else {
                alert('Ошибка получения данных');
            }
        }

        var data = {
            sender: sender,
            recipient: recipient,
            limit: limitMessageChat
        };

        $.post(BaseUrl + 'messages/data', data, callback, 'json');
    }

    function historyUserChat(sender, recipient) {
        function callback(data) {
            if (data.status) {
                // Обновение видимости кнопки подгрузки истории
                console.log(data.records.length);
                console.log(limitMessageChat);
                $('.action-load-history').toggle(data.records.length >= limitMessageChat);

                $.each(data.records, function () {
                    var IsChat = parseInt(recipient) == 0;
                    var IsSender = this.sender == sender;

                    // Обновление минимального ID сообщеия для возможности подгрузки сообщений
                    if (minMessageID > parseInt(this.id))
                        minMessageID = parseInt(this.id);

                    var messageEmployee = IsChat ? this.sender : parseInt(IsSender ? sender : recipient);

                    var message = {
                        id: this.id,
                        employee: messageEmployee,
                        dateMoment: moment(this.ts),
                        message: this.message,
                        IsSender: IsSender,
                        Avatar: employees[messageEmployee].avatar
                    };

                    printMessage(message, true);
                });

//                var block = $('#MainChatBlock');
//                var height = block[0].scrollHeight;
//                block.scrollTop(height);
            } else {
                alert('Ошибка получения данных');
            }
        }

        var data = {
            sender: sender,
            recipient: recipient,
            min: minMessageID,
            limit: limitMessageChat
        };

        $.post(BaseUrl + 'messages/history', data, callback, 'json');
    }

    $(document).on('click', '.action-load-history', function () {
        var selectEmployeeID = parseInt($('.chat-select-user').attr('id-employee'));
        historyUserChat(currentEmployeeID, selectEmployeeID);
    });

    $(document).on('click', '.chat-list li', function () {
        <? if($role['isDirector']): ?>
        $('#HideEmployeeChat').click(); // Скрываем режим просмотра чужих сообщений
        <? endif ?>

        $('.chat-list .chat-user-name').removeClass('current');
        $(this).find('.chat-user-name').addClass('current');

        updateUserChatInfo();
    });

    (function ($) {
        // custom css expression for a case-insensitive contains()
        jQuery.expr[':'].Contains = function(a,i,m){
            return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
        };

        function listFilter(input, list) {
            $(input)
                .change( function () {
                    var filter = $(this).val();
                    if(filter) {
                        $(list).find("a:not(:Contains(" + filter + "))").parent().slideUp();
                        $(list).find("a:Contains(" + filter + ")").parent().slideDown();
                    } else {
                        $(list).find("li").slideDown();
                    }
                    return false;
                })
                .keyup( function () {
                    $(this).change();
                });
        }

        $(function () {
            listFilter($('#search-field'), $("#employee-list"));
        });
    }(jQuery));

    function sendMessage(IsSetPrefix) {
        IsSetPrefix = IsSetPrefix || false;

        var selectEmployeeID = $('.chat-select-user').attr('id-employee');
        var input = $('#TextSendMessage');
        var text = $.trim(input.val());

        if (text && selectEmployeeID) {
            function callback(data) {
                if (data.status) {
                    var message = {
                        id: data.id,
                        employee: currentEmployeeID,
                        dateMoment: moment(),
                        message: text,
                        IsSender: true,
                        Avatar: employees[currentEmployeeID].avatar
                    };

                    printMessage(message);

                    var block = $('#MainChatBlock');
                    var height = block[0].scrollHeight;
                    block.animate({ scrollTop: height }, 'normal');

                    input.val('');

                    if (IsSetPrefix) {
                        setNamePrefix();
                    }
                } else {
                    alert('Ошибка отправки сообщения');
                }
            }

            var data = {
                sender: currentEmployeeID,
                recipient: parseInt(selectEmployeeID),
                message: text
            };

            $.post(BaseUrl + 'messages/send', data, callback, 'json');
        }
    }

    var currentPrefix = '';

    function setNamePrefix() {
        var selectEmployeeID = $('.chat-select-user').attr('id-employee');
        if (selectEmployeeID > 0) {
            currentPrefix = employees[selectEmployeeID].fName + ", ";
            $('#TextSendMessage').val(currentPrefix);
        }
    }

    if (IsLoveStory) {
        // Установка префикса при клике на текстовое поле
        $('#TextSendMessage').click(setNamePrefix);

        // Установка префикса в общем чате по нажатию аватара пользователя
        $(document).on('click', '.chat-user-img img', function() {
            // Если это общий чат
            if ($('.chat-select-user').attr('id-employee') == 0) {
                var selectEmployeeID = $(this).closest('[id-employee]').attr('id-employee');
                var input = $('#TextSendMessage');

                // если выбран пользователь и тест сообщения пустой или равен префиксу, то меняем его на новый префикс
                if (((selectEmployeeID > 0) && (selectEmployeeID != currentEmployeeID)) && ((input.val().trim().length == 0) || (input.val() == currentPrefix))) {
                    currentPrefix = employees[selectEmployeeID].fName + ", ";
                    input.val(currentPrefix);
                }
            }
        });
    }

    $('#BtnSendMessage').click(sendMessage);
    $('#TextSendMessage').keyup(function (event) {
        if (/*event.ctrlKey && */event.which == 13) {
            sendMessage(IsLoveStory);
        }
    });

    $('body')
        .on('shown.bs.modal', '.messageDialog', function () {
            <? if($role['isDirector']): ?>
            $('#HideEmployeeChat').click(); // Скрываем режим просмотра чужих сообщений
            <? endif ?>

            clearSelectUserChat();

            // Запуск таймера оповещения о новых сообщениях
            $.TimerEventsMessage.start();
        })
        .on('hidden.bs.modal', '.messageDialog', function () {
            // Остановка таймера оповещения о новых сообщениях
            $.TimerEventsMessage.stop();
        });

    function clearSelectUserChat() {
        // Снимаем выделение сотрудника
        $('.chat-list .chat-user-name').removeClass('current');
        // Скрываем шапку с инфой о выбранном сотруднике
        $('#ChatSelectUserInfo').hide();
        // Очищаем окно чата
        $('.main-chat-block').empty();
    }

<? if($role['isDirector']): ?>

    var showEmployeeChatMode = false;

    $('.chat-select').change(function () {
        var sender = $('#InChatEmployee').val();
        var recipient = $('#OutChatEmployee').val();

        if ((sender > 0) && (recipient > 0)) {
            reloadUserChat(sender, recipient);
        }
    });

    $('#ShowEmployeeChat').click(function () {
        clearSelectUserChat();

        $('#ChatUserSettings').hide();
        $('#DirectorShowChat').show();
        showEmployeeChatMode = true;
    });

    $('#HideEmployeeChat').click(function () {
        clearSelectUserChat();

        $('#ChatUserSettings').show();
        $('#DirectorShowChat').hide();
        showEmployeeChatMode = false;
    });

<? endif ?>
</script>