<?php
// inc/controllers/PollingUnitController.php
class PollingUnitController {
    private $pollingUnitModel;
    private $announcedPuResultsModel;

    public function __construct($db) {
        $this->pollingUnitModel = new PollingUnit($db);
        $this->announcedPuResultsModel = new AnnouncedPuResults($db);
    }

    public function showResults($polling_unit_id) {
        // Get polling unit details
        $pollingUnit = $this->pollingUnitModel->readOne($polling_unit_id);
        
        if(!$pollingUnit) {
            return ['error' => 'Polling unit not found'];
        }

        // Get results for this polling unit
        $results = $this->announcedPuResultsModel->readByPollingUnit($polling_unit_id);
        
        return [
            'polling_unit' => $pollingUnit,
            'results' => $results
        ];
    }
}
?>