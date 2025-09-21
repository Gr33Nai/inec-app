<?php
// inc/views/polling_unit/results.php
function displayPollingUnitResults($data) {
    if (isset($data['error'])) {
        echo "<div class='alert alert-danger'>{$data['error']}</div>";
        return;
    }
    
    $pollingUnit = $data['polling_unit'];
    $results = $data['results'];
    ?>
    <div class="card">
        <div class="card-header">
            <h2>Polling Unit Results</h2>
        </div>
        <div class="card-body">
            <h3><?php echo htmlspecialchars($pollingUnit['polling_unit_name']); ?></h3>
            <p><strong>Number:</strong> <?php echo htmlspecialchars($pollingUnit['polling_unit_number']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($pollingUnit['polling_unit_description']); ?></p>
            
            <h4>Results:</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Party</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $results->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['party_abbreviation']); ?></td>
                        <td><?php echo htmlspecialchars($row['party_score']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>