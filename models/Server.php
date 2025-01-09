<?php
class Server {
    private $conn;
    private $table_name = "servers";

    public $id;
    public $name;
    public $ip;
    public $port;
    public $description;
    public $logo_url;
    public $banner_url;
    public $votes;
    public $user_id;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                (name, ip, port, description, logo_url, banner_url, user_id, votes, created_at) 
                VALUES (:name, :ip, :port, :description, :logo_url, :banner_url, :user_id, 0, NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":ip", $this->ip);
        $stmt->bindParam(":port", $this->port);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":logo_url", $this->logo_url);
        $stmt->bindParam(":banner_url", $this->banner_url);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }

    public function getTopServers() {
        $query = "SELECT s.*, u.username as owner_name FROM " . $this->table_name . " s 
                 LEFT JOIN users u ON s.user_id = u.id 
                 ORDER BY s.votes DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getServerById($id) {
        $query = "SELECT s.*, u.username as owner_name FROM " . $this->table_name . " s 
                 LEFT JOIN users u ON s.user_id = u.id 
                 WHERE s.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecentVotes($server_id, $limit = 5) {
        $query = "SELECT v.*, u.username FROM votes v 
                 LEFT JOIN users u ON v.user_id = u.id 
                 WHERE v.server_id = :server_id 
                 ORDER BY v.vote_time DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":server_id", $server_id);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function vote($server_id, $user_id) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO votes (server_id, user_id, vote_time) VALUES (?, ?, NOW())");
            $stmt->execute([$server_id, $user_id]);

            $stmt = $this->conn->prepare("UPDATE servers SET votes = votes + 1 WHERE id = ?");
            $stmt->execute([$server_id]);

            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
}
?> 