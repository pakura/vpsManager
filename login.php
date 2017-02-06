<?php
require_once('config.inc.php');
require_once('core/dbconfig.inc.php');
require_once('core/controller/loginController.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mobility VPS Manage</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="/res/css/hover-min.css" rel="stylesheet">
    <link href="/res/css/login.css" rel="stylesheet">
</head>
<body>
    <div class="loginWP">
        <div class="logo"></div>
        <div class="loginText"><span>root<span>@</span>devmobility:</span> >_  login<dot class="blink"></dot></div>
        <div class="form">
            <p <?php if(!isset($Loginerror)){ ?>style="display:none;"<?php } ?>>
                <?= $Loginerror ?>
            </>
            <form action="login.php" method="post">
                <?php if(!isset($phone)){ ?>
                <input type="text" name="user" placeholder="user">
                <input type="password" name="pass" placeholder="password">
                <?php } else { ?>
                <label style="color:#fff; float: left;margin-left: 4%;" for="sms">SMS Code:</label>
                <br>
                <input type="text" name="sms" placeholder="SMS">
                <?php } ?>
                <div style="width: 90%; height: 0px; margin: auto; border: 2px dashed rgba(0,0,0,0.3); margin-top: 10px"></div>
                <input type="submit" class="loginbtn hvr-float-shadow" value="Log In">
            </form>
        </div>
        <div style="clear: both"></div>
    </div>
    <div class="power">v0.1 By Vaja Sinauridze</div>
</body>
</html>
