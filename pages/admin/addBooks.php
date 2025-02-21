<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/modules/admin/addBooks_modules.php';
if(isset($_GET['id'])){
$book_id = $_GET['id'];
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Zarządzania Biblioteką - Formularz Oddania Książki</title>

    <!-- Link do Bootstrapa 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .hero-section {
            background-color: #007bff;
            color: white;
            padding: 50px 0;
            text-align: center;
        }

        .form-container, .user-info {
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
        }

        .user-info {
            display: none;
        }
        textarea{
            width: 100%;
            height: 100px;
            resize: none;
        }
        a {
            color: white;
            text-decoration: none;
            background-color: #007bff;
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <a href="/SystemZarządzaniaBiblioteką/index.php" class="btn btn-light">
        <h1>Formularz dodania książki</h1>
        <p>Uzupełnij formularz, aby dodać książkę</p>
        </a>
    </div>

    <div class="container mt-12">
        <div class="row">
            <!-- Formularz (8 jednostek) -->
            <?php if(isset($book_id)): ?>
            <div class="col-md-12">
                <div class="form-container">
                    <h3>Wpisz nowe dane:</h3>
                    <form action="/SystemZarządzaniaBiblioteką/modules/admin/addBooks_modules.php" method="POST">
                        <div class="mb-4">
                            <label for="title" class="form-label">Tytuł</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-4">
                            <label for="author" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="author" name="author" required>
                        </div>
                        <div class="mb-4">
                            <label for="genre" class="form-label">Gatunek</label>
                            <input type="text" class="form-control" id="genre" name="genre" required>
                        </div>
                        <div class="mb-4">
                            <label for="published_date" class="form-label">Data Publikacji</label>
                            <input type="date" class="form-control" id="published_date" name="published_date" required>
                        </div>
                        <div class="mb-4">
                            <p for="description" class="form-label">Opis</p>
                            <textarea name="description" id="description"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="count" class="form-label">Ilość sztuk</label>
                            <input type="number" class="form-control" id="count" name="count" required>
                        </div>
                        <input type="hidden" name="book_id" value="<?php echo $book_id;?>">
                        <input type="submit" name="edit_book_form" class="btn btn-primary" value="Edytuj książkę">
                        <p name="error_reports"></p>
                    </form>
                </div>
            </div>
            <?php endif;?>
            <?php if(!isset($book_id)):?>
            <div class="col-md-12">
                <div class="form-container">
                    <h3>Wypełnij formularz:</h3>
                    <form action="/SystemZarządzaniaBiblioteką/modules/admin/addBooks_modules.php" method="POST">
                        <div class="mb-4">
                            <label for="title" class="form-label">Tytuł</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-4">
                            <label for="author" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="author" name="author" required>
                        </div>
                        <div class="mb-4">
                            <label for="genre" class="form-label">Gatunek</label>
                            <input type="text" class="form-control" id="genre" name="genre" required>
                        </div>
                        <div class="mb-4">
                            <label for="published_date" class="form-label">Data Publikacji</label>
                            <input type="date" class="form-control" id="published_date" name="published_date" required>
                        </div>
                        <div class="mb-4">
                            <p for="description" class="form-label">Opis</p>
                            <textarea name="description" id="description"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="count" class="form-label">Ilość sztuk</label>
                            <input type="number" class="form-control" id="count" name="count" required>
                        </div>
                        <input type="submit" name="dodaj_ksiazke" class="btn btn-primary" value="Dodaj książki">
                        <p name="error_reports"></p>
                    </form>
                </div>
            </div>
            <?php endif;?>
            <div class="container mt-5">
    <h3 class="mb-4">Lista książek</h3>
    <table class="table table-striped table-bordered">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Tytuł</th>
                <th>Autor</th>
                <th>Gatunek</th>
                <th>Data Publikacji</th>
                <th>Ilość</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php displayBooks(); ?>
        </tbody>
    </table>
</div>


    <div class="footer">
        <p>&copy; 2025 System Zarządzania Biblioteką</p>
    </div>

    <!-- Skrypt Bootstrapa 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
