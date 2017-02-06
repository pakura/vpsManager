/**
 * Created by root on 1/28/2017.
 */

$(function () {
    $('#dialog').draggable({ handle: ".popup .title" });
    $('.wrapper .desktop .options .spoiler').click(function () {
       toggleSpoiler();
    });
    $('#go').click(function () {
        initCreate();
    });
    $('#abort').click(function () {
        disableScreen();
    });
    $('#OK').click(function () {
        disableScreen();
    });


});

setInterval(function () {
    clockDown();
},1000);


function load(time, stay) {
    if(typeof time == 'undefined'){
        time = 1;
    }
    time *= 1000;
    $('.loader').stop( false, false );
    $('.loader').css({width: '0%'});
    $('.loader').animate({width: '100%'},time, function () {
        if(stay != true){
            $('.loader').css({width: '0%'});
        }
    });

}

var Spoiler = false;
function toggleSpoiler() {
    if(Spoiler == false){
        var height = 160;
        $('.options').css({'height':'auto'});
        $('.options .spoiler').addClass('up');
    } else {
        var height = 0;
        $('.options .spoiler').removeClass('up');
    }
    $('.options .content').animate({
        height: height
    },1300, function () {
        if(Spoiler == true){
            $('.options').animate({'height':'20px'},300);
        }
        Spoiler = !Spoiler;
    });
}

function clockDown() {
    var time = $('#time').html();
    time = time.split(':');
    var Hour = parseInt(time[0])*3600;
    var Min = parseInt(time[1])*60;
    var Sec = parseInt(time[2]);
    time = Hour + Min + Sec + 1;
    Hour = (time / 3600 < 10)?'0'+(parseInt(time / 3600)):parseInt(time / 3600);
    time %= 3600;
    Min = (time / 60 < 10)?'0'+(parseInt(time / 60)):parseInt(time / 60);
    time %= 60;
    Sec = (time < 10)?'0'+time:time;
    $('#time').html(Hour+':'+Min+':'+Sec);
}

function closeListener() {
    $('.popupwp .close').click(function () {
        var popup = $(this).parents()[1];
        $(popup).fadeOut(function () {
            $(popup).remove();
        });
    });
}

function dialog(title, content, btns, size) {
    if(!size) size = '';
    var id = 'dialog_'+Math.round(Math.random()*10000);
    var dialog = '<div class="popupwp '+size+'" style="display: none;" id="'+id+'">\
        <div class="head">\
        <div class="title">'+title+'</div>\
        <div class="close pointer hvr-grow"></div>\
        </div>\
        <div class="content">'+content+'</div>\
        <div class="footer">';

        for(var ii in btns){
            dialog += '<button class="close"> '+btns[ii].name+'</button>';
        }

        dialog += '</div>\
        </div>';
    $('body').append(dialog);
    $('#'+id).fadeIn();
    $('#'+id).find('button:first').focus();
    closeListener();
    return id;
}
function disableScreen() {
    $('.hover').fadeToggle();
}

function initCreate() {
    if(done==true){
        disableScreen();
        return 0;
    }
    $('.monitor').html('');
    log('Start Project creation');
    log('Collecting data');
    var project = {
        name: $('#name').val(),
        git: $('#git').val(),
        type: parseInt($('input[name="project_type"]:checked').val())
    }
    log('Project init',500);
    if(project.name.length < 3) return;
    disableScreen();
    log('Creating project '+$('#name').val(), 1000);
    load(30,true);
    sendData(project);
}
var done = false;
var result;
function sendData(project) {
    setInterval(function () {
        if(done == false){
            $(".monitor").animate({ scrollTop: $(document).height() }, "slow");
        }
    },300);
    log('Sending collected data to server', 2000);
    $.ajax({
        method: "POST",
        url: "/index.php",
        data: { data: project }
    })
        .done(function( msg ) {
            result = msg;
            done = true;
        });
    log('Creating Direcoties',3500);
    log('Creating Log Direction',4200);
    log('Generating Virtual Host .conf File',5000);
    log('Saving Virtual Hpst .conf Files',5500);
    log('Creating new ssh User',6000);
    log('Generating New ssh User\'s Password',6500);
    log('Add ssh User To Sudoer Group',6800);
    log('Creating Database',8000);
    log('Creating MySql User',8100);
    log('Grant All Action to MySql User Of New Database',8500);
    log('Creating Res Files',12000);
    log('Setting Up chown & chmod Commands',15000);
    log('Checking Exception',17000);
    log('Almost Completed',20000);
    log('Done',25000);
    setTimeout(function () {
        try {
            response = JSON.parse(result);
            var showParams = '<a href="http://'+response.project_url+'" target="_blank">'+response.project_url+'</a>';
            showParams += '<br>ssh{<br>&nbsp;&nbsp;&nbsp;<span>Host: </span>138.197.73.246<br>&nbsp;&nbsp;&nbsp;<span>User: </span>'+response.ssh_user;
            showParams += '<br>&nbsp;&nbsp;&nbsp;<span>Pass: </span>'+response.ssh_user_pass+'<br>&nbsp;&nbsp;&nbsp;<span>Port: </span>22<br>}';
            showParams += '<br>MySql{<br>&nbsp;&nbsp;&nbsp;<span>Host: </span>127.0.0.1<br>&nbsp;&nbsp;&nbsp;<span>Database: </span>'+response.db;
            showParams += '<br>&nbsp;&nbsp;&nbsp;<span>User: </span>'+response.db_user+'<br>&nbsp;&nbsp;&nbsp;<span>Pass: </span>'+response.db_pass;
            showParams += '<br>&nbsp;&nbsp;&nbsp;<span>Port: </span>3306<br>}';
            log(showParams);
        } catch(e) {
            log('<span style="color: darkred;">Error: '+result); // error in the above string (in this case, yes)!
        }
    },27000);
    setTimeout(function () {
        if(done == true){
            $('#abort').fadeOut();
            $('#OK').fadeIn();
            $('.loader').css({
                'width':'0px'
            });
            // disableScreen();
            $.ajax({
                method: "POST",
                url: "/index.php",
                data: { data: 'restart' }
            })
        }
    },30000);
}

function log(str, time){
    if(typeof time == 'undefined') time = 10;
    setTimeout(function () {
        var logs = $('.monitor').html();
        logs += '<span>root@mobility:</span> >_ '+str+'...<br>';
        $('.monitor').html(logs);
    },time);
}


