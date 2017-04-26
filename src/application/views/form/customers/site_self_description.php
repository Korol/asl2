<?php
// Статусы ssd:
// 0 - default
// 1 – в очереди
// 2 - на утверждение
// 3 - подтверждено
// Пользователь: $this->user
// изменять Ответственного сотрудника может только Директор
//$customer['ssdStatus'] = 3; // test
$ssdResponsibleStaffMode = (($this->user['role'] == '10001')) ? '' : 'disabled="disabled"';
$ssdRSCommentDisplay = false;
$ssdRSCommentMode = 'readonly="readonly"';

// показываем поле «Комментарий ответственному» ответственному сотруднику
// после подтверждения анкеты Директором (ssdStatus = 3)
if(($customer['ssdStatus'] == 3) && ($this->user['ID'] == $customer['ssdResponsibleStaff'])){
    $ssdRSCommentDisplay = true;
}
?>
<style>
    .ssd-header-block{
        margin-top: 40px; margin-bottom: 15px;
    }
    .ssd-header-bline{
        width: 100%; border-bottom: 1px solid #ddd;
    }
    .ssd-form-block{
        margin-top: 30px;
    }
    .ssd-block{
        /*display: none;*/
    }
</style>
<div class="row ssd-header-block ssd-block">
    <div class="col-md-12">
        <h4>Самоописание на сайт:</h4>
        <div class="ssd-header-bline"></div>
    </div>
</div>
<?php
// открываем поля для заполнения сотруднику, который назначен ответственным за эту анкету
if(($customer['ssdStatus'] == 1) && ($this->user['ID'] == $customer['ssdResponsibleStaff'])):
?>
    <script>
        $(function () {
            $('.ssd-ta').removeAttr('disabled');
        });
    </script>
<?php endif; ?>
<div class="row ssd-form-block ssd-block">
    <div class="col-md-12">
        <div class="form-group">
            <label for="ssdCharacter">Характер:</label>
            <textarea class="form-control ssd-ta assol-input-style" name="ssdCharacter" id="ssdCharacter" cols="30" rows="5"><?=$customer['ssdCharacter']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="ssdHobbies">Интересы:</label>
            <textarea class="form-control ssd-ta assol-input-style" name="ssdHobbies" id="ssdHobbies" cols="30" rows="5"><?=$customer['ssdHobbies']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="ssdWishingForPartner">Пожелание к партнеру:</label>
            <textarea class="form-control ssd-ta assol-input-style" name="ssdWishingForPartner" id="ssdWishingForPartner" cols="30" rows="5"><?=$customer['ssdWishingForPartner']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="ssdPresentationLetter">Презентационное письмо:</label>
            <textarea class="form-control ssd-ta assol-input-style" name="ssdPresentationLetter" id="ssdPresentationLetter" cols="30" rows="5"><?=$customer['ssdPresentationLetter']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="ssdMailingList1">Рассылочное письмо 1:</label>
            <textarea class="form-control ssd-ta assol-input-style" name="ssdMailingList1" id="ssdMailingList1" cols="30" rows="5"><?=$customer['ssdMailingList1']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="ssdMailingList2">Рассылочное письмо 2:</label>
            <textarea class="form-control ssd-ta assol-input-style" name="ssdMailingList" id="ssdMailingList2" cols="30" rows="5"><?=$customer['ssdMailingList2']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="ssdMailingList3">Рассылочное письмо 3:</label>
            <textarea class="form-control ssd-ta assol-input-style" name="ssdMailingList3" id="ssdMailingList3" cols="30" rows="5"><?=$customer['ssdMailingList3']; ?></textarea>
        </div>
    </div>
