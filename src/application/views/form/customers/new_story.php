<?php if(!IS_LOVE_STORY): ?>
<?php
$md_sites = array();
if(!empty($sites)){
    foreach($sites as $msite){
        $md_sites[$msite['ID']] = $msite;
    }
}
?>
<style>
    .md-table{
        font-size: 12px;
    }
    .md-additional img {
        border-radius: 25px;
        width: 50px;
        height: 50px;
    }
    .md-additional{
        min-width: 150px;
    }
    .md-small-header{
        font-size: 14px;
        font-weight: normal;
    }
</style>
<?php if(!empty($meetings)): ?>
    <div class="row">
        <div class="col-lg-12">
            <h4>ВСТРЕЧИ <span class="md-small-header">(<?= count($meetings); ?>)</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 table-responsive">
            <table class="table table-striped md-table">
                <thead>
                <tr>
                    <th>Сотрудник</th>
                    <th>Приезд</th>
                    <th>Отъезд</th>
                    <th>Девушка</th>
                    <th>Мужчина</th>
                    <th>Сайт</th>
                    <th>Переводчик</th>
                    <th>Город</th>
                    <th>Трансф.</th>
                    <th>Жильё</th>
                    <th>Перевод</th>
                    <th class="md-additional">Дополнительно</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($meetings as $meeting): ?>
                    <tr id="meeting_tr_<?=$meeting['ID'];?>">
                        <td><?= $meeting['ESName'] . ' ' . mb_substr($meeting['EFName'], 0, 1, 'UTF-8') . '.' . mb_substr($meeting['EMName'], 0, 1, 'UTF-8') . '.'; ?></td>
                        <td><?= date('d.m.Y', strtotime($meeting['DateIn'])); ?></td>
                        <td><?= date('d.m.Y', strtotime($meeting['DateOut'])); ?></td>
                        <td><?= $meeting['Girl']; ?></td>
                        <td><?= $meeting['Men']; ?></td>
                        <td>
                            <?= (!empty($md_sites[$meeting['SiteID']]))
                                ? '<a href="' . $md_sites[$meeting['SiteID']]['Domen'] . '" target="_blank">' . $md_sites[$meeting['SiteID']]['Name'] . '</a>'
                                : $meeting['SiteID']; ?>
                        </td>
                        <td><?= $meeting['UserTranslate']; ?></td>
                        <td><?= $meeting['City']; ?></td>
                        <td><?= $meeting['Transfer']; ?></td>
                        <td><?= $meeting['Housing']; ?></td>
                        <td><?= $meeting['Translate']; ?></td>
                        <td class="md-additional">
                            <?php if(!empty($meeting['Photo'])): ?>
                                <a href="<?= base_url("thumb") ?>/?src=/files/images/<?=$meeting['Photo'];?>" data-lightbox="Story_Image_Meeting_<?=$meeting['ID'];?>">
                                    <img src="<?= base_url("thumb") ?>/?src=/files/images/<?=$meeting['Photo'];?>&w=50" alt="avatar">
                                </a>
                            <?php else: ?>
                                <img src="http://placehold.it/50x50?text=No+Image" alt="No avatar">
                            <?php endif; ?>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModalMeeting<?=$meeting['ID'];?>">
                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                            </button>
                            <? if ($isEditStory): ?>
                                <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Вы уверены, что хотите удалить это событие?')){ removeServiceItem(<?=$meeting['ID'];?>, 'meeting'); }">
                                    <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                </button>
                            <? endif; // $isEditStory ?>
                            <!-- Modal -->
                            <div class="modal fade" id="myModalMeeting<?=$meeting['ID'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalMeeting<?=$meeting['ID'];?>Label">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="<?= base_url('customer/'.$customer['ID'].'/story/save2') ?>" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="SType" value="meeting"/>
                                            <input type="hidden" name="SID" value="<?= $meeting['ID']; ?>"/>
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalMeeting<?=$meeting['ID'];?>Label">Дополнительная информация о событии #<?=$meeting['ID'];?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <?php if(!empty($meeting['Photo'])): ?>
                                                            <a href="<?= base_url("thumb") ?>/?src=/files/images/<?=$meeting['Photo'];?>" data-lightbox="Story_Image_Meeting_Modal_<?=$meeting['ID'];?>">
                                                                <img src="<?= base_url("thumb") ?>/?src=/files/images/<?=$meeting['Photo'];?>&w=150" alt="avatar">
                                                            </a><br/>
                                                        <?php endif; ?>
                                                        <div class="form-group">
                                                            <label for="">Фото с события:</label>
                                                            <input type="file" name="SPhoto"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="">Комментарий о событии:</label>
                                                            <textarea name="SComment" class="form-control" rows="4"><?= $meeting['Comment']; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить и закрыть</button>
                                                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /Modal -->
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; // meetings ?>
<?php if(!empty($deliverys)): ?>
        <br/><br/><br/>
    <div class="row">
        <div class="col-lg-12">
            <h4>ДОСТАВКИ <span class="md-small-header">(<?= count($deliverys); ?>)</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 table-responsive">
            <table class="table table-striped md-table">
                <thead>
                <th>Сотрудник</th>
                <th>Дата</th>
                <th>Сайт</th>
                <th>Переводчик</th>
                <th>Мужчина</th>
                <th>Девушка</th>
                <th>Доставка</th>
                <th>Благодарность</th>
                <th class="md-additional">Дополнительно</th>
                </thead>
                <tbody>
                <?php foreach($deliverys as $delivery): ?>
                    <tr id="delivery_tr_<?=$delivery['ID'];?>">
                        <td><?= $delivery['ESName'] . ' ' . mb_substr($delivery['EFName'], 0, 1, 'UTF-8') . '.' . mb_substr($delivery['EMName'], 0, 1, 'UTF-8') . '.'; ?></td>
                        <td><?= date('d.m.Y', strtotime($delivery['Date'])); ?></td>
                        <td>
                            <?= (!empty($md_sites[$delivery['SiteID']]))
                                ? '<a href="' . $md_sites[$delivery['SiteID']]['Domen'] . '" target="_blank">' . $md_sites[$delivery['SiteID']]['Name'] . '</a>'
                                : $delivery['SiteID']; ?>
                        </td>
                        <td><?= $delivery['TSName'] . ' ' . mb_substr($delivery['TFName'], 0, 1, 'UTF-8') . '.' . mb_substr($delivery['TMName'], 0, 1, 'UTF-8') . '.'; ?></td>
                        <td><?= $delivery['Men']; ?></td>
                        <td><?= $delivery['Girl']; ?></td>
                        <td><?= $delivery['Delivery']; ?></td>
                        <td><?= $delivery['Gratitude']; ?></td>
                        <td class="md-additional">
                            <?php if(!empty($delivery['Photo'])): ?>
                                <a href="<?= base_url("thumb") ?>/?src=/files/images/<?=$delivery['Photo'];?>" data-lightbox="Story_Image_Delivery_<?=$delivery['ID'];?>">
                                    <img src="<?= base_url("thumb") ?>/?src=/files/images/<?=$delivery['Photo'];?>&w=50" alt="avatar">
                                </a>
                            <?php else: ?>
                                <img src="http://placehold.it/50x50?text=No+Image" alt="No avatar">
                            <?php endif; ?>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModalDelivery<?=$delivery['ID'];?>">
                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                            </button>
                            <? if ($isEditStory): ?>
                                <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Вы уверены, что хотите удалить это событие?')){ removeServiceItem(<?=$delivery['ID'];?>, 'delivery'); }">
                                    <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                </button>
                            <? endif; // $isEditStory ?>
                            <!-- Modal -->
                            <div class="modal fade" id="myModalDelivery<?=$delivery['ID'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalDelivery<?=$delivery['ID'];?>Label">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="<?= base_url('customer/'.$customer['ID'].'/story/save2') ?>" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="SType" value="delivery"/>
                                            <input type="hidden" name="SID" value="<?= $delivery['ID']; ?>"/>
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalDelivery<?=$delivery['ID'];?>Label">Дополнительная информация о событии #<?=$delivery['ID'];?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <?php if(!empty($delivery['Photo'])): ?>
                                                            <a href="<?= base_url("thumb") ?>/?src=/files/images/<?=$delivery['Photo'];?>" data-lightbox="Story_Image_Delivery_Modal_<?=$delivery['ID'];?>">
                                                                <img src="<?= base_url("thumb") ?>/?src=/files/images/<?=$delivery['Photo'];?>&w=150" alt="avatar">
                                                            </a><br/>
                                                        <?php endif; ?>
                                                        <div class="form-group">
                                                            <label for="">Фото с события:</label>
                                                            <input type="file" name="SPhoto"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="">Комментарий о событии:</label>
                                                            <textarea name="SComment" class="form-control" rows="4"><?= $delivery['Comment']; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить и закрыть</button>
                                                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /Modal -->
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; // deliverys ?>
<?php endif; // IS_LOVE_STORY ?>
</div>
<script>
    function removeServiceItem(id, type){
        $.post(
            '/services/'+type+'/remove',
            {id : id},
            function(data){
                console.log(data);
                if(data.status == 1){
                    $('#'+type+'_tr_'+id).remove();
                }
            },
            'json'
        );
    }
</script>