<?php
session_start();
require_once '../config/database.php';
require_once '../models/Server.php';

$database = new Database();
$db = $database->getConnection();
$server = new Server($db);

if(!isset($_GET['id'])) {
    header("Location: /mcserverlist/");
    exit();
}

$server_data = $server->getServerById($_GET['id']);
if(!$server_data) {
    header("Location: /mcserverlist/");
    exit();
}

$recent_votes = $server->getRecentVotes($server_data['id']);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($server_data['name']); ?> - Minecraft Sunucu Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/mcserverlist/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/mcserverlist/">
                <i class="fas fa-cube me-2"></i>MCList
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/mcserverlist/add">
                                <i class="fas fa-plus-circle me-1"></i>Sunucu Ekle
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mcserverlist/logout">
                                <i class="fas fa-sign-out-alt me-1"></i>Çıkış Yap
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/mcserverlist/login">
                                <i class="fas fa-sign-in-alt me-1"></i>Giriş Yap
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mcserverlist/register">
                                <i class="fas fa-user-plus me-1"></i>Kayıt Ol
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if($server_data['banner_url']): ?>
            <div class="server-banner mb-4">
                <img src="<?php echo htmlspecialchars($server_data['banner_url']); ?>" alt="<?php echo htmlspecialchars($server_data['name']); ?> Banner" class="w-100">
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <?php if($server_data['logo_url']): ?>
                                <img src="<?php echo htmlspecialchars($server_data['logo_url']); ?>" alt="Logo" class="server-logo me-3">
                            <?php endif; ?>
                            <div>
                                <h1 class="minecraft-font mb-2"><?php echo htmlspecialchars($server_data['name']); ?></h1>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-user me-2"></i>Sunucu Sahibi: <?php echo htmlspecialchars($server_data['owner_name']); ?>
                                </p>
                            </div>
                        </div>

                        <div class="server-ip-box mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="server-ip-text">
                                    <?php echo htmlspecialchars($server_data['ip']); ?>:<?php echo htmlspecialchars($server_data['port']); ?>
                                </span>
                                <button class="copy-btn" onclick="copyIP('<?php echo htmlspecialchars($server_data['ip']); ?>:<?php echo htmlspecialchars($server_data['port']); ?>')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="server-description mb-4">
                            <?php echo nl2br(htmlspecialchars($server_data['description'])); ?>
                        </div>

                        <?php if(isset($_SESSION['user_id'])): ?>
                            <form id="voteForm" class="vote-form">
                                <input type="hidden" name="server_id" value="<?php echo $server_data['id']; ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-vote-yea"></i>Oy Ver
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="/mcserverlist/login" class="btn btn-secondary">
                                <i class="fas fa-sign-in-alt"></i>Oy vermek için giriş yap
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title minecraft-font mb-3">
                            <i class="fas fa-chart-bar me-2"></i>İstatistikler
                        </h5>
                        <div class="server-stats">
                            <div class="server-stat-item">
                                <div class="server-stat-value">
                                    <?php echo number_format($server_data['votes']); ?>
                                </div>
                                <div class="server-stat-label">Toplam Oy</div>
                            </div>
                            <div class="server-stat-item">
                                <div class="server-stat-value">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="server-stat-label">Online</div>
                            </div>
                            <div class="server-stat-item">
                                <div class="server-stat-value">
                                    <i class="fas fa-signal"></i>
                                </div>
                                <div class="server-stat-label">Ping</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title minecraft-font mb-3">
                            <i class="fas fa-history me-2"></i>Son Oylar
                        </h5>
                        <?php if($recent_votes): ?>
                            <div class="recent-votes">
                                <?php foreach($recent_votes as $vote): ?>
                                    <div class="recent-vote-item">
                                        <i class="fas fa-user-circle me-2"></i>
                                        <span class="vote-username"><?php echo htmlspecialchars($vote['username']); ?></span>
                                        <span class="vote-date ms-auto">
                                            <?php echo date('d.m.Y H:i', strtotime($vote['vote_time'])); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">Henüz oy verilmemiş.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="notification" class="notification">
        <i class="notification-icon"></i>
        <span class="notification-message"></span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function copyIP(ip) {
        navigator.clipboard.writeText(ip).then(() => {
            showNotification('success', 'IP adresi kopyalandı: ' + ip);
        });
    }

    document.getElementById('voteForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        fetch('/mcserverlist/servers/vote.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            showNotification(data.success ? 'success' : 'error', data.message);
            if (data.success) {
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        })
        .catch(error => {
            showNotification('error', 'Bir hata oluştu!');
        });
    });

    function showNotification(type, message) {
        const notification = document.getElementById('notification');
        const icon = notification.querySelector('.notification-icon');
        const messageEl = notification.querySelector('.notification-message');
        
        if (type === 'success') {
            icon.className = 'notification-icon fas fa-check-circle';
            notification.className = 'notification notification-success show';
        } else {
            icon.className = 'notification-icon fas fa-exclamation-circle';
            notification.className = 'notification notification-error show';
        }
        
        messageEl.textContent = message;
        
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }
    </script>
</body>
</html> 