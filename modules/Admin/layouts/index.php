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
        'brandLabel' => \app\sitebuilder\Container::get('siteName') . ' - Панель управління',
        'brandUrl' => '/',
        'navBars' => [
            [
                'class' => 'nav navbar-nav navbar-right',
                'items' => [
                    ['label' => 'Документація', 'url' => '/docs'],
                ]
            ]
        ]
    ]) ?>

    <div>
        <?= $this->content ?>
    </div>
</div>
</body>
</html>