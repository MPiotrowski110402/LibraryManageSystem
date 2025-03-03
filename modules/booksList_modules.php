<?php
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        reserveBook();
    }
    function booksList(){
        global $conn;
        $query = "SELECT * FROM books";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($result)){
            if($row['available_copies'] > 0){
            echo '<tr>
                    <td>'.$row['title'].'</td>
                    <td>'.$row['author'].'</td>
                    <td>'.$row['genre'].'</td>
                    <td>'.$row['available_copies'].'</td>
                    <td>
                    <form action="#" method="post">
                        <input type="hidden" name="bookId" value="'.$row['id'].'">
                        <input type="submit" name="copiesCount" class="btn btn-primary" value="Zarezerwuj">
                    </form>
                    </td>
                </tr>';
            }
        }
    }
    function reserveBook(){
        if(isset($_POST['copiesCount'])){
            global $conn;
            $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] :0;
            $sql = "SELECT user_id FROM reservations WHERE user_id = ?";
            $stmt = mysqli_prepare($conn,$sql);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) > 0){
                echo "Możesz zarezerwować tylko jedną książkę.";
            }else{
                $bookId = isset($_POST['bookId'])?(int)$_POST['bookId'] :0;
                $query = "UPDATE books SET available_copies = available_copies - 1 WHERE id = ?";
                $stmt = mysqli_prepare($conn,$query);
                mysqli_stmt_bind_param($stmt, "i", $bookId);
                
                if(mysqli_stmt_execute($stmt)){
                    $sql = "INSERT INTO reservations (user_id, book_id, reservation_date, status) 
                    VALUES (?, ?, CURRENT_DATE(), 'active')";  
                    $stmt = mysqli_prepare($conn,$sql);
                    mysqli_stmt_bind_param($stmt, "ii", $user_id, $bookId);
                    mysqli_stmt_execute($stmt);
                    header('Location:../pages/booksList.php');
                    exit();
                }
            }

        }
    }

    function removeExpiredReservations() {
        global $conn;

        $query = "SELECT * FROM reservations 
                  WHERE status = 'active' 
                  AND DATEDIFF(CURRENT_DATE(), reservation_date) > 30";
    
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $bookId = $row['book_id'];
            $reservationId = $row['id'];
            $updateBookQuery = "UPDATE books SET available_copies = available_copies + 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $updateBookQuery);
            mysqli_stmt_bind_param($stmt, "i", $bookId);
            mysqli_stmt_execute($stmt);
            $deleteReservationQuery = "DELETE FROM reservations WHERE id = ?";
            $stmt = mysqli_prepare($conn, $deleteReservationQuery);
            mysqli_stmt_bind_param($stmt, "i", $reservationId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }



?>