<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/29/2017
 * Time: 12:08 PM
 */

class sshModel{

    private $con;
    private $ssh = array(
        'ssh_host' => '',
        'ssh_port' => '22',
        'ssh_user' => '',
        'ssh_pass' => '',
    );

    public function __construct(){
        $this->ssh = (object)$this->ssh;
        $this->con = new Net_SSH2($this->ssh->ssh_host);
        if (!$this->con->login($this->ssh->ssh_user, $this->ssh->ssh_pass)) {
            exit('Login Failed');
        }
    }

    public function run($script){
        if(is_array($script)){
            $res = false;
            for ($i = 0; $i<count($script); $i++){
                $res = $this->con->exec($script[$i]);
            }
            return $res;
        } else {
            return $this->con->exec($script);
        }
    }

}
