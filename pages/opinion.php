<?php
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/modules/opinion_modules.php';


?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteka - Wypożyczenia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/opinion.css">
</head>
<body>
   <!-- Hero Section -->
   <header class="bg-primary text-white text-center py-5">
    <a href="../index.php">
    <h1>System Zarządzania Biblioteką</h1>
    </a>
  </header>
    <div class="container mt-5">
        <div class="card table-container">
            <div class="card-header bg-primary text-white text-center">
                <h3>Wystaw opinię</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Tytuł Książki</th>
                            <th>Autor</th>
                            <th>Gatunek</th>
                            <th>Data Oddania</th>
                            <th>Opóźnienie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php booksList();?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>