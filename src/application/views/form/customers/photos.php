<?php
/**
 * @var $CustomerID
 * @var $canRemove
 */
?>
<style>
    .customer-photos-block{
        margin: 15px auto 10px 0;
    }
    .customer-photos-block > .col-md-12{
        padding: 0;
    }
    .photos-header{
        font-size: 20px;
        margin-top: 15px;
        position: relative;
        top: 3px;
    }
    #addNewCustomerPhotos{
        margin-left: 15px;
    }
    #CustomerPhotoUploadModal .modal-dialog{
        width: 90%;
        background: white;
        max-width: 1180px;
    }
    #CustomerPhotoUploadModal iframe{
        width: 100%;
        height: 600px;
    }
    #customerPhotosGrid{
        margin-bottom: 40px;
    }
    #customerPhotosGrid .thumbnail{
        width: 140px;
        height: 165px;
        float: left;
        margin: 2px;
        position: relative;
    }
    #customerPhotosGrid .thumbnail button,
    #customerPhotosGrid .thumbnail a.btn{
        position: absolute;
        border-radius: 50%;
    }
    #customerPhotosGrid .thumbnail button > span.glyphicon,
    #customerPhotosGrid .thumbnail a.btn > span.glyphicon{
        position: relative;
        top: 1px;
    }
    .rm-btn{
        right: 5px;
    }
    .dl-btn{
        left: 5px;
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
        top: -10px;
    }
    .moder-content {
        position: relative;
    }
</style>
<div class="row customer-photos-block">
    <div class="col-md-12 moder-content">
        <span class="photos-header">Фото</span>
        <button id="addNewCustomerPhotos" class="btn assol-btn add">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        </button>
        <div class="col-md-8 messages-area">
            <div class="alert alert-success" role="alert" id="successText"></div>
            <div class="alert alert-danger" role="alert" id="errorText"></div>
        </div>
    </div>
</div>

<script id="photosWrapTmpl" type="text/x-jquery-tmpl">
    {{if records.length > 0}}
        {{tmpl(records) '#photoItemTmpl'}}
    {{/if}}
</script>
<script id="photoItemTmpl" type="text/x-jquery-tmpl">
    <div class="thumbnail" id="gridItem${ID}">
        <a href="${pathFull}" class="btn btn-default btn-xs dl-btn" title="Скачать" target="_blank">
            <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
        </a>
        <?php if($canRemove): ?>
        <button class="btn btn-default btn-xs rm-btn" title="Удалить" onclick="removePhoto(${ID});">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
        </button>
        <?php endif; ?>
        <a href="${pathFull}" data-lightbox="Customer_images" title="${Comment}">
          <img src="${pathThumb}" alt="Customer ${CustomerID} image ${ID}">
        </a>
        <?php $onclick = ($canRemove) ? 'onclick="editComment(${ID});" id="photocomment_${ID}"' : ''; ?>
        <div <?= $onclick; ?> class="photo-comment" data-toggle="tooltip" data-placement="bottom" title="${Comment}">${Comment}</div>
    </div>
</script>
<div class="alert alert-success text-center" role="alert" id="uploadInfo" style="display: none;">
    Информация успешно загружена!<br>Она будет доступна на странице Клиентки после одобрения Директором.
</div>
<div id="customerPhotosGrid" class="clearfix"></div>

<script>
    $(document).on('click', 'a[aria-controls=PhotoAndVideo]', function(){
        getCustomerPhotosList();
    });

    $(document).on('click', '#addNewCustomerPhotos', function () {
        var modal = $('#CustomerPhotoUploadModal');
        var frame = modal.find('iframe');
        var frameSrc = '<?=base_url('Customer_Photos/upload/'.$CustomerID)?>';

        modal.on('show.bs.modal', function () {
            frame.attr("src", frameSrc);
        });
        modal.on('hidden.bs.modal', function () {
            getCustomerPhotosList();
            checkUpload();
        });
        modal.modal({show:true});
    });

    function checkUpload() {
        $.post(
            '/Customer_Photos/checkup',
            {
                id: <?= $CustomerID; ?>
            },
            function (data) {
                if(data*1 > 0){
                    $("#uploadInfo").show().delay(5000).fadeOut();
                }
            },
            'text'
        );
    }

    function getCustomerPhotosList() {
        $.post(
            '/Customer_Photos/data',
            {
                id: <?= $CustomerID; ?>
            },
            function (data) {
                if(data.status){
                    $('#customerPhotosGrid').html('');
                    $(function () {
                        $('#photosWrapTmpl').tmpl(data).appendTo('#customerPhotosGrid');
                    });
                }
                else{
                    $('#customerPhotosGrid').html('<h5>Нет данных для отображения. Status 0</h5>');
                }
            },
            'json'
        );
    }

    function removePhoto(id) {
        if(confirm('Вы действительно хотите удалить фото?')){
            $.get(
                '/Customer_Photos/remove/<?= $CustomerID;?>/'+id,
                function (data) {
                    if(data.status){
                        $('#gridItem'+data.index).remove();
                    }
                },
                'json'
            );
        }
    }

    $(function () {
//        $('[data-toggle="tooltip"]').tooltip();
        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
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

<?php /* upload photos modal */ ?>
<div class="modal fade" id="CustomerPhotoUploadModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="CustomerPhotoUploadLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="CustomerPhotoUploadLabel">Загрузка изображения</h4>
            </div>
            <div class="modal-body">
                <iframe src="" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
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