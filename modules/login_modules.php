<?php
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';
// Zamierzenie nie zabezpieczone. 
if(isset($_POST['login_btn'])){
    global $conn;
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(!empty($email) && !empty($password)){
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            if($row = mysqli_fetch_assoc($result)){
                if($row['password'] == $password){
                    $_SESSION['zalogowany'] = true;
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name'] = $row['last_name'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['cid'] = $row['cid'];
                    $_SESSION['role'] = $row['role_id'];
                    $_SESSION['registration_date'] = $row['registration_date'];
                    header('Location: ../index.php');
                    exit();
                } else {
                    echo "Zły email lub hasło!";
                }
            }else{echo "Błąd bazy danych!";}
        }else{echo "Nie znaleziono użytkownika!";}
    }else{echo "Pole email i hasło nie mogą być puste!";}
}else{echo "Nie wybrano żadnego przycisku!";}

if(isset($_POST['registered_btn'])){
    global $conn;
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordRepeat = $_POST['passwordRepeat'];
    $birth_date = $_POST['date'];
    $role_id = 2;
    $registration_date = date('Y-m-d H:i:s');
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        echo "Użytkownik o podanym adresem email już istnieje!";
    }else{
        $cid = "#" . rand(100000, 999999);
        $sql = "SELECT * FROM users WHERE cid = '$cid'";
        $result = mysqli_query($conn, $sql);
        while(mysqli_num_rows($result) > 0){
            $cid = "#" . rand(100000, 999999);
            $sql = "SELECT * FROM users WHERE cid = '$cid'";
            $result = mysqli_query($conn, $sql);
        }
        if($password === $passwordRepeat){
            $sql = "INSERT INTO users (first_name, last_name, email, password, role_id, registration_date, cid, birth_date) 
        VALUES ('$first_name', '$last_name', '$email', '$password', '$role_id', '$registration_date', '$cid', '$birth_date')";
            if(mysqli_query($conn, $sql)){
                header('Location: ../pages/login.php');
                exit();
            }else{echo "Błąd w przetwarzaniu danych!";}
        }
    }
}

?>