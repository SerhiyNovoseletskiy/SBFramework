<?php
$this->title = 'Категорії';
?>
<?=\app\widgets\bootstrap\BreadCrumb::widget([
    ['label' => 'Головна', 'url' => '/admin'],
    ['label' => 'Категорії', 'url' => '/admin/categories'],
])?>

<a href="/admin/category/add" class="btn btn-success" style="float: right;">Додати категорію</a>

<table class="table">
    <?foreach ($categories as $category):?>
        <tr>
            <td><?= $category->id?></td>
            <td><?= $category->name?></td>
            <td><a href="/admin/category/<?= $category->id?>" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span></a> </td>
            <td><a href="/admin/category/<?= $category->id?>/delete" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a> </td>
        </tr>
    <?endforeach;?>
</table>