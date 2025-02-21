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
            $sql = "SELECT user_id FROM reservations WHERE user_id = '".$_SESSION['user_id']."'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                echo "Możesz zarezerwować tylko jedną książkę.";
            }else{
                $bookId = $_POST['bookId'];
                $query = "UPDATE books SET available_copies = available_copies - 1 WHERE id = '$bookId'";
                if(mysqli_query($conn, $query)){
                    $sql = "INSERT INTO reservations (user_id, book_id, reservation_date, status) 
                    VALUES ('".$_SESSION['user_id']."', '$bookId', CURRENT_DATE(), 'active')";  
                    mysqli_query($conn, $sql);
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
            $updateBookQuery = "UPDATE books SET available_copies = available_copies + 1 WHERE id = '$bookId'";
            mysqli_query($conn, $updateBookQuery);
            $deleteReservationQuery = "DELETE FROM reservations WHERE id = '".$row['id']."'";
            mysqli_query($conn, $deleteReservationQuery);
        }
    }



?>