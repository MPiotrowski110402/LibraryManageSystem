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
        $bookId = isset($_POST['book_id'])? (int)$_POST['book_id']: 0;
        $due_time = $_POST['return_date'];
        if(empty($due_time)){
            echo "Proszę podać poprawną datę zwracania";
            return;
        }
        $sql = "SELECT id FROM users WHERE cid = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if(!$stmt) {
            echo "Błąd przy przygotowywaniu zapytania". mysqli_error($conn);
            exit();
        }

        mysqli_stmt_bind_param($stmt, 's', $cid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $userId = $row['id'];


            $sql = "INSERT INTO borrowings (user_id, book_id, borrow_date, due_date, returned)  
            VALUES (?, ?, CURRENT_DATE(), ?, 0)";
            $stmt = mysqli_prepare($conn, $sql);
            if(!$stmt) {
                echo "Błąd przy przygotowywaniu zapytania". mysqli_error($conn);
                exit();
            }
            mysqli_stmt_bind_param($stmt, 'iis', $userId, $bookId, $due_time);
            $execute = mysqli_stmt_execute($stmt);
            if($execute){
                $query = "UPDATE books SET available_copies = available_copies - 1 WHERE id = ?";
                $stmt = mysqli_prepare($conn,$query);
                if(!$stmt) {
                    echo "Błąd przy przygotowywaniu zapytania". mysqli_error($conn);
                    exit();
                }else{
                mysqli_stmt_bind_param($stmt, 'i', $bookId);
                mysqli_stmt_execute($stmt);
                }

                $deleteReservationSql = "DELETE FROM reservations WHERE book_id = ? AND user_id = ?";
                $stmt = mysqli_prepare($conn, $deleteReservationSql);
                mysqli_stmt_bind_param($stmt, 'ii', $bookId, $userId);
                mysqli_stmt_execute($stmt);
                header('Location:../../index.php');
                exit();
            }else{
                echo "Wystąpił błąd przy wypożyczeniu książki";
            }
        }else{
            echo "Nie znaleziono użytkownika o podanym id";
        }
    }

?>