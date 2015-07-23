<div class="navbar navbar-default navbar-fixed-bottom">

    <div style="color: white;">
        <span>Time : <?= round((($timeEnd - $timeBegin)), 4)?> seconds.</span>
        <span>Memory : <?= round(memory_get_peak_usage() / (1024*1024), 3)?> mb.</span>
    </div>
</div>