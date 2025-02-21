<?php
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/modules/admin/manageUser_modules.php';

   if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $_SESSION['cid'] = $_GET['cid'];
   }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Użytkownika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .user-info {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .book-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <a href="/SystemZarządzaniaBiblioteką/index.php" class="btn btn-light">
        <h1>Powróc do strony głównej</h1>
        </a>
    <div class="container mt-4">
        <!-- Sekcja użytkownika -->
        <div class="user-info">
            <?php echo userData(); ?>
        </div>
        
        <!-- Lista wypożyczonych książek -->
        <h3 class="mt-4">Twoje wypożyczone książki</h3>
        <div class="row">
            <?php  booksUserBorrowings();?>
        </div>
        <h3 class="mt-4">Historia wypożyczeń</h3>
        <div class="row">
            <?php  historyUserBorrowings();?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>