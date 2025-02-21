<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';


if (isset($_POST['dodaj_ksiazke'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    $count = $_POST['count'];
    $published_date = $_POST['published_date'];
    if(empty($title) || empty($author) || empty($genre) || empty($description) || empty($published_date) || empty($count)) {
        echo '<p name="error_reports">Wszystkie Pola muszą być wypełnione</p>';
    }else{
        $query = "INSERT INTO books (title, author, genre, description,published_date, available_copies) VALUES ('$title', '$author', '$genre', '$description','$published_date', '$count')";
        mysqli_query($conn, $query);
        header('Location: /SystemZarządzaniaBiblioteką/index.php');
    }
}


function displayBooks() {
    global $conn;
    $query = "SELECT * FROM books";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo '
        <tr>
            <td>'. $row['id']. '</td>
            <td>'. $row['title']. '</td>
            <td>'. $row['author']. '</td>
            <td>'. $row['genre']. '</td>
            <td>'. $row['published_date']. '</td>
            <td>'. $row['available_copies']. '</td>
            <td>
                <form action="/SystemZarządzaniaBiblioteką/modules/admin/addBooks_modules.php" method="post">
                    <input type="hidden" name="id" value="'. $row['id']. '">
                    <input type="submit" value="Edytuj" class="btn btn-warning btn-sm" name="edit_books">
                    <input type="submit" value="Usuń" class="btn btn-danger btn-sm" name="delete_book">
                </form>
            </td>
    </tr>';
    }
}

if(isset($_POST['edit_books'])) {
    $id = $_POST['id'];
    header('Location: /SystemZarządzaniaBiblioteką/pages/admin/addBooks.php?id='. $id);
    exit();
}
if(isset($_POST['delete_book'])) {
    global $conn;
    $id = $_POST['id'];
    $sql = "SELECT * FROM borrowings WHERE book_id = '$id'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
        echo '<p name="error_reports">Książka jest wypożyczana, nie można jej usunąć</p>';
        header('Location: /SystemZarządzaniaBiblioteką/pages/admin/addBooks.php');
        exit();
    }else{
    $query = "DELETE FROM books WHERE id = '$id'";
    mysqli_query($conn, $query);
    header('Location: /SystemZarządzaniaBiblioteką/pages/admin/addBooks.php');
    exit();
    }
}
if(isset($_POST['edit_book_form'])) {


    global $conn;
    $id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    $published_date = $_POST['published_date'];
    $available_copies = $_POST['count'];
    $query = "UPDATE books SET title='$title', author='$author', genre='$genre', description='$description', published_date='$published_date', available_copies='$available_copies' WHERE id='$id'";
    if(mysqli_query($conn, $query)){
        header('Location: /SystemZarządzaniaBiblioteką/pages/admin/addBooks.php');
        exit();
    }
}
?>