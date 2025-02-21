<?php
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

    }
    function booksList() {
        global $conn;
        $query = "SELECT 
                    borrowings.id AS borrowing_id, 
                    borrowings.borrow_date, 
                    borrowings.return_date,  
                    borrowings.due_date,
                    books.title AS book_title, 
                    books.author AS book_author, 
                    books.genre AS book_genre
                FROM borrowings
                JOIN books ON borrowings.book_id = books.id
                WHERE borrowings.user_id = '".$_SESSION['user_id']."' AND borrowings.returned = 1
                ORDER BY borrowings.borrow_date DESC;";
        $result = mysqli_query($conn, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $daysLate = due_date($row['return_date'], $row['due_date']);
            
            echo '<tr>
                    <td>'.$row['book_title'].'</td>
                    <td>'.$row['book_author'].'</td>
                    <td>'.$row['book_genre'].'</td>
                    <td>'.$row['return_date'].'</td>
                    <td>'.$daysLate.'</td>  
                </tr>';
        }
    }
    function due_date($returnDate, $dueDate) {
        if ($returnDate > $dueDate) {
            $diff = strtotime($returnDate) - strtotime($dueDate);
            $daysLate = floor($diff / (60 * 60 * 24));
            return $daysLate;
        }
        return 0; 
    }



?>