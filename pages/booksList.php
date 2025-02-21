<?php
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/connect_db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/connect/session.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SystemZarządzaniaBiblioteką/modules/booksList_modules.php';

removeExpiredReservations();
?>


<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista Książek - System Zarządzania Biblioteką</title>
  <link rel="stylesheet" href="../styles/booksList_styles.css">
</head>
<body class="bg-light">
  <!-- Hero Section -->
  <header class="bg-primary text-white text-center py-5">
    <a href="../index.php">
    <h1>System Zarządzania Biblioteką</h1>
    </a>
  </header>

  <!-- Lista książek -->
  <div class="container my-5">
    <h2 class="text-center mb-4">Dostępne Książki</h2>
    <table class="table table-striped table-books">
      <thead>
        <tr>
          <th>Tytuł książki</th>
          <th>Autor</th>
          <th>Gatunek</th>
          <th>Dostępne kopie</th>
          <th>Akcja</th>
        </tr>
      </thead>
      <tbody>
          <?php booksList();?>
      </tbody>
    </table>
  </div>

  <!-- Stopka -->
  <footer class="bg-light text-center py-3 mt-5">
    <p>&copy; 2025 System Zarządzania Biblioteką</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
