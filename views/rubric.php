<a href="/rubric/delete/<?= $rubric->id?>" class="btn btn-danger">Видалити</a>
<form method="post">
    <label>Title</label>
    <input type="text" class="form-control" name="title" value="<?= $rubric->title?>">
    <button type="submit" class="btn btn-success">Зберегти</button>
</form>