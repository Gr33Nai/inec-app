<?php
// inc/views/result/new.php
function displayNewResultForm($pollingUnits, $parties) {
    ?>
    <div class="card">
        <div class="card-header">
            <h2>Add New Polling Unit Result</h2>
        </div>
        <div class="card-body">
            <form id="resultForm" action="index.php?action=create_result" method="POST">
                <div class="form-group">
                    <label for="polling_unit">Polling Unit:</label>
                    <select class="form-control" id="polling_unit" name="polling_unit_uniqueid" required>
                        <option value="">Select Polling Unit</option>
                        <?php while ($pu = $pollingUnits->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $pu['uniqueid']; ?>">
                            <?php echo htmlspecialchars($pu['polling_unit_name'] . ' (' . $pu['polling_unit_number'] . ')'); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="party">Party:</label>
                    <select class="form-control" id="party" name="party_abbreviation" required>
                        <option value="">Select Party</option>
                        <?php while ($party = $parties->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $party['partyid']; ?>">
                            <?php echo htmlspecialchars($party['partyname']); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="score">Party Score:</label>
                    <input type="number" class="form-control" id="score" name="party_score" required min="0">
                </div>
                
                <div class="form-group">
                    <label for="entered_by">Entered By:</label>
                    <input type="text" class="form-control" id="entered_by" name="entered_by_user" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit Result</button>
            </form>
        </div>
    </div>
    <?php
}

function displayResultMessage($message) {
    if (isset($message['success'])) {
        echo "<div class='alert alert-success'>{$message['success']}</div>";
    } elseif (isset($message['error'])) {
        echo "<div class='alert alert-danger'>{$message['error']}</div>";
    }
}
?>