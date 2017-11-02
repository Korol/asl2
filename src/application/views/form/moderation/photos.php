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
    #successText,
    #errorText {
        display: none;
    }
    .messages-area {
        position: absolute;
        right: 0;
        top: 10px;
    }
    .moder-content {
        position: relative;
    }
    .panel {
        padding-top: 15px;
        padding-bottom: 15px;
        margin-left: 0;
        margin-right: 0;
    }
</style>

<div class="row panel assol-grey-panel">
    <div class="col-md-6 col-md-offset-3">
        <div class="row">
            <div class="col-md-6">
                <?php if(!empty($customers)): ?>
                <form action="">
                    <div class="form-group">
                        <select name="customer_id" id="" class="form-control"></select>
                    </div>
                </form>
                <?php endif; ?>
            </div>
            <div class="col-md-6">rest</div>
        </div>
    </div>
</div>
<?php
$h3 = (!empty($customer_id))
    ? 'клиентки'
    : (!empty($author_id))
        ? 'сотрудника'
        : 'клиенток';
?>
<div class="row" id="customersPhotosGrid">
    <div class="col-md-12 moder-content">
        <h3>Фото <?= $h3; ?> на модерацию</h3>
        <div class="col-md-4 messages-area">
            <div class="alert alert-success" role="alert" id="successText"></div>
            <div class="alert alert-danger" role="alert" id="errorText"></div>
        </div>
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
                            $photo_comment_attr = 'data-toggle="tooltip" data-placement="bottom" title="' . $photo['Comment'] . '"';
                            $photo_comment_text = $photo['Comment'];
                            ?>
                            <div id="photocomment_<?= $photo['ID']; ?>" class="photo-comment" <?= $photo_comment_attr; ?> onclick="editComment(<?= $photo['ID']; ?>);">
                                <?= $photo_comment_text; ?>
                            </div>
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
                            if(data.message.length > 0){
                                $('#successText').html(data.message);
                                $("#successText").show().delay(4000).fadeOut();
                            }
                        }
                        else{
                            $('#errorText').html(data.message);
                            $("#errorText").show().delay(4000).fadeOut();
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

        function editComment(id) {
            $('#editCommentPhotoID').val(id);
            $.post(
                '/Moderation/getphototext/',
                {
                    ID: id
                },
                function (data) {
                    if(data.status){
                        $('#editCommentText').val(data.message);
                        $('#editCommentModal').modal('show');
                    }
                    else {
                        alert('Нет данных для редактирования');
                    }
                },
                'json'
            );
        }

        function saveCommentChanges() {
            var id = $('#editCommentPhotoID').val();
            var comment = $('#editCommentText').val();
            $.post(
                '/Moderation/savenewcomment/',
                {
                    ID: id,
                    Comment: comment
                },
                function (data) {
                    if(data.status){
                        $('#photocomment_'+data.id).html(data.comment);
                        $('#photocomment_'+data.id).attr('title', data.comment);
                        $('#photocomment_'+data.id).attr('data-original-title', data.comment);
                        $('#editCommentModal').modal('hide');
                        $('#successText').html(data.message);
                        $("#successText").show().delay(4000).fadeOut();
                    }
                    else{
                        $('#editCommentModal').modal('hide');
                        $('#errorText').html(data.message);
                        $("#errorText").show().delay(4000).fadeOut();
                    }
                },
                'json'
            );
        }
    </script>
<?php else: ?>
        <h5 class="text-center">Все фото Клиенток обработаны</h5>
<?php endif; ?>
    </div>
</div>

<?php /* edit comment modal */ ?>
<div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Редактирование комментария к фото</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="editCommentPhotoID" value="">
                    <label for="editCommentText">Текст комментария:</label>
                    <textarea class="form-control" id="editCommentText" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" onclick="saveCommentChanges();" class="btn btn-primary">Сохранить</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
