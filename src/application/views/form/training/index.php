<?php if ($role['isDirector']): ?>
    <div class="panel assol-grey-panel">
        <div class="panel-body">

            <button class="btn assol-btn add right action-training-add" title="Создать статью" style="margin-left: 20px">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Создать статью
            </button>
            <a href="<?=base_url('training/add_folder')?>" data-toggle="modal" data-target="#remoteDialog"
               class="" role="button" title="Добавить папку">
               <button class="btn assol-btn add right">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Добавить папку
                </button>
            </a>

            <div>
                <a href="javascript:void(0)" id="btnFileUpload" role="button" title="Загрузить файл">
                    <button class="btn assol-btn save right" style="margin-right: 25px;">
                        <span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
                        Загрузить файл
                    </button>
                </a>

                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="FileUploadLabel">
                    <div class="modal-dialog training-file-modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="FileUploadLabel">Загрузка файлов</h4>
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

                <style>
                    .training-file-modal {
                        width: 90% !important;
                        background: white;
                        max-width: 1180px;
                    }

                    .training-file-modal .modal-title {
                        float: left;
                    }

                    .training-file-modal iframe {
                        width: 100%;
                        height: 600px;
                    }
                </style>

                <script>
                    $(document).on('click', '#btnFileUpload', function () {
                        var modal = $(this).parent().find('.modal');
                        var frame = $(this).parent().find('iframe');
                        var frameSrc = '<?= base_url('training') ?>/' + $.AssolTraining.GetParent() + '/upload';

                        modal.on('show.bs.modal', function () {
                            frame.attr("src", frameSrc);
                        });
                        modal.on('hidden.bs.modal', function () {
                            $.AssolTraining.ReloadTrainingList();
                        });
                        modal.modal({show:true});
                    });
                </script>
            </div>
        </div>
    </div>
<?php endif ?>

<script id="trainingTemplate" type="text/x-jquery-tmpl">
    {{if bread}}
    <ol class="breadcrumb assol-grey-panel">
        <li><a href="#"><a href="#" record="0" class="action-folder-open">Обучение</a></li>
        {{tmpl($data.bread) "#trainingBreadTemplate"}}
    </ol>
    {{/if}}

    {{if AccessDenied}}
        Нет доступа к текущему каталогу
    {{else}}
        {{tmpl($data.data) "#trainingListTemplate"}}
    {{/if}}
</script>

<script id="trainingBreadTemplate" type="text/x-jquery-tmpl">
    <li><a href="#" record="${ID}" class="action-folder-open">${Name}</a></li>
</script>
<script id="trainingListTemplate" type="text/x-jquery-tmpl">
    <div class="document" record="${ID}">
        <div class="document-menu">
        <?php if ($role['isDirector']): ?>
            {{if IsFolder>0}}
            <span class="glyphicon glyphicon-remove-circle document-menu-btn remove action-folder-remove" aria-hidden="true" title="Удалить"></span>
            <a href="<?=base_url('training/edit_folder')?>/${ID}" data-toggle="modal" data-target="#remoteDialog" role="button">
                <span class="glyphicon glyphicon-edit document-menu-btn blue" aria-hidden="true" title="Редактировать"></span>
            </a>
            {{/if}}

            {{if IsFolder==0}}
                {{if isFile}}
                <span class="glyphicon glyphicon-remove-circle document-menu-btn remove action-file-remove" aria-hidden="true" title="Удалить"></span>
                <a href="<?=current_url()?>/load/${ID}" target="_blank">
                    <span class="glyphicon glyphicon-eye-open document-menu-btn blue" aria-hidden="true" title="Открыть"></span>
                </a>
                {{else}}
                    <span class="glyphicon glyphicon-remove-circle document-menu-btn remove action-document-remove" aria-hidden="true" title="Удалить"></span>
                    <span class="glyphicon glyphicon-edit document-menu-btn blue action-training-edit" aria-hidden="true" title="Редактировать"></span>
                {{/if}}
            {{/if}}
        <?php endif ?>
        </div>
        <div>
            {{if isFile}}
                <img src="<?=base_url('public/img')?>/file.png">
                <p class="document-name">${Name}</p>
            {{else}}
            <a href="#" class="{{if IsFolder>0}}action-folder-open{{else}}action-training-open{{/if}}">
                <img src="<?=base_url('public/img')?>/{{if IsFolder>0}}<?= IS_LOVE_STORY ? 'folder-lovestory' : 'folder' ?>{{else}}training{{/if}}.png">
                <p class="document-name">${Name}</p>
            </a>
            {{/if}}

        </div>
    </div>
</script>

<div id="training"></div>

<script>
    $('body').on('hidden.bs.modal', '.remoteModal', function () {
        $(this).removeData('bs.modal');
    });

    var UrlParent = <?= $Parent ?>;
</script>

<style>
    .modal-dialog {
        width: 400px;
    }
</style>