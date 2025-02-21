<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';

    if(isset($_SESSION['logined']) && $_SESSION['logined'] === true){
        $_SESSION['logined'] = false;
        $_SESSION = [];
        session_destroy();
    }
    header('Location: /SystemZarządzaniaBiblioteką/pages/login.php');
    exit();
?>
