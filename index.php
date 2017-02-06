<?php

$title = "Mobility VPS Manage";

require_once('config.inc.php');
require_once('core/dbconfig.inc.php');
require_once('core/model/authCheck.php');
require_once('Net/SSH2.php');
require_once('core/model/sshModel.php');
require_once ('core/controller/ProjectController.php');
include 'views/head.inc.php';
?>

<body>
    <?php include 'views/header.inc.php'; ?>

    <div class="wrapper">
        <div class="projectList">
            <div class="item hvr-float-shadow active" style="background-color: rgba(28, 110, 79, 0.6);">
                <div class="icon" style="background-image: url('/res/img/projecticon.png'); margin-right: 0;"></div>
                <div class="info" style="line-height: 60px">
                    Create Something Realy Cool
                </div>
            </div>

            <div class="item hvr-float-shadow">
                <div class="icon" style="background-image: url('http://mobility.ge/files/porfolio/lilomall/lilomall-icon.png')"></div>
                <div class="info">
                    <div class="title">Lilo Mall</div>
                    <div class="desc">
                        <a href="http://lilomall.ge" target="_blank">lilo.GE</a>
                        |
                        Owner: Giorgi
                    </div>
                </div>
            </div>

            <div class="item hvr-float-shadow">
                <div class="icon" style="background-image: url('https://ukve.ge/assets/site/images/fb-logo-210.png')"></div>
                <div class="info">
                    <div class="title">Ukve</div>
                    <div class="desc">
                        <a href="http://geocell.ge" target="_blank">Ukve.GE</a>
                        |
                        Owner: Vaja
                    </div>
                </div>
            </div>

            <div class="item hvr-float-shadow">
                <div class="icon"></div>
                <div class="info">
                    <div class="title">Geocell</div>
                    <div class="desc">
                        <a href="http://geocell.ge" target="_blank">Geocell.GE</a>
                        |
                        Owner: Vaja
                    </div>
                </div>
            </div>

            <div class="item hvr-float-shadow">
                <div class="icon"></div>
                <div class="info">
                    <div class="title">Geocell</div>
                    <div class="desc">
                        <a href="http://geocell.ge" target="_blank">Geocell.GE</a>
                        |
                        Owner: Vaja
                    </div>
                </div>
            </div>

            <div class="item hvr-float-shadow">
                <div class="icon"></div>
                <div class="info">
                    <div class="title">Geocell</div>
                    <div class="desc">
                        <a href="http://geocell.ge" target="_blank">Geocell.GE</a>
                        |
                        Owner: Vaja
                    </div>
                </div>
            </div>
        </div>
        <div class="desktop">
            <h1>New Project</h1>
            <input type="text" class="projectName hvr-grow" name="name" id="name" placeholder="Type Project  Name N Go" />
            <div style="height: 50%">
            <div class="options">
                <h4>Options</h4>
                <div class="spoiler"></div>
                <div class="content">
                    <input type="text" name="git" id="git" placeholder="Enter git repo"/>
                    <br>
                    <br>
                    <div style="text-align: center; width: 100%; height: 30px">
                        <span>OR</span>
                    </div>
                    <input type="radio" name="project_type" value="1" id="empty" checked />
                    <label for="empty">Empty Project</label>
                    <br>
                    <input type="radio" name="project_type" value="2" id="laravel"  />
                    <label for="laravel">Empty Laravel Project</label>
                    <br>
                    <input type="radio" name="project_type" value="3" id="cms"  />
                    <label for="cms">Digital CMS Project</label>
                    <br>
                </div>
            </div>
            </div>
            <button class="hvr-bounce-in" id="go">GO</button>
        </div>
    </div>
    <div class="hover" style="display: none">
        <button class="hvr-push" id="abort">Abort</button>
        <button class="hvr-push" style="background-color: #0ac153; display: none" id="OK">OK</button>
        <div class="monitor">

        </div>
    </div>
    <div class="power">v0.1 By Vaja Sinauridze</div>
</body>
</html>