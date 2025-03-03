<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';




    function displayName(){
        $fist_name = $_SESSION['first_name'];
        $last_name = $_SESSION['last_name'];
        $result = $fist_name.' '.$last_name;
        return $result;
    }
    function displayCid(){
        $cid = $_SESSION['cid'];
        return $cid;
    }
    function displayList(){
        $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] :0;
        global $conn;
        $sql = "SELECT borrowings.*, books.title, books.author, books.genre 
                FROM borrowings 
                JOIN books ON borrowings.book_id = books.id 
                WHERE borrowings.user_id = ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row = mysqli_fetch_assoc($result)){
            if($row['returned'] == 0){
                // Wywołanie funkcji expiredCount() z przekazaniem book_id
                $id = $row['id'];
                $zwrot = expiredCount($id);
                echo  '<tr>
                            <td>'.$row['title'].'</td>
                            <td>'.$row['borrow_date'].'</td>
                            <td>'.$row['due_date'].'</td>
                            <td>'.$zwrot.'</td>
                        </tr>';
            }
        }
    }
    
    function expiredCount($id){
        $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] :0;
        global $conn;

        $sql = "SELECT DATEDIFF(CURDATE(),due_date) AS days_late
                FROM borrowings
                WHERE user_id = ?
                AND id = ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $daysLate = $row['days_late'];
            if($daysLate>0){
            $zwrot = $daysLate * 0.30;  // Naliczanie opłaty
            return number_format($zwrot, 2, '.', '') . ' zł';
            }else{
                return '0 zł';   
            }
        }else{
            return '0 zł';
        }
    }
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'update_newsletter_agreement') {
            $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] :0;
    
            $sql_check = "SELECT newsletter_announcer FROM users WHERE id = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param('i', $user_id);
            $stmt_check->execute();
            $result_check = $stmt->get_result();
            $user = mysqli_fetch_assoc($result_check);
    
            if ($user['newsletter_announcer'] == 1) {
                echo 'success'; 
            } else {
                $sql_update = "UPDATE users SET newsletter_announcer = 1 WHERE id = '$user_id'";
                if (mysqli_query($conn, $sql_update)) {
                    echo 'success'; 
                } else {
                    echo 'error'; 
                }
            }
        }
    }
    function showNotification(){
        $id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] :0;
        global $conn;
        $sql = "SELECT newsletter_announcer FROM users WHERE id = ?";
        $stmt = $conn ->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        if($row['newsletter_announcer'] == 0){
            echo '<div id="notification-bar" class="notification-bar">
                    <div class="notification-content">
                        <p>Dostawaj powiadomienia mailem o zbliżającym się końcu terminu wypożyczenia!</p>
                        <button class="btn" id="accept-notifications">Zgadzam się na powiadomienia</button>
                    </div>
                </div>';
        }
    }
    function expiredReservation(){
        $id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] :0;
        global $conn;
        $sql = "SELECT 
            books.title AS book_title, 
            reservations.reservation_date,
            reservations.book_id,
            reservations.status,
            DATEDIFF(CURRENT_DATE, reservations.reservation_date) AS days_since_reservation
        FROM 
            reservations
        JOIN 
            books ON reservations.book_id = books.id
        WHERE 
            reservations.user_id = ?";
        $stmt = $conn ->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $expiredTime = 30 - $row['days_since_reservation'];
                if($expiredTime >= 0){
                echo '
                    <h5 class="card-title">Twoja rezerwacja</h5>
                    <p class="card-text">'.$row['book_title'].'</p>
                    <p class="card-text">Anuluje się za: '.$expiredTime.' dni</p>
                    <a href="#" id="btn-decline-reservation" class="btn btn-primary" data-book-id="'.$row['book_id'].'">Anuluj teraz</a>
                    ';
                }else{
                    $book_id = $row['book_id'];
                    $sql = "DELETE FROM reservations WHERE user_id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $sql = "UPDATE books SET available_copies = available_copies + 1 WHERE id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    echo '
                    <h5 class="card-title">Twoja rezerwacja</h5>
                    <p class="card-text">Aktualnie brak</p>
                    <a href="pages/booksList.php" id="btn-decline" class="btn btn-primary">Zarezerwuj</a>
                    ';
                }
            }
        }else{
            echo '
            <h5 class="card-title">Twoja rezerwacja</h5>
            <p class="card-text">Aktualnie brak</p>
                <a href="pages/booksList.php" id="btn-decline" class="btn btn-primary">Zarezerwuj</a>
            ';
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'delete_reservation') {
            $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] :0;
            $book_id = (int)$_POST['book_id'];

            $sql = "DELETE FROM reservations WHERE user_id = ? AND book_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $user_id, $book_id);
            
            if($stmt->execute()){
                $sql = "UPDATE books SET available_copies = available_copies + 1 WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $book_id);
                if ($stmt->execute()) {
                    echo 'success'; 
                } else {
                    echo 'error';    
                }
            }else{
                echo 'error';
            }
        }
    }
    function countUser(){
        global $conn;
        $query = "SELECT COUNT(*) AS total_users FROM users";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo $row['total_users'];
    }
    function countBorrowings(){
        global $conn;
        $query = "SELECT COUNT(*) AS total_borrowings FROM borrowings";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo $row['total_borrowings'];
    }
    function countBooks(){
        global $conn;
        $query = "SELECT COUNT(*) AS total_books FROM books";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo $row['total_books'];
    }
    function countReviews(){
        global $conn;
        $query = "SELECT COUNT(*) AS total_reviews FROM reviews";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        echo $row['total_reviews'];
    }

?>