<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>

    <title><?= $exception->getMessage();?></title>
    <link rel="stylesheet" href="/assets/syntaxhighlighter/styles/shCoreDefault.css">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap-theme.min.css">


</head>

<body>
<div class="header">
    <h1><?= $exception->getMessage();?></h1>
</div>

<div class="call-stack">

    <ul style="list-style: none;">
        <?= $handler->renderCallStackItem($exception->getFile(), $exception->getLine(), null, null, [], 1) ?>
        <?php for ($i = 0, $trace = $exception->getTrace(), $length = count($trace); $i < $length; ++$i): ?>
            <?= $handler->renderCallStackItem(@$trace[$i]['file'] ?: null, @$trace[$i]['line'] ?: null,
                @$trace[$i]['class'] ?: null, @$trace[$i]['function'] ?: null, $trace[$i]['args'], $i + 2) ?>
        <?php endfor; ?>
    </ul>
</div>

<script src="/assets/jquery/jquery.min.js"></script>
<script src="/assets/syntaxhighlighter/scripts/shCore.js"></script>
<script src="/assets/syntaxhighlighter/scripts/shBrushPhp.js"></script>

<script type="text/javascript">SyntaxHighlighter.all();</script>

</body>

</html>

