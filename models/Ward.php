<?php
// inc/models/Ward.php
class Ward {
    private $conn;
    private $table_name = "ward";

    public $uniqueid;
    public $ward_id;
    public $ward_name;
    public $lga_id;
    public $ward_description;
    public $entered_by_user;
    public $date_entered;
    public $user_ip_address;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all wards
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY ward_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read a single ward by ID
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE uniqueid = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get wards by LGA ID
    public function getByLga($lga_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE lga_id = ? ORDER BY ward_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $lga_id);
        $stmt->execute();
        return $stmt;
    }

    // Get ward by name
    public function getByName($ward_name) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ward_name = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $ward_name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new ward
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET ward_id=:ward_id, 
                      ward_name=:ward_name, 
                      lga_id=:lga_id, 
                      ward_description=:ward_description, 
                      entered_by_user=:entered_by_user, 
                      user_ip_address=:user_ip_address";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->ward_id = htmlspecialchars(strip_tags($this->ward_id));
        $this->ward_name = htmlspecialchars(strip_tags($this->ward_name));
        $this->lga_id = htmlspecialchars(strip_tags($this->lga_id));
        $this->ward_description = htmlspecialchars(strip_tags($this->ward_description));
        $this->entered_by_user = htmlspecialchars(strip_tags($this->entered_by_user));
        $this->user_ip_address = htmlspecialchars(strip_tags($this->user_ip_address));

        // Bind parameters
        $stmt->bindParam(":ward_id", $this->ward_id);
        $stmt->bindParam(":ward_name", $this->ward_name);
        $stmt->bindParam(":lga_id", $this->lga_id);
        $stmt->bindParam(":ward_description", $this->ward_description);
        $stmt->bindParam(":entered_by_user", $this->entered_by_user);
        $stmt->bindParam(":user_ip_address", $this->user_ip_address);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update a ward
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET ward_id=:ward_id, 
                      ward_name=:ward_name, 
                      lga_id=:lga_id, 
                      ward_description=:ward_description, 
                      entered_by_user=:entered_by_user, 
                      user_ip_address=:user_ip_address
                  WHERE uniqueid = :uniqueid";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->ward_id = htmlspecialchars(strip_tags($this->ward_id));
        $this->ward_name = htmlspecialchars(strip_tags($this->ward_name));
        $this->lga_id = htmlspecialchars(strip_tags($this->lga_id));
        $this->ward_description = htmlspecialchars(strip_tags($this->ward_description));
        $this->entered_by_user = htmlspecialchars(strip_tags($this->entered_by_user));
        $this->user_ip_address = htmlspecialchars(strip_tags($this->user_ip_address));
        $this->uniqueid = htmlspecialchars(strip_tags($this->uniqueid));

        // Bind parameters
        $stmt->bindParam(":ward_id", $this->ward_id);
        $stmt->bindParam(":ward_name", $this->ward_name);
        $stmt->bindParam(":lga_id", $this->lga_id);
        $stmt->bindParam(":ward_description", $this->ward_description);
        $stmt->bindParam(":entered_by_user", $this->entered_by_user);
        $stmt->bindParam(":user_ip_address", $this->user_ip_address);
        $stmt->bindParam(":uniqueid", $this->uniqueid);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete a ward
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE uniqueid = ?";
        $stmt = $this->conn->prepare($query);
        $this->uniqueid = htmlspecialchars(strip_tags($this->uniqueid));
        $stmt->bindParam(1, $this->uniqueid);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Search wards
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE ward_name LIKE ? OR ward_description LIKE ? 
                  ORDER BY ward_name";

        $stmt = $this->conn->prepare($query);
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->execute();

        return $stmt;
    }

    // Get wards with polling unit count
    public function readWithPollingUnitCount() {
        $query = "SELECT w.*, COUNT(pu.uniqueid) as polling_unit_count 
                  FROM " . $this->table_name . " w 
                  LEFT JOIN polling_unit pu ON w.uniqueid = pu.ward_id 
                  GROUP BY w.uniqueid 
                  ORDER BY w.ward_name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get wards by state ID (through LGA)
    public function getByState($state_id) {
        $query = "SELECT w.* FROM " . $this->table_name . " w 
                  INNER JOIN lga l ON w.lga_id = l.uniqueid 
                  WHERE l.state_id = ? 
                  ORDER BY w.ward_name";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $state_id);
        $stmt->execute();
        return $stmt;
    }

    // Get total number of wards
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get wards with pagination
    public function readPaged($from_record_num, $records_per_page) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  ORDER BY ward_name 
                  LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
?>