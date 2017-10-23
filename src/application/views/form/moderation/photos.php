<?php
/**
 * @var $photos
 */
?>
<style>
    #customersPhotosGrid .thumbnail{
        width: 140px;
        height: 190px;
        float: left;
        margin: 5px;
        position: relative;
    }
    .dl-btn, .rm-btn{
        margin-top: 5px;
    }
    .chk-inp{
        width: 20px;
        height: 20px;
        margin-left: 32px !important;
        margin-top: 7px !important;
    }
    .customer-photos-block{
        margin-bottom: 30px;
    }
    #btnApprove{
        margin-right: 15px;
    }
    #btnRemove{
        margin-left: 15px;
    }
    .moder-btns-block{
        margin-bottom: 40px;
    }
    .moder-content h5{
        margin-top: 30px;
    }
    .photo-comment {
        width: 100%;
        height: 20px;
        overflow-x: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
        margin-top: 5px;
    }
</style>
<div class="row" id="customersPhotosGrid">
    <div class="col-md-12 moder-content">
        <h3>Фото Клиенток на модерацию</h3>
<?php if(!empty($photos)): ?>
        <?php
        $i = 0;
        foreach ($photos as $pk => $customerPhotos): ?>
        <div class="row customer-photos-block">
            <div class="col-md-12">
                <?php
                if($i != $pk):
                    $i = $pk;
                ?>
                <h4><?= $customerPhotos[0]['SName'] . ' ' . $customerPhotos[0]['FName']; ?></h4>
                <div class="row">
                    <div class="col-md-12 clearfix">
                <?php endif; ?>
                    <?php foreach ($customerPhotos as $photo): ?>
                        <div class="thumbnail clearfix" id="gridItem<?= $photo['ID']; ?>">
                            <a href="<?= $photo['pathFull']; ?>" data-lightbox="Customer_images_<?= $pk; ?>" title="<?= $photo['Comment']; ?>">
                                <img src="<?= $photo['pathThumb']; ?>" alt="Customer <?= $photo['SName']; ?> image <?= $photo['ID']; ?>">
                            </a>
                            <?php
                            $photo_comment_text = $photo_comment_attr = '';
                            if(!empty($photo['Comment'])){
                                $photo_comment_attr = 'data-toggle="tooltip" data-placement="bottom" title="' . $photo['Comment'] . '"';
                                $photo_comment_text = $photo['Comment'];
                            }
                            ?>
                            <div class="photo-comment" <?= $photo_comment_attr; ?>><?= $photo_comment_text; ?></div>
                            <a href="<?= $photo['pathFull']; ?>" class="btn btn-default btn-xs dl-btn pull-left" title="Скачать" target="_blank">
                                <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
                            </a>
                            <input type="checkbox" name="items[<?= $photo['ID']; ?>]" value="<?= $photo['ID']; ?>" class="chk-inp pull-left">
                            <button class="btn btn-default btn-xs rm-btn pull-right" title="Удалить" onclick="removePhoto(<?= $photo['CustomerID']; ?>, <?= $photo['ID']; ?>);">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </button>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <div class="row moder-btns-block">
        <div class="col-md-12 moder-btns">
            <button id="btnApprove" onclick="actionSelected('approve');" class="btn btn-success">Одобрить отмеченные</button>
            <button id="btnRemove" onclick="actionSelected('remove');" class="btn btn-danger">Удалить отмеченные</button>
        </div>
    </div>

    <script>
        function actionSelected(mode) {
            if((mode === 'remove') && !confirm('Вы действительно хотите удалить отмеченные фото?')){
                return;
            }
            var items = '';
            $('input.chk-inp').each(function (index, element) {
                if($(element).is(':checked')){
                    items += '_'+$(element).val();
                }
            });
            if(items !== ''){
                $.post(
                    '/Moderation/batchphotos',
                    {
                        items: items,
                        mode: mode
                    },
                    function (data) {
                        if(data.status){
                            var rds = data.records;
                            rds.forEach(function (item, i, rds) {
                                $('#gridItem'+item).remove();
                            });
                        }
                    },
                    'json'
                );
                checkCountPhotos();
            }
        }

        function removePhoto(cid, id) {
            if(confirm('Вы действительно хотите удалить фото?')){
                $.get(
                    '/Customer_Photos/remove/'+cid+'/'+id,
                    function (data) {
                        if(data.status){
                            $('#gridItem'+data.index).remove();
                        }
                    },
                    'json'
                );
                checkCountPhotos();
            }
        }

        function checkCountPhotos(){
            $.post(
                '/Moderation/countphotos',
                function (data) {
                    if(data*1 === 0){
                        $('.customer-photos-block, .moder-btns-block').remove();
                        $('.moder-content').append('<h5 class="text-center">Все фото Клиенток обработаны</h5>');
                    }
                },
                'text'
            );
        }

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
<?php else: ?>
        <h5 class="text-center">Все фото Клиенток обработаны</h5>
<?php endif; ?>
    </div>
</div>
