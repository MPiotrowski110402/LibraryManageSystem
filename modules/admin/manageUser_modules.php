<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';

    function userData(){
        global $conn;
        $cid = htmlspecialchars(trim($_SESSION['cid']));
        $query = "SELECT * FROM users WHERE cid = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $cid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result)){
        if($row['role_id'] == '2'){
            $role = 'user';
        }else{
            $role = 'admin';
        }
        if($row['newsletter_announcer'] == '1'){
            $newsletter = 'Zapisany na newsletter';
        }else{
            $newsletter = 'Nie zapisany na newsletter';
        }
        $return = '
            <h2>Imię i nazwisko: '.$row['first_name'].$row['last_name'].'</h2>
            <p>email: '.$row['email'].'</p>
            <p>Rola: '.$role.'</p>
            <p>Dzień rejestracji: '.$row['registration_date'].'</p>
            <p>Cid: '.$row['cid'].'</p>
            <p>Data urodzin: '.$row['birth_date'].'</p>
            <p>Newsletter: '.$newsletter.'</p>
        ';
        return $return;
        }else{
            return "Brak użytkownika z tym CID!";
        }
    }

    function booksUserBorrowings(){

        global $conn;
        $cid = $_SESSION['cid'];
        $query = "SELECT 
            b.user_id, 
            b.book_id, 
            b.borrow_date, 
            b.return_date, 
            b.due_date, 
            b.returned, 
            bk.id AS book_id, 
            bk.title, 
            bk.author, 
            bk.genre,
            DATEDIFF(CURRENT_DATE(), IFNULL(b.due_date, CURRENT_DATE())) AS days_late
        FROM borrowings b
        JOIN books bk ON b.book_id = bk.id
        WHERE b.user_id = (SELECT id FROM users WHERE cid = ?)
        ORDER BY b.id ASC";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $cid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                if($row['returned'] == 0){
                $oplata = $row['days_late'] * 0.30;
                if($oplata<0){
                    $oplata = 0;
                }
                    echo '
                            <div class="col-md-4">
                                <div class="card book-card">
                                    <div class="card-body">
                                        <h5 class="card-title">'.$row['title'].'</h5>
                                        <p class="card-text">Autor: '.$row['author'].'</p>
                                        <p class="card-text">Gatunek: '.$row['genre'].'</p>
                                        <p class="card-text">Data wypożyczenia: '.$row['borrow_date'].'</p>
                                        <p class="card-text">Termin zwrotu: '.$row['due_date'].'</p>
                                        <p class="card-text">Czas opóźnienia: '.$row['days_late'].' dzień</p>
                                        <p class="card-text">Dodatkowa opłata: '.$oplata.'zł</p>
                                        <form method="post" action="/SystemZarządzaniaBiblioteką/modules/admin/manageUser_modules.php">
                                        <input type="hidden" name="book_id" value="'.$row['book_id'].'">
                                        <input type="submit" name="przedluz" value="przedluz" class="btn btn-warning">
                                        <input type="submit" name="oddaj" value="oddaj" class="btn btn-success">
                                        </form>
                                    </div>
                                </div>
                            </div> 
                    ';
                }
            }
        }
    }
    function historyUserBorrowings(){

        global $conn;
        $cid = $_SESSION['cid'];
        $query = "SELECT 
            b.user_id, 
            b.book_id, 
            b.borrow_date, 
            b.return_date, 
            b.due_date, 
            b.returned, 
            bk.id AS book_id, 
            bk.title, 
            bk.author, 
            bk.genre,
            DATEDIFF(IFNULL(b.return_date, CURRENT_DATE()), b.due_date) AS days_late
        FROM borrowings b
        JOIN books bk ON b.book_id = bk.id
        WHERE b.user_id = (SELECT id FROM users WHERE cid = ?)
        ORDER BY b.id ASC";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $cid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                if($row['returned'] == 1){
                $oplata = $row['days_late'] * 0.30;
                if($oplata<0){
                    $oplata = 0;
                }
                    echo '
                            <div class="col-md-4">
                                <div class="card book-card">
                                    <div class="card-body">
                                        <h5 class="card-title">'.$row['title'].'</h5>
                                        <p class="card-text">Autor: '.$row['author'].'</p>
                                        <p class="card-text">Gatunek: '.$row['genre'].'</p>
                                        <p class="card-text">Data wypożyczenia: '.$row['borrow_date'].'</p>
                                        <p class="card-text">Termin zwrotu: '.$row['due_date'].'</p>
                                        <p class="card-text">Czas opóźnienia: '.$row['days_late'].'</p>
                                        <p class="card-text">Dodatkowa opłata: '.$oplata.'zł</p>
                                    </div>
                                </div>
                            </div> 
                    ';
                }
            }
        }
    }
    if(isset($_POST['przedluz'])){
        $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] :0;
        $query = "UPDATE borrowings SET due_date = DATE_ADD(due_date, INTERVAL 7 DAY) WHERE book_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $book_id);
        mysqli_stmt_execute($stmt);
        header('Location: /SystemZarządzaniaBiblioteką/pages/admin/manageUser.php?cid=' . urlencode($_SESSION['cid']));
        exit();
    }
    if(isset($_POST['oddaj'])){
        $cid = htmlspecialchars(trim($_SESSION['cid']));
        $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id']:0;
        $user_query = "SELECT id FROM users WHERE cid = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $user_query);
        mysqli_stmt_bind_param($stmt, "s", $cid);
        mysqli_stmt_execute($stmt);
        $user_result = mysqli_stmt_get_result($stmt);
        if ($user_result && mysqli_num_rows($user_result) > 0) {
            $user_row = mysqli_fetch_assoc($user_result);
            $user_id = $user_row['id'];
            $query = "UPDATE borrowings
                    SET returned = 1, return_date = NOW()
                    WHERE id = (
                        SELECT id FROM (
                            SELECT id FROM borrowings
                            WHERE user_id = ? AND book_id = ? AND returned = 0
                            ORDER BY borrow_date ASC LIMIT 1
                        ) AS subquery
                    )";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ii", $user_id, $book_id);
            if(mysqli_stmt_execute($stmt)){
                $update_book_query = "UPDATE books SET available_copies = available_copies + 1 WHERE id = ?";
                $stmt = mysqli_prepare($conn, $update_book_query);
                mysqli_stmt_bind_param($stmt, "i", $book_id);
                mysqli_stmt_execute($stmt);
            }
        }
        header('Location: /SystemZarządzaniaBiblioteką/pages/admin/manageUser.php?cid=' . urlencode($_SESSION['cid']));
        exit();
    }

        

?>