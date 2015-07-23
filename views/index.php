<? $this->title = \app\sitebuilder\Container::get('siteName')?>

<a href="/rubric" class="btn btn-primary">Додати рубрику</a>

<table class="table">

    <?foreach ($rubrics as $rubric):?>
        <tr>
            <td><?= $rubric['id']?></td>
            <td><?= $rubric['title']?></td>
            <td><a href="/rubric/edit/<?= $rubric['id']?>" class="btn btn-success"><span class="glyphicon glyphicon-eye-open"></span></a> </td>
            <td><a href="/rubric/<?= $rubric['id']?>/delete" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a> </td>
        </tr>
    <?endforeach?>
</table>