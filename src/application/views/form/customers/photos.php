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
        height: 140px;
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
</style>
<div class="row customer-photos-block">
    <div class="col-md-12">
        <span class="photos-header">Фото</span>
        <button id="addNewCustomerPhotos" class="btn assol-btn add">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        </button>
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
        <a href="${pathFull}" data-lightbox="Customer_images">
          <img src="${pathThumb}" alt="Customer ${CustomerID} image ${ID}">
        </a>
    </div>
</script>
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
        });
        modal.modal({show:true});
    });

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
</script>

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