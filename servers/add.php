<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: /mcserverlist/login");
    exit();
}

require_once '../config/database.php';
require_once '../models/Server.php';

$database = new Database();
$db = $database->getConnection();
$server = new Server($db);

$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $server->name = $_POST['name'];
    $server->ip = $_POST['ip'];
    $server->port = $_POST['port'];
    $server->description = $_POST['description'];
    $server->logo_url = $_POST['logo_url'];
    $server->banner_url = $_POST['banner_url'];
    $server->user_id = $_SESSION['user_id'];

    if(empty($server->name) || empty($server->ip)) {
        $error = "Sunucu adı ve IP adresi zorunludur.";
    } else {
        if($server->create()) {
            $success = "Sunucu başarıyla eklendi!";
        } else {
            $error = "Sunucu eklenirken bir hata oluştu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunucu Ekle - MCList</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/mcserverlist/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-4">
                    <a href="/mcserverlist/" class="navbar-brand mb-4 d-inline-block">
                        <i class="fas fa-cube me-2"></i>MCList
                    </a>
                    <h2 class="minecraft-font mb-3">Yeni Sunucu Ekle</h2>
                    <p class="text-muted">Minecraft sunucunuzu listeye ekleyin ve oyuncuları bekleyin</p>
                </div>

            <div class="add-server-form">
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>

                <form action="/mcserverlist/servers/add.php" method="post" enctype="multipart/form-data">
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Temel Bilgiler</h3>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-server"></i>
                                Sunucu Adı
                            </label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-network-wired"></i>
                                Sunucu IP Adresi
                            </label>
                            <input type="text" name="ip" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i>
                                Sunucu Açıklaması
                            </label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-image"></i> Görsel İçerikler</h3>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-image"></i>
                                Logo URL
                            </label>
                            <input type="url" name="logo_url" class="form-control" placeholder="https://example.com/logo.png">
                            <small class="form-text">Sunucunuzun logo görseli için bir URL girin (64x64 önerilir)</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-panorama"></i>
                                Banner URL
                            </label>
                            <input type="url" name="banner_url" class="form-control" placeholder="https://example.com/banner.png">
                            <small class="form-text">Sunucunuzun banner görseli için bir URL girin (468x60 önerilir)</small>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-cog"></i> Sunucu Detayları</h3>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-gamepad"></i>
                                Sunucu Versiyonu
                            </label>
                            <input type="text" name="version" class="form-control" placeholder="1.20.1" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-globe"></i>
                                Website URL
                            </label>
                            <input type="url" name="website" class="form-control" placeholder="https://example.com">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fab fa-discord"></i>
                                Discord URL
                            </label>
                            <input type="url" name="discord" class="form-control" placeholder="https://discord.gg/...">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i>
                        Sunucu Ekle
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 