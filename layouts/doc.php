<?
use app\widgets\bootstrap\Nav;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $this->title ?></title>

    <? $this->getCssFiles(); ?>

    <? $this->getJsFiles() ?>
    <script type="text/javascript">SyntaxHighlighter.all();</script>
</head>
<body>
<div class="container">
    <?= Nav::widget([
        'class' => 'navbar-default',
        'brandLabel' => \app\sitebuilder\Container::get('siteName') . ' ( '.\app\sitebuilder\Application::$app->t->translate('documentation', 'Documentation').' )',
        'brandUrl' => '/',

    ]) ?>

    <div class="col-md-9">
        <?= $this->content ?>
    </div>

    <div class="col-md-3">

    </div>
</div>
</body>
</html>