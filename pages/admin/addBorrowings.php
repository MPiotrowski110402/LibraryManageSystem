<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/modules/admin/addBorrowingsData_modules.php';


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

    <div class="container mt-4">
        <div class="row">
            <!-- Formularz (8 jednostek) -->
            <div class="col-md-8">
                <div class="form-container">
                    <h3>Wypełnij formularz:</h3>
                    <form action="/SystemZarządzaniaBiblioteką/modules/admin/addBorrowingsData_modules.php" method="POST">
                        <div class="mb-3">
                            <label for="cid" class="form-label">Cid użytkownika</label>
                            <input type="text" class="form-control" id="cid" name="cid" required>
                        </div>

                        <div class="mb-3">
                            <label for="book_id" class="form-label">Wybierz książkę</label>
                            <select class="form-select" id="book_id" name="book_id" required>
                                <option value="" disabled selected>Wybierz książkę</option>
                                <?php  echo bookList(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="return_date" class="form-label">Data oddania</label>
                            <input type="date" class="form-control" id="return_date" name="return_date" required>
                        </div>

                        <input type="submit" name="wypozycz" class="btn btn-primary" value="Wypożycz książkę">
                    </form>
                </div>
            </div>

            <!-- Informacje o użytkowniku (4 jednostki) -->
            <div class="col-md-4">
                <div class="user-info" id="user-info">
                    <h4>Informacje o użytkowniku</h4>
                    <p><strong>Imię i nazwisko:</strong> <span id="user-name"></span></p>
                    <p><strong>Email:</strong> <span id="user-email"></span></p>
                    <p><strong>Ilość wypożyczonych książek:</strong> <span id="borrowed-books"></span></p>
                    <p><strong>Aktualna rezerwacja:</strong> <span id="reservation-books"></span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 System Zarządzania Biblioteką</p>
    </div>

    <!-- Skrypt Bootstrapa 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function () {
    $('#cid').on('input', function () {
        let cid = $(this).val();
        if (cid.length > 0) {
            $.ajax({
                url: '/SystemZarządzaniaBiblioteką/modules/admin/addBorrowings_modules.php',
                type: 'POST',
                data: { cid: cid },
                success: function (response) {
                    console.log(response); 
                    if (response.success) {
                        $('#user-name').text(response.name);
                        $('#user-email').text(response.email);
                        $('#borrowed-books').text(response.borrowed_books);
                        $('#reservation-books').text(response.reservation_books);
                        $('#user-info').fadeIn();
                    } else {
                        $('#user-info').fadeOut();
                    }
                }
            });
        } else {
            $('#user-info').fadeOut();
        }
    });
});

    </script>
</body>

</html>
