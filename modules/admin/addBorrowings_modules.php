<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';
header('Content-Type: application/json');

if (isset($_POST['cid'])) {
    $cid = $_POST['cid'];
    
    $query = "SELECT first_name, last_name, email, (SELECT COUNT(*) FROM borrowings WHERE user_id = users.id AND returned = 0) AS borrowed_books FROM users WHERE cid = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $cid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $sql = "SELECT books.title
            FROM reservations
            JOIN books ON reservations.book_id = books.id
            WHERE reservations.user_id = (SELECT id FROM users WHERE cid = ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $cid);
    mysqli_stmt_execute($stmt);
    $result2 = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $books = mysqli_num_rows($result2) > 0 ? mysqli_fetch_assoc($result2)['title'] : 'Brak rezerwacji';
            echo json_encode([
                'success' => true,
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'email' => $user['email'],
                'borrowed_books' => $user['borrowed_books'],
                'reservation_books' => $books
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nie znaleziono użytkownika']);
        }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie']);
}

if (isset($_POST['get_books'])) {
    echo json_encode(['success' => true, 'books' => bookList()]);
    exit();
}

?>