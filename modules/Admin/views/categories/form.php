<?
    $this->title =  $form->instance->isNew ?
        'Нова категорія' :
        $form->instance->name
?>

<?=\app\widgets\bootstrap\BreadCrumb::widget([
    ['label' => 'Головна', 'url' => '/admin'],
    ['label' => 'Категорії', 'url' => '/admin/categories'],

    $form->instance->isNew ?
        ['label' => 'Нова категорія', 'url'=> '/admin/category/add'] :
        ['label' => $form->instance->name, 'url' => '/admin/category/'. $form->instance->id]
])?>

<form method="post" action="<?= (!$form->instance->isNew) ? '/admin/category/'. $form->instance->id : '/admin/category/add'?>">
    <?= $form->field('name', ['class' => 'form-control'])?>
    <?= $form->field('alias', ['class' => 'form-control'])?>

    <button type="submit" class="btn btn-success">Зберегти</button>
</form>