<?php
// inc/controllers/LgaController.php
class LgaController {
    private $lgaModel;
    private $pollingUnitModel;
    private $announcedPuResultsModel;

    public function __construct($db) {
        $this->lgaModel = new Lga($db);
        $this->pollingUnitModel = new PollingUnit($db);
        $this->announcedPuResultsModel = new AnnouncedPuResults($db);
    }

    public function getLgas() {
        return $this->lgaModel->read();
    }

    public function getLgaResults($lga_id) {
        // Get LGA details
        $lga = $this->lgaModel->readOne($lga_id);
        
        if(!$lga) {
            return ['error' => 'LGA not found'];
        }
        
        // Get all polling units in this LGA
        $pollingUnits = $this->pollingUnitModel->getByLga($lga_id);
        
        $totalResults = [];
        
        // For each polling unit, get results and sum them
        while ($pollingUnit = $pollingUnits->fetch(PDO::FETCH_ASSOC)) {
            $puResults = $this->announcedPuResultsModel->readByPollingUnit($pollingUnit['uniqueid']);
            
            while ($result = $puResults->fetch(PDO::FETCH_ASSOC)) {
                $party = $result['party_abbreviation'];
                if (!isset($totalResults[$party])) {
                    $totalResults[$party] = 0;
                }
                $totalResults[$party] += (int)$result['party_score'];
            }
        }
        
        return [
            'lga' => $lga,
            'results' => $totalResults
        ];
    }
}
?>