<?php
// inc/models/AnnouncedPuResults.php
class AnnouncedPuResults {
    private $conn;
    private $table_name = "announced_pu_results";

    public $result_id;
    public $polling_unit_uniqueid;
    public $party_abbreviation;
    public $party_score;
    public $entered_by_user;
    public $date_entered;
    public $user_ip_address;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readByPollingUnit($polling_unit_uniqueid) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE polling_unit_uniqueid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $polling_unit_uniqueid);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET polling_unit_uniqueid=:polling_unit_uniqueid, 
                      party_abbreviation=:party_abbreviation, 
                      party_score=:party_score, 
                      entered_by_user=:entered_by_user, 
                      user_ip_address=:user_ip_address";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->polling_unit_uniqueid = htmlspecialchars(strip_tags($this->polling_unit_uniqueid));
        $this->party_abbreviation = htmlspecialchars(strip_tags($this->party_abbreviation));
        $this->party_score = htmlspecialchars(strip_tags($this->party_score));
        $this->entered_by_user = htmlspecialchars(strip_tags($this->entered_by_user));
        $this->user_ip_address = htmlspecialchars(strip_tags($this->user_ip_address));

        // Bind values
        $stmt->bindParam(":polling_unit_uniqueid", $this->polling_unit_uniqueid);
        $stmt->bindParam(":party_abbreviation", $this->party_abbreviation);
        $stmt->bindParam(":party_score", $this->party_score);
        $stmt->bindParam(":entered_by_user", $this->entered_by_user);
        $stmt->bindParam(":user_ip_address", $this->user_ip_address);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
