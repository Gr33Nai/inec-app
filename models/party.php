<?php
// inc/models/Party.php
class Party {
    private $conn;
    private $table_name = "party";

    public $id;
    public $partyid;
    public $partyname;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY partyname";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
