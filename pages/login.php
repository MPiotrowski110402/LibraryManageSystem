<?php
include '../connect/connect_db.php';
include '../connect/session.php';

?>



<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie i Rejestracja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/login_style.css">
</head>
<body>
    <div class="container-fluid d-flex vh-100">
        <!-- Logowanie -->
        <div class="login-section w-50 d-flex flex-column align-items-center justify-content-center p-5">
            <h2>Zaloguj się</h2>
            <form method="POST" action="../modules/login_modules.php">
                <div class="mb-3">
                    <label for="emailLogin" class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" id="emailLogin" required>
                </div>
                <div class="mb-3">
                    <label for="passwordLogin" class="form-label">Hasło</label>
                    <input type="password" name="password" class="form-control" id="passwordLogin" required>
                </div>
                <input type="submit" name="login_btn" class="btn btn-primary w-100" value="Zaloguj">
            </form>
        </div>
        
        <!-- Rejestracja -->
        <div class="register-section w-50 d-flex flex-column align-items-center justify-content-center p-5">
            <h2>Zarejestruj się</h2>
            <form method="POST" action="../modules/login_modules.php">
                <div class="mb-3">
                    <label for="firstName" class="form-label">Imię</label>
                    <input type="text" name="firstName" class="form-control" id="firstName" required>
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Nazwisko</label>
                    <input type="text" name="lastName" class="form-control" id="lastName" required>
                </div>
                <div class="mb-3">
                    <label for="emailRegister" class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" id="emailRegister" required>
                </div>
                <div class="mb-3">
                    <label for="passwordRegister" class="form-label">Hasło</label>
                    <input type="password" name="password" class="form-control" id="passwordRegister" required>
                </div>
                <div class="mb-3">
                    <label for="passwordRegister" class="form-label">Powtórz Hasło</label>
                    <input type="password" name="passwordRepeat" class="form-control" id="passwordRegister" required>
                </div>
                <div class="mb-3">
                    <label for="birthDate" class="form-label">Data urodzenia</label>
                    <input type="date" name="date" class="form-control" id="birthDate" required>
                </div>
                <input type="submit" name="registered_btn" class="btn btn-success w-100" value="Zarejestruj">
            </form>
        </div>
    </div>
</body>
</html>