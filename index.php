<?php
  include 'connect/connect_db.php';
  include 'connect/session.php';
  include 'modules/index_modules.php';
  
  if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true){
    header('Location: pages/login.php');
    exit();
  }
  $role = $_SESSION['role'];

?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Zarządzania Biblioteką</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <link rel="stylesheet" href="styles/index_styles.css">
</head>
<body class="bg-light">
      <header class="bg-primary text-white text-center py-5">
    <h1>System Zarządzania Biblioteką</h1>
  </header>

  <?php if ($role === '1'): ?>
    <div class="container my-5">
    <a href="pages/logout.php">wyloguj się</a>
  </div>
  <div class="container my-5">
    <div class="row profile-section">
        <div class="col-md-4 mb-4">
            <div class="card profile-card">
                <div class="profile-header">Witaj, <?php echo displayName(); ?></div>
                <p>Twój kod: <?php echo displayCid();?></p>
            </div>
        </div>

        <!-- Prawa sekcja - Kafelki -->
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="pages/admin/manageUser.php">
                            <h5 class="card-title">Zarządzanie użytkownikami</h5>
                            <p class="card-text"><input type="text" name="cid" placeholder="Wpisz CID: #XXXXXX"></p>
                            <input type="submit"  class="btn btn-primary"value="Szukaj!">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Dodaj wypożyczenie</h5>
                            <p class="card-text">Dodaj nowe wypożyczenie</p>
                            <a href="pages/admin/addBorrowings.php" class="btn btn-primary">Przejdź</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Zarządzaj książkami</h5>
                            <p class="card-text">Dodaj/edytuj/usuń książkę.</p>
                            <a href="pages/admin/addBooks.php" class="btn btn-primary">Przejdź</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Statystyki</h5>
                            <p class="card-text">Ilość użytkowników: <?php countUser();?></p>
                            <p class="card-text">Ilość książek: <?php countBooks();?></p>
                            <p class="card-text">Ilość wypożyczeń: <?php countBorrowings();?></p>
                            <p class="card-text">Ilość recenzji: <?php countReviews();?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>

<?php endif;?>
<?php if ($role === '2'):?>


  <div class="container my-5">
    <a href="pages/logout.php">wyloguj się</a>
  </div>

  
  <div class="container my-5">
    <div class="row profile-section">
      
        <div class="col-md-4 mb-4">
            <div class="card profile-card">
                <div class="profile-header">Witaj, <?php echo displayName(); ?></div>
                <p>Twój kod: <?php echo displayCid();?></p>
                <h5>Aktualnie wynajęte książki:</h5>
                <table class="table table-sm table-striped rentals-table">
                    <thead>
                        <tr>
                            <th>Tytuł książki</th>
                            <th>Data wypożyczenia</th>
                            <th>Data zwrotu</th>
                            <th>Opłaty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo displayList(); ?>
                    </tbody>
                </table>
            </div>
        </div>

      
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Lista dostępnych książek</h5>
                            <p class="card-text">Przejdź do listy dostępnych książek.</p>
                            <a href="pages/booksList.php" class="btn btn-primary">Przejdź</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Historia wypożyczeń</h5>
                            <p class="card-text">Zobacz swoją historię wypożyczeń.</p>
                            <a href="pages/borrowings.php" class="btn btn-primary">Przejdź</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Recenzje i oceny</h5>
                            <p class="card-text">Oceń książki, które przeczytałeś.</p>
                            <a href="pages/opinion.php" class="btn btn-primary">Przejdź</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <?php expiredReservation();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
<?php
    showNotification();
?>

<?php endif;?>

  <footer class="bg-light text-center py-3 mt-5">
    <p>&copy; 2025 System Zarządzania Biblioteką</p>
  </footer> -

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" defer></script>
  <script defer>
    $(document).ready(function() {
    $('#accept-notifications').click(function() {
        $.ajax({
            url: '/SystemZarządzaniaBiblioteką/modules/index_modules.php',
            type: 'POST',
            data: { action: 'update_newsletter_agreement' },
            success: function(response) {
                console.log(response); // Logowanie odpowiedzi
                if (response === 'success') {
                    $('.notification-bar').fadeOut(); // Ukryj pasek
                } else {
                    alert('Wystąpił problem podczas zapisywania zgody.');
                }
            },
            error: function() {
                alert('Wystąpił błąd podczas komunikacji z serwerem.');
            }
        });
    });
});
$(document).ready(function() {
    $('#btn-decline-reservation').click(function(e) {
        e.preventDefault(); 

        var bookId = $(this).data('book-id'); 

        console.log('Anulowanie rezerwacji dla książki o ID: ' + bookId); 

        $.ajax({
            url: '/SystemZarządzaniaBiblioteką/modules/index_modules.php',
            type: 'POST',
            data: {
                action: 'delete_reservation',
                book_id: bookId 
            },
            success: function(response) {
                console.log('Odpowiedź z serwera: ' + response);
                if (response === 'success') {
                    location.reload(); 
                } else {
                    alert('Wystąpił błąd podczas anulowania rezerwacji.');
                }
            },
            error: function() {
                alert('Wystąpił problem z połączeniem.');
            }
        });
    });
});






  </script>
</body>
</html>
