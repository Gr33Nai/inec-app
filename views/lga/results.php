<?php
// inc/views/lga/results.php
function displayLgaResults($data) {
    if (isset($data['error'])) {
        echo "<div class='alert alert-danger'>{$data['error']}</div>";
        return;
    }
    
    $lga = $data['lga'];
    $results = $data['results'];
    ?>
    <div class="card">
        <div class="card-header">
            <h2>LGA Results Summary</h2>
        </div>
        <div class="card-body">
            <h3><?php echo htmlspecialchars($lga['lga_name']); ?></h3>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($lga['lga_description']); ?></p>
            
            <h4>Total Results from All Polling Units:</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Party</th>
                        <th>Total Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $party => $score): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($party); ?></td>
                        <td><?php echo htmlspecialchars($score); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>
