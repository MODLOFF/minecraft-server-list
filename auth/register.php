<?php
session_start();
require_once '../config/database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $user->username = $_POST['username'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];

    if(empty($user->username) || empty($user->email) || empty($user->password)) {
        $error = "Tüm alanları doldurunuz.";
    } elseif($user->usernameExists()) {
        $error = "Bu kullanıcı adı zaten kullanılıyor.";
    } elseif($user->emailExists()) {
        $error = "Bu email adresi zaten kullanılıyor.";
    } else {
        if($user->create()) {
            $success = "Kayıt başarılı! Şimdi giriş yapabilirsiniz.";
        } else {
            $error = "Kayıt sırasında bir hata oluştu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - MCList</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/mcserverlist/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-logo">
            <h1><i class="fas fa-cube"></i> MCList</h1>
            <p class="auth-subtitle">Minecraft sunucu listemize katılın</p>
        </div>

        <div class="auth-form">
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if(isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form action="/mcserverlist/auth/register.php" method="post">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user"></i>
                        Kullanıcı Adı
                    </label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i>
                        E-posta Adresi
                    </label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        Şifre
                    </label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        Şifre Tekrar
                    </label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i>
                    Kayıt Ol
                </button>
            </form>

            <div class="auth-separator">
                <span>veya</span>
            </div>

            <div class="auth-links">
                <p>Zaten hesabınız var mı? <a href="/mcserverlist/login">Giriş Yap</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 