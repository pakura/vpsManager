<?php
if(!isset($_SESSION['admin'])){
    header('Location: /login.php');
}
?>