<div class="header">
    <a href="/"><div class="logo"></div></a>
    <div class="status">
        <div>
            Apache Status: <span class="ok" id="apache">OK</span>
        </div>
        <div>
            Mysql Status: <span class="ok" id="mysql">OK</span>
        </div>
        <div>
            <span class="info pointer" onclick="dialog('Database Status', '<?= mysqli_stat ($con) ?>', [{name: 'OK'}], 'big')">MariaDB Info</span>
        </div>
        <div>
            Server Time: <span id="time"><?= date('h:i:s') ?></span>
        </div>
    </div>
    <a href="/logout.php">
        <div class="profileWP">
            <div class="logout"></div>
        </div>
    </a>
</div>
<div class="headerloadwp">
    <div class="loader"></div>
</div>