<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="remoteDialogLabel">ДОБАВИТЬ КОНТАКТ</h4>
</div>
<div class="modal-body">
    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger" role="alert">
            <strong>Ошибка!</strong> <?= $errorMessage ?>
        </div>
    <?php endif; ?>

    <div class="service-block" style="padding-top: 0">
        <div class="service-block-settings clear">
            <div>
                <div class="form-group">
                    <label for="contactDate">Дата</label>
                    <div class="date-field">
                        <input type="text" class="assol-input-style" id="contactDate">
                    </div>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="contactSite">Сайт</label>
                    <div class="btn-group assol-select-dropdown" id="contactSite">
                        <div class="label-placement-wrap">
                            <button class="btn" data-label-placement=""><span class="data-label">Выбрать</span></button>
                        </div>
                        <button data-toggle="dropdown" class="btn dropdown-toggle">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach($sites as $item): ?>
                                <li>
                                    <input type="radio" id="Site_<?=$item['ID']?>" name="Site" value="<?=$item['ID']?>">
                                    <label for="Site_<?=$item['ID']?>"><?= empty($item['Name']) ? $item['Domen'] : $item['Name'] ?></label>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="contactUserTranslate">Переводчик</label>
                    <div class="btn-group assol-select-dropdown" id="contactUserTranslate">
                        <div class="label-placement-wrap">
                            <button class="btn" data-label-placement=""><span class="data-label">Выбрать</span></button>
                        </div>
                        <button data-toggle="dropdown" class="btn dropdown-toggle">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach($translators as $item): ?>
                                <li>
                                    <input type="radio" id="UserTranslate_<?=$item['ID']?>" name="UserTranslate" value="<?=$item['ID']?>">
                                    <label for="UserTranslate_<?=$item['ID']?>"><?= $item['SName'] ?> <?= $item['FName'] ?></label>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="contactMen">Мужчина</label>
                    <input type="text" class="assol-input-style" id="contactMen">
                </div>
            </div>
            <style>
                .action-append-customer{
                    display: block;
                }
            </style>
            <div class="user-id-field-wrap">
                <div class="form-group user-id-field" style="width: 100%; margin-left: 0;">
                    <label for="contactGirl">Девушка</label>
                    <input type="text" class="assol-input-style user-id-input"
                           id="contactGirl" <?= (!empty($CustomerName)) ? 'value="' . $CustomerName . '"' : ''; ?>>
                    <div class="user-id-tooltip"> <!-- Появляется на фокус поля, но можно єто и убрать.... -->
                        <div id="contactGirl_tg" class="tooltip-content">
                            <a href="javascript: void(0);" class="action-append-customer" id-customer="0">Введите ФИО или ID</a>
                        </div>
                        <div class="arrow"></div>
                    </div>
                </div>
            </div>
            <div style="width: 75%;">
                <div class="form-group">
                    <label for="contactDescription">Описание</label>
                    <input type="text" class="assol-input-style" id="contactDescription">
                </div>
            </div>
        </div>
        <div class="service-block-settings-btns">
            <button id="Savecontact" class="btn assol-btn save" title="Сохранить изменения">
                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                Сохранить
            </button>
        </div>
    </div>

    <div id="alertError" class="alert alert-danger" role="alert" style="display: none; margin-top: 20px">
        <h4>Ошибка!</h4>
        <p id="alertErrorMessage"></p>
    </div>
</div>

<script>
    function callback(data) {
        if (data.status) {
            $('#remoteDialog').modal('hide');
            getServiceContactsList();
        } else {
            showErrorAlert(data.message)
        }
    }

    $('#Savecontact').click(function () {
        $('#alertError').hide();

        var data = {
            date: toServerDate($('#contactDate').val()),
            site: $('#contactSite').find("input:radio:checked").val(),
            men: $('#contactMen').val(),
            girl: $('#contactGirl').val(),
            description: $('#contactDescription').val(),
            userTranslate: $('#contactUserTranslate').find("input:radio:checked").val()
        };

        $.post('<?= current_url() ?>', data, callback, 'json');
    });


    function showErrorAlert(message) {
        $('#alertErrorMessage').text(message);
        $('#alertError').slideDown();
    }
</script>