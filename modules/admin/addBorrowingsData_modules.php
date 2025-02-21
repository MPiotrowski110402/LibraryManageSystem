<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';

function bookList(){
    global $conn;
    $query = "SELECT * FROM books";
    $result = mysqli_query($conn, $query);
    $options = '';
    while($row = mysqli_fetch_assoc($result)){
        if($row['available_copies'] > 0){
            $title = htmlspecialchars(strip_tags($row['title']));
            $id = htmlspecialchars($row['id']);
            $options .= '<option value="' . $id . '">' . $title . '</option>';
        }
    }
    return $options;
    }
    if(isset($_POST['wypozycz'])){
        $cid = $_POST['cid'];
        $bookId = $_POST['book_id'];
        $due_time = $_POST['return_date'];
        $sql = "SELECT id FROM users WHERE cid = '$cid'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $userId = $row['id'];
            $sql = "INSERT INTO borrowings (user_id, book_id, borrow_date, due_date, returned)  
            VALUES ('$userId', '$bookId', CURRENT_DATE(), '$due_time', 0)";
            mysqli_query($conn, $sql);
            $query = "UPDATE books SET available_copies = available_copies - 1 WHERE id = '$bookId'";
            mysqli_query($conn, $query);
            header('Location:../../index.php');
            exit();
        }
    }

?>