</div>
<div class="row ssd-block">
    <div class="col-md-4">
        <div class="form-group">
            <label for="Forming">Ответственный сотрудник:</label>
            <div class="btn-group assol-select-dropdown" id="ssdResponsibleStaff">
                <div class="label-placement-wrap">
                    <button <?= $ssdResponsibleStaffMode; ?> class="btn" data-label-placement>Выбрать</button>
                </div>
                <button data-toggle="dropdown" <?= $ssdResponsibleStaffMode; ?> class="btn dropdown-toggle">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <input type="radio" id="ssdResponsibleStaff_0" name="ssdResponsibleStaff" value="0">
                        <label for="ssdResponsibleStaff_0">
                            <span class="data-label">Выбрать</span>
                        </label>
                    </li>
                    <?php foreach($ssdStaffs as $item): ?>
                        <?php $isSelected = (isset($customer['ssdResponsibleStaff'])) ? ($item['ID']== $customer['ssdResponsibleStaff']) : false; ?>
                        <li>
                            <input type="radio" id="ssdResponsibleStaff_<?=$item['ID']?>" name="ssdResponsibleStaff" <?= $isSelected ? 'checked="checked"':'' ?> value="<?=$item['ID']?>">
                            <label for="ssdResponsibleStaff_<?=$item['ID']?>">
                                <span class="data-label"><?=$item['SName'] . ' ' . $item['FName']; ?></span>
                            </label>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8" style="padding-top: 20px;">
        <?php
        // новая анкета – Директор назначает ответственного Сотрудника для её заполнения
        if(($customer['ssdStatus'] == 0) && ($this->user['role'] == '10001')):
        ?>
        <div class="form-group clearfix">
            <button id="SaveSiteSelfDescription" class="btn assol-btn save pull-right" title="Отправить изменения">
                Отправить
            </button>
        </div>
        <?php
        // Сотрудник получил задачу, заполнил поля – и отправляет информацию на утверждение Директору
        elseif(($customer['ssdStatus'] == 1) && ($this->user['ID'] == $customer['ssdResponsibleStaff'])):
            $ssdRSCommentDisplay = (!empty($customer['ssdRSComment'])) ? true : false;
        ?>
        <div class="form-group clearfix">
            <button id="SaveSiteSelfDescription1" record="<?= $customer['ID']; ?>" class="btn assol-btn save pull-right" title="Отправить изменения">
                На утверждение
            </button>
        </div>
        <?php
        // Директор получил заполненные поля анкеты, проверил – и либо Подтверждает (данные верны),
        // либо пишет обязательный(!) комментарий Ответственному Сотруднику и отправляет ему анкету обратно на доработку
        // при этом ssdStatus меняем обратно на 1
        elseif(($customer['ssdStatus'] == 2) && ($this->user['role'] == '10001')):
            $ssdRSCommentDisplay = true;
            $ssdRSCommentMode = '';
        ?>
        <div class="form-group clearfix">
            <button id="SaveSiteSelfDescription2" record="<?= $customer['ID']; ?>" class="btn assol-btn save pull-right" title="Отправить изменения">
                Подтвердить
            </button>
            <button id="SaveSiteSelfDescription3" record="<?= $customer['ID']; ?>" class="btn assol-btn remove pull-right" title="Отправить изменения" style="margin-right: 20px;">
                Назад в доработку
            </button>
        </div>
        <?php
        // Директор подтвердил корректность анкеты
        // при этом у него есть возможность вернуть анкету назад в доработку (ssdStatus = 1)
        // а также назначить нового Ответственного сотрудника
        elseif(($customer['ssdStatus'] == 3) && ($this->user['role'] == '10001')):
            $ssdRSCommentDisplay = true;
            $ssdRSCommentMode = '';
        ?>
        <div class="form-group clearfix">
            <button id="SaveSiteSelfDescription3" record="<?= $customer['ID']; ?>" class="btn assol-btn remove pull-right" title="Отправить изменения">
                Назад в доработку
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php if(!empty($ssdRSCommentDisplay)): ?>
<div class="row ssd-block">
    <div class="col-md-12">
        <div class="form-group">
            <label for="ssdRSComment">Комментарий ответственному:</label>
            <textarea <?= $ssdRSCommentMode; ?> class="form-control assol-input-style" name="ssdRSComment" id="ssdRSComment" cols="30" rows="5"><?=$customer['ssdRSComment']; ?></textarea>
        </div>
    </div>
</div>
<?php endif; ?>