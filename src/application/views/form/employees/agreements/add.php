<form id="fileUploadForm" action="<?=current_url()?>" enctype="multipart/form-data" method="post">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="remoteDialogLabel">Добавить договор</h4>
    </div>
    <div class="modal-body">
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Ошибка!</strong> <?= $errorMessage ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="exampleInputFile">Выбор файла для загрузки</label>
            <input type="file" name="upload" id="exampleInputFile" required>
        </div>

        <div id="alertError" class="alert alert-danger" role="alert" style="display: none">
            <h4>Ошибка!</h4>
            <p id="alertErrorMessage"></p>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="bSubmit">Загрузить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
    </div>
</form>

<script>
    $('#fileUploadForm').ajaxForm(function(data) {
        if (data.status) {
            $.EmployeeCard.ReloadAgreementList();
            $('#remoteDialog').modal('hide')
        } else {
            showErrorAlert(data.message)
        }
    });

    function showErrorAlert(message) {
        $('#alertErrorMessage').text(message);
        $('#alertError').slideDown();
    }
</script>