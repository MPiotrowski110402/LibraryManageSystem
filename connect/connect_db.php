<?php
    include('session.php');
    $login = 'localhost';
    $username = 'root';
    $password = '123';
    $db_name = 'librarysystem';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($login, $username, $password, $db_name);

?>