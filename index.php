<?php
session_start();
require_once 'config/database.php';
require_once 'models/Server.php';

$database = new Database();
$db = $database->getConnection();

$server = new Server($db);
$servers = $server->getTopServers()->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minecraft Sunucu Listesi</title>
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

    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1 class="display-4 minecraft-font mb-4">
                <i class="fas fa-crown text-warning me-2"></i>
                Top Minecraft Sunucuları
            </h1>
            <p class="lead text-muted">En iyi Minecraft sunucularını keşfedin ve oylamaya katılın!</p>
        </div>
        
        <div class="servers-list">
            <?php foreach($servers as $index => $server): ?>
                <div class="server-item">
                    <div class="server-rank">#<?php echo $index + 1; ?></div>
                    
                    <?php if($server['logo_url']): ?>
                        <img src="<?php echo htmlspecialchars($server['logo_url']); ?>" alt="Logo" class="server-logo">
                    <?php endif; ?>
                    
                    <div class="server-content">
                        <div class="server-info">
                            <h3 class="server-name">
                                <a href="/mcserverlist/server/<?php echo $server['id']; ?>">
                                    <?php echo htmlspecialchars($server['name']); ?>
                                </a>
                            </h3>
                        </div>

                        <div class="server-stats">
                            <div class="server-stat">
                                <i class="fas fa-users"></i>
                                <span>Online</span>
                            </div>
                            <div class="server-stat">
                                <i class="fas fa-signal"></i>
                                <span>Ping</span>
                            </div>
                            <div class="server-stat">
                                <i class="fas fa-star"></i>
                                <span><?php echo number_format($server['votes']); ?> Oy</span>
                            </div>
                        </div>

                        <div class="server-actions">
                            <div class="server-ip">
                                <span class="server-ip-text">
                                    <?php echo htmlspecialchars($server['ip']); ?>:<?php echo htmlspecialchars($server['port']); ?>
                                </span>
                                <button class="copy-btn" onclick="copyIP('<?php echo htmlspecialchars($server['ip']); ?>:<?php echo htmlspecialchars($server['port']); ?>')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="copy-notification" class="copy-notification">
        <i class="fas fa-check-circle"></i>
        <span class="notification-text"></span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function copyIP(ip) {
        navigator.clipboard.writeText(ip).then(() => {
            const notification = document.getElementById('copy-notification');
            const notificationText = notification.querySelector('.notification-text');
            notificationText.textContent = ip + ' kopyalandı';
            
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 2000);
        });
    }
    </script>
</body>
</html> 