<?php
    //login
    if(isset($_POST['user']) && isset($_POST['pass']) && $_POST['user'] != ''){
        $user = $_POST['user'];
        $pass = md5($_POST['pass'].md5('mobility2017'));
        $query = "SELECT * FROM `users` WHERE `username` = '$user' AND `password` = '$pass'";
        $result = mysqli_query($con, $query);
        if(mysqli_num_rows($result) > 0){
            $user = (object)mysqli_fetch_assoc($result);
            $_SESSION['temp'] = $user;
            if($config->sms_access == true){
                $tempcode = mt_rand(1000, 9999);
                $res = mysqli_query($con, "UPDATE `users` SET `last_phone_token` = '$tempcode' WHERE `id` = $user->id");
                $sms = sendSMS($user, $tempcode);
                $sms = json_decode($sms);
//            header('Location: index.php');
                $phone = $user->phone;
            } else {
                $_SESSION['admin'] = $user;
                header('Location: index.php');
            }
        } else {
            $Loginerror = 'Access denied. Invalid User or Password';
        }
    }

    if(isset($_POST['sms']) && $_POST['sms'] != ''){
        $sms = $_POST['sms'];
        $user = $_SESSION['temp'];
        if(isset($user->id)){
            $smsres = mysqli_query($con, "SELECT last_phone_token FROM users WHERE id = $user->id AND last_phone_token = $sms");
            if(mysqli_num_rows($smsres)<=0){
                $phone = $user->phone;
                $Loginerror = 'Access denied. Invalid SMS Code';
            } else {
                $_SESSION['admin'] = $user;
                header('Location: index.php');
            }
        }
    }


    function sendSMS($user, $code){
        $curl = "curl 'https://api.twilio.com/2010-04-01/Accounts/ACe13d0b29fdb02dbb7ae1006c167dc341/Messages.json' -X POST --data-urlencode 'To=$user->phone' --data-urlencode 'From=+16313362253' --data-urlencode 'MessagingServiceSid=MG650326d6ffc4511be5f227851656b4c2' --data-urlencode 'Body=Code: $code' -u ACe13d0b29fdb02dbb7ae1006c167dc341:faa06d36aa55198b8a76704f02708b3b";
        $output = shell_exec($curl);
        return $output;
    }

?>