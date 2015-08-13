<?
    $this->title = 'Вхід в систему';
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            Вхід в систему
        </h3>
    </div>

    <div class="panel-body">
        <form action="/admin/sign_in?referrer=<?= $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '/admin' ?>" method="post" id = "loginForm">
            <?= $form->field('login', ['class' => 'form-control'])?>
            <?= $form->field('password', ['class' => 'form-control'])?>
        </form>
    </div>

    <div class="panel-footer">
        <button type="submit" form="loginForm" class="btn btn-success">Вхід</button>
    </div>
</div>