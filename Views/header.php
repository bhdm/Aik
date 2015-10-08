<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="?type=group">По группам</a>
            <a class="navbar-brand" href="?type=instructor">По инструкторам</a>
            <a class="navbar-brand" href="?type=room">По залам</a>
        </div>
        <div class="navbar-right">
            <a class="navbar-brand" href="?type=<?=$_GET['type']?>&pdf=1" target="_blank">В PDF</a>
        </div>
    </div>
</nav>