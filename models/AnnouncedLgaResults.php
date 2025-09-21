<?php
// inc/models/AnnouncedLgaResults.php
class AnnouncedLgaResults {
    private $conn;
    private $table_name = "announced_lga_results";

    public $result_id;
    public $lga_name;
    public $party_abbreviation;
    public $party_score;
    public $entered_by_user;
    public $date_entered;
    public $user_ip_address;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readByLga($lga_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE lga_name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $lga_id);
        $stmt->execute();
        return $stmt;
    }

    public function getStateResults($state_id) {
        // Get all LGAs in the state
        $query = "SELECT l.uniqueid, l.lga_name FROM lga l WHERE l.state_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $state_id);
        $stmt->execute();
        $lgas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stateResults = [];
        
        foreach ($lgas as $lga) {
            $lgaResults = $this->readByLga($lga['uniqueid']);
            
            while ($result = $lgaResults->fetch(PDO::FETCH_ASSOC)) {
                $party = $result['party_abbreviation'];
                if (!isset($stateResults[$party])) {
                    $stateResults[$party] = 0;
                }
                $stateResults[$party] += (int)$result['party_score'];
            }
        }
        
        return $stateResults;
    }
}
?>
