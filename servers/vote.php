<?php
session_start();
require_once '../config/database.php';
require_once '../models/Server.php';

date_default_timezone_set('Europe/Istanbul');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Oy vermek için giriş yapmalısınız!']);
    exit;
}

if (!isset($_POST['server_id'])) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz sunucu!']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

$db->exec("SET time_zone = '+03:00'");

$server = new Server($db);

$stmt = $db->prepare("SELECT UNIX_TIMESTAMP(vote_time) as vote_timestamp FROM votes WHERE user_id = ? AND server_id = ? ORDER BY vote_time DESC LIMIT 1");
$stmt->execute([$_SESSION['user_id'], $_POST['server_id']]);
$lastVote = $stmt->fetch(PDO::FETCH_ASSOC);

if ($lastVote) {
    $lastVoteTime = $lastVote['vote_timestamp'];
    $currentTime = time();
    $timeDiff = $currentTime - $lastVoteTime;
    $secondsInDay = 24 * 60 * 60; 
    
    if ($timeDiff < $secondsInDay) {
        $remainingSeconds = $secondsInDay - $timeDiff;
        $hours = floor($remainingSeconds / 3600);
        $minutes = floor(($remainingSeconds % 3600) / 60);
        
        echo json_encode([
            'success' => false, 
            'message' => "Bu sunucu için {$hours} saat {$minutes} dakika sonra tekrar oy verebilirsiniz!"
        ]);
        exit;
    }
}

if ($server->vote($_POST['server_id'], $_SESSION['user_id'])) {
    echo json_encode(['success' => true, 'message' => 'Oyunuz başarıyla kaydedildi!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Oy verme işlemi başarısız oldu!']);
}
?> 