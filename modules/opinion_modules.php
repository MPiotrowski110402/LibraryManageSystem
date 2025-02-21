<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    review();  
}

function booksList() {
    global $conn;
    $query = "SELECT 
        borrowings.id AS borrowing_id, 
        borrowings.borrow_date, 
        borrowings.return_date,  
        borrowings.book_id,
        borrowings.due_date,
        books.title AS book_title, 
        books.author AS book_author, 
        books.genre AS book_genre
    FROM borrowings
    JOIN books ON borrowings.book_id = books.id
    LEFT JOIN reviews ON borrowings.book_id = reviews.book_id AND reviews.user_id = '".$_SESSION['user_id']."'
    WHERE borrowings.user_id = '".$_SESSION['user_id']."' 
    AND borrowings.returned = 1
    AND reviews.book_id IS NULL  -- Wybieramy tylko książki, które nie mają recenzji
    ORDER BY borrowings.borrow_date DESC;";
    $result = mysqli_query($conn, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $daysLate = due_date($row['return_date'], $row['due_date']);
        echo '  <tr>
                    <td>'.$row['book_title'].'</td>
                    <td>'.$row['book_author'].'</td>
                    <td>'.$row['book_genre'].'</td>
                    <td>'.$row['return_date'].'</td>
                    <td>'.$daysLate.'</td>
                </tr>
                <tr>
                    <form method="post">
                    <td colspan="3">
                        <textarea class="form-control" name="review_'.$row['borrowing_id'].'" rows="2" placeholder="Dodaj swoją recenzję..."></textarea>
                    </td>
                    <td>
                        <div class="rating">
                            <input type="radio" id="star1_'.$row['borrowing_id'].'" name="rating_'.$row['borrowing_id'].'" value="1"><label for="star1_'.$row['borrowing_id'].'">★</label>
                            <input type="radio" id="star2_'.$row['borrowing_id'].'" name="rating_'.$row['borrowing_id'].'" value="2"><label for="star2_'.$row['borrowing_id'].'">★</label>
                            <input type="radio" id="star3_'.$row['borrowing_id'].'" name="rating_'.$row['borrowing_id'].'" value="3"><label for="star3_'.$row['borrowing_id'].'">★</label>
                            <input type="radio" id="star4_'.$row['borrowing_id'].'" name="rating_'.$row['borrowing_id'].'" value="4"><label for="star4_'.$row['borrowing_id'].'">★</label>
                            <input type="radio" id="star5_'.$row['borrowing_id'].'" name="rating_'.$row['borrowing_id'].'" value="5"><label for="star5_'.$row['borrowing_id'].'">★</label>
                        </div>
                    </td>
                    <td>
                        <input type="hidden" name="book_id" value="'.$row['book_id'].'">
                        <input type="hidden" name="borrowing_id" value="'.$row['borrowing_id'].'">
                        <input type="submit" name="btn_review_'.$row['borrowing_id'].'" class="btn btn-success" value="Wyślij">
                    </td>
                    </form>
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

function review(){
    global $conn;
    foreach ($_POST as $key => $value){
        if(strpos($key, 'btn_review') === 0){
            $borrowing_id = str_replace('btn_review_', '', $key);
            $review = isset($_POST['review_' . $borrowing_id]) ? trim($_POST['review_' . $borrowing_id]) : '';
            $rating = isset($_POST['rating_' . $borrowing_id]) ? $_POST['rating_' . $borrowing_id] : '';
            $user_id = $_SESSION['user_id'];
            $book_id = $_POST['book_id'];

            if (strlen($review) == 0 || strlen($rating) == 0) {
                echo "Proszę wypełnić wszystkie pola: recenzję oraz ocenę.";
                return;
            }
            if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
                echo "Ocena musi być liczbą między 1 a 5.";
                return;
            }
            $sql = "SELECT * FROM reviews WHERE user_id = '$user_id' AND book_id = '$book_id'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0){
                echo "Już posiadasz recenzję dla tej książki.";
            } else {
                $query = "INSERT INTO reviews (user_id, book_id, rating, review_text, review_date) 
                      VALUES ('$user_id', '$book_id', '$rating', '$review', CURRENT_DATE())";   
                $result = mysqli_query($conn, $query);
                if($result){
                    echo "Recenzja została dodana.";
                } else {
                    echo "Wystąpił błąd podczas dodawania recenzji.";
                }
            }
        }
    }
}
?>