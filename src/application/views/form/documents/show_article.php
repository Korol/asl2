<ol class="breadcrumb assol-grey-panel">
    <li><a href="<?= base_url('documents') ?>">Документация</a></li>
    <?php /*if (!empty($bread)): ?>
        <? foreach ($bread as $item): ?>
        <li><a href="<?= base_url('documents') . '/' .$item['ID']; ?>"><?= $item['Name'] ?></a></li>
        <? endforeach ?>
    <? endif*/ ?>
    <li class="active"><?= $record['Name'] ?></li>
</ol>

<p><b><?= $record['Name'] ?></b></p>
<br>
<script type="text/javascript">
    document.ondragstart = noselect;
    // запрет на перетаскивание
    document.onselectstart = noselect;
    // запрет на выделение элементов страницы
    document.oncontextmenu = noselect;
    // запрет на выведение контекстного меню
    function noselect() {return false;}
</script>
<style type="text/css">
    .noselect {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }
</style>
<div style="max-width: 937px" class="noselect">
    <?= $record['Content'] ?>
</div>