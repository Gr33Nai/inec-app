<?php
// inc/models/PollingUnit.php
class PollingUnit {
    private $conn;
    private $table_name = "polling_unit";

    public $uniqueid;
    public $polling_unit_id;
    public $ward_id;
    public $lga_id;
    public $uniquewardid;
    public $polling_unit_number;
    public $polling_unit_name;
    public $polling_unit_description;
    public $lat;
    public $long;
    public $entered_by_user;
    public $date_entered;
    public $user_ip_address;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY polling_unit_name";
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

    public function getByLga($lga_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE lga_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $lga_id);
        $stmt->execute();
        return $stmt;
    }
}
?>