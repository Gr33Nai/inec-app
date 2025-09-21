<?php
// inc/controllers/StateController.php
class StateController {
    private $stateModel;
    private $lgaModel;
    private $announcedLgaResultsModel;
    private $announcedPuResultsModel;

    public function __construct($db) {
        $this->stateModel = new State($db);
        $this->lgaModel = new Lga($db);
        $this->announcedLgaResultsModel = new AnnouncedLgaResults($db);
        $this->announcedPuResultsModel = new AnnouncedPuResults($db);
    }

    public function getStates() {
        return $this->stateModel->read();
    }

    public function getStateResults($state_id) {
        // Get state details
        $state = $this->stateModel->readOne($state_id);
        
        if(!$state) {
            return ['error' => 'State not found'];
        }
        
        // Get announced LGA results for the state
        $announcedResults = $this->announcedLgaResultsModel->getStateResults($state_id);
        
        // Calculate results from polling units
        $calculatedResults = $this->calculateStateResults($state_id);
        
        return [
            'state' => $state,
            'announced_results' => $announcedResults,
            'calculated_results' => $calculatedResults
        ];
    }
    
    private function calculateStateResults($state_id) {
        // Get all LGAs in the state
        $query = "SELECT uniqueid FROM lga WHERE state_id = ?";
        $stmt = $this->lgaModel->conn->prepare($query);
        $stmt->bindParam(1, $state_id);
        $stmt->execute();
        $lgas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stateResults = [];
        
        foreach ($lgas as $lga) {
            // Get all polling units in this LGA
            $pollingUnits = $this->lgaModel->getByLga($lga['uniqueid']);
            
            // For each polling unit, get results and sum them
            while ($pollingUnit = $pollingUnits->fetch(PDO::FETCH_ASSOC)) {
                $puResults = $this->announcedPuResultsModel->readByPollingUnit($pollingUnit['uniqueid']);
                
                while ($result = $puResults->fetch(PDO::FETCH_ASSOC)) {
                    $party = $result['party_abbreviation'];
                    if (!isset($stateResults[$party])) {
                        $stateResults[$party] = 0;
                    }
                    $stateResults[$party] += (int)$result['party_score'];
                }
            }
        }
        
        return $stateResults;
    }
}
?>
