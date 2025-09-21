<?php
// inc/models/Lga.php
class Lga {
    private $conn;
    private $table_name = "lga";

    public $uniqueid;
    public $lga_id;
    public $lga_name;
    public $state_id;
    public $lga_description;
    public $entered_by_user;
    public $date_entered;
    public $user_ip_address;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY lga_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE uniqueid = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
