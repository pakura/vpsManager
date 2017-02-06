<?php
if(isset($_POST['data'])){
    $data = ($_POST['data'] == 'restart')?$_POST['data']:(object)$_POST['data'];
    $project = new project($data);
    $project->createProject();
    $query = "SELECT * FROM projects WHERE name = '".$data->name."'";
    $result = (object)mysqli_fetch_assoc(mysqli_query($con, $query));
    echo json_encode($result);
    die();
}

class project{

    private $project;
    private $user;
    private $ssh;
    private $db;
    public function __construct($project){
        $this->project = $project;
        $this->ssh = new sshModel();
        $this->user = $_SESSION['admin'];
        GLOBAL $con;
        $this->db = $con;
    }

    public function createProject(){
        if($this->project == 'restart'){
            $this->ssh->run('systemctl restart apache2');
            die('ok');
        }
        $query = "SELECT * FROM project WHERE name = '".$this->project->name."'";
        $res = mysqli_query($this->db, $query);
        if(mysqli_num_rows($res)>0){
            echo json_encode(mysqli_fetch_assoc($res));
        }
        $this->createDir();
        $this->createUser();
        $this->createVirtualHost();
        $this->createDB();
        $this->createFiles();
        $this->setPermision();
    }

    private function createDir(){
        $script = array(
            'mkdir /var/www/'.$this->project->name.'/',
            'mkdir /var/www/'.$this->project->name.'/public/',
            'mkdir /var/log/apache2/'.$this->project->name
        );

        return $this->ssh->run($script);
    }

    private function createUser(){
        $pass = generateRandomString();
//        $pass = '123456';
        $script = array(
            "sudo useradd -d /home/".$this->project->name." -p $(perl -e'print crypt(\"".$pass."\", \"cu\")') ".$this->project->name,
            'usermod -aG sudo '.$this->project->name
        );
        $this->project->pass = $pass;
        $sql = "INSERT INTO projects (name, user_id, type, git, project_url, dir, ssh_user, ssh_user_pass) VALUES 
            ('".$this->project->name."', '".$this->user->id."', '".$this->project->type."', '".$this->project->git."',
              '".$this->project->name.".devmobility.com', '/var/www/".$this->project->name."', '".$this->project->name."',
              '".$this->project->pass."')";
        $resSet = mysqli_query($this->db, $sql);
        $this->project->id = mysqli_insert_id($this->db);

        return $this->ssh->run($script);
    }

    private function createVirtualHost(){
        $cmd = '<VirtualHost *:80>
                    ServerAdmin dev@devmobility.com
                    ServerName '.$this->project->name.'.devmobility.com
                    ServerAlias www.'.$this->project->name.'.devmobility.com
                    DocumentRoot /var/www/'.$this->project->name.'/public
                    <Directory "/var/www/'.$this->project->name.'/public">
                      AllowOverride all
                    </Directory>
                    ErrorLog ${APACHE_LOG_DIR}/'.$this->project->name.'/error.log
                    CustomLog ${APACHE_LOG_DIR}/'.$this->project->name.'/access.log combined
                </VirtualHost>';
        $myfile = fopen("/var/www/devmobility/temp/config.conf", "w") or die("Unable to open file!");
        fwrite($myfile, $cmd);
        fclose($myfile);
        $script = array(
            "cp /var/www/devmobility/temp/config.conf /etc/apache2/sites-available/".$this->project->name.".devmobility.conf",
            "a2ensite ".$this->project->name.".devmobility.conf",
            "echo '<?= echo \"Hello World.\"; ?>' /var/www/".$this->project->name."/public/index.php",
        );

//        $script = "echo \"".$cmd."\" >> /etc/apache2/sites-available/".$this->project->name.".devmobility.conf";
        return $this->ssh->run($script);
    }

    private function setPermision(){
        $script = array(
//            "setfacl -m user:".$this->project->name.":rx -R /",
//            "setfacl -m user:".$this->project->name.":rxw -R /var/www/".$this->project->name."/",
            "chmod -R 755 /var/www/".$this->project->name."/",
            "chmod -R 777 /var/www/".$this->project->name."/public/",
            "chmod -R 777 /var/www/".$this->project->name."/storage/",
            "chmod -R 777 /var/www/".$this->project->name."/bootstrap/cache/",
            "chown -R www-data:www-data /var/www/".$this->project->name."/",
        );
        return $this->ssh->run($script);
    }

    private function createDB(){
        $pass = generateRandomString(10);
        $sql = "CREATE USER '".$this->project->name."'@'localhost' IDENTIFIED BY '".$pass."'";
        $resultSet = mysqli_query($this->db, $sql) or die('2 '.mysqli_error($this->db));
        $sql = "CREATE DATABASE `".$this->project->name."` /*!40100 COLLATE 'utf8_general_ci' */";
        $resultSet = mysqli_query($this->db, $sql) or die('1 '.mysqli_error($this->db));
        $sql = "GRANT ALL ON ".$this->project->name.".* TO '".$this->project->name."'@'localhost'";
        $resultSet = mysqli_query($this->db, $sql) or die('3 '.mysqli_error($this->db));
        $sql = "UPDATE projects  set db_user='".$this->project->name."', db_pass='".$pass."', db='".$this->project->name."' WHERE name = '".$this->project->name."'";
        $resultSet = mysqli_query($this->db, $sql) or die('4 '.mysqli_error($this->db));
        return $resultSet;
    }

    private function createFiles(){
        if($this->project->type == 1){
            $script = "unzip /var/www/devmobility/temp/empty.zip -d /var/www/".$this->project->name."/public/";
        } elseif ($this->project->type == 2){
            $script = "unzip /var/www/devmobility/temp/laravel.zip -d /var/www/".$this->project->name."/";
        } elseif ($this->project->type == 3){
            $script = "unzip /var/www/devmobility/temp/cms.zip -d /var/www/".$this->project->name."/";
        }
        return $this->ssh->run($script);
    }

}

function generateRandomString($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#%&()';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
