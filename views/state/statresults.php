<?php
// inc/views/state/results.php
function displayStateResults($data) {
    if (isset($data['error'])) {
        echo "<div class='alert alert-danger'>{$data['error']}</div>";
        return;
    }
    
    $state = $data['state'];
    $announcedResults = $data['announced_results'];
    $calculatedResults = $data['calculated_results'];
    ?>
    <div class="card">
        <div class="card-header">
            <h2>State Results - <?php echo htmlspecialchars($state['state_name']); ?></h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h3>Announced Results (from LGA totals)</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Party</th>
                                <th>Total Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($announcedResults as $party => $score): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($party); ?></td>
                                <td><?php echo number_format($score); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <h3>Calculated Results (from Polling Units)</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Party</th>
                                <th>Total Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($calculatedResults as $party => $score): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($party); ?></td>
                                <td><?php echo number_format($score); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <h3>Comparison</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Party</th>
                                <th>Announced Results</th>
                                <th>Calculated Results</th>
                                <th>Difference</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $allParties = array_unique(array_merge(array_keys($announcedResults), array_keys($calculatedResults)));
                            sort($allParties);
                            
                            foreach ($allParties as $party): 
                                $announced = $announcedResults[$party] ?? 0;
                                $calculated = $calculatedResults[$party] ?? 0;
                                $difference = $announced - $calculated;
                                $status = $difference == 0 ? 'Match' : ($difference > 0 ? 'Overstated' : 'Understated');
                                $statusClass = $difference == 0 ? 'text-success' : ($difference > 0 ? 'text-warning' : 'text-danger');
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($party); ?></td>
                                <td><?php echo number_format($announced); ?></td>
                                <td><?php echo number_format($calculated); ?></td>
                                <td><?php echo number_format(abs($difference)); ?></td>
                                <td class="<?php echo $statusClass; ?>"><?php echo $status; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>