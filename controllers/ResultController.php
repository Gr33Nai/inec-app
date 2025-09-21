<?php
// inc/controllers/ResultController.php
class ResultController {
    private $announcedPuResultsModel;
    private $pollingUnitModel;
    private $partyModel;

    public function __construct($db) {
        $this->announcedPuResultsModel = new AnnouncedPuResults($db);
        $this->pollingUnitModel = new PollingUnit($db);
        $this->partyModel = new Party($db);
    }

    public function getPollingUnits() {
        return $this->pollingUnitModel->read();
    }

    public function getParties() {
        return $this->partyModel->read();
    }

    public function createResult($data) {
        $this->announcedPuResultsModel->polling_unit_uniqueid = $data['polling_unit_uniqueid'];
        $this->announcedPuResultsModel->party_abbreviation = $data['party_abbreviation'];
        $this->announcedPuResultsModel->party_score = $data['party_score'];
        $this->announcedPuResultsModel->entered_by_user = $data['entered_by_user'];
        $this->announcedPuResultsModel->user_ip_address = $_SERVER['REMOTE_ADDR'];
        
        if($this->announcedPuResultsModel->create()) {
            return ['success' => 'Result was created successfully.'];
        } else {
            return ['error' => 'Unable to create result.'];
        }
    }
}
?>
