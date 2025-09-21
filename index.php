<?php
// index.php
session_start();

// Include configuration and models
include_once 'inc/config/database.php';
include_once 'inc/models/PollingUnit.php';
include_once 'inc/models/AnnouncedPuResults.php';
include_once 'inc/models/Lga.php';
include_once 'inc/models/Party.php';
include_once 'inc/models/State.php';
include_once 'inc/models/AnnouncedLgaResults.php';

// Include controllers
include_once 'inc/controllers/PollingUnitController.php';
include_once 'inc/controllers/LgaController.php';
include_once 'inc/controllers/ResultController.php';
include_once 'inc/controllers/StateController.php';

// Include views
include_once 'inc/views/polling_unit/results.php';
include_once 'inc/views/lga/results.php';
include_once 'inc/views/result/new.php';
include_once 'inc/views/state/results.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize controllers
$pollingUnitController = new PollingUnitController($db);
$lgaController = new LgaController($db);
$resultController = new ResultController($db);
$stateController = new StateController($db);

// Handle routing
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

// Display header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEC Polling Unit Results</title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a class="navbar-brand" href="index.php">INEC Results</a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?action=polling_unit_results&id=8">Sample Polling Unit</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?action=lga_results">LGA Results</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?action=state_results">State Results</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?action=new_result">New Result</a>
            </li>
        </ul>
    </nav>

    <div class="container">
        <?php
        switch ($action) {
            case 'polling_unit_results':
                $id = isset($_GET['id']) ? $_GET['id'] : 8;
                $data = $pollingUnitController->showResults($id);
                displayPollingUnitResults($data);
                break;
                
            case 'lga_results':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $lga_id = $_POST['lga_id'];
                    $data = $lgaController->getLgaResults($lga_id);
                    displayLgaResults($data);
                } else {
                    $lgas = $lgaController->getLgas();
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h2>Select LGA to View Results</h2>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="lga">Local Government Area:</label>
                                    <select class="form-control" id="lga" name="lga_id" required>
                                        <option value="">Select LGA</option>
                                        <?php while ($lga = $lgas->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo $lga['uniqueid']; ?>">
                                            <?php echo htmlspecialchars($lga['lga_name']); ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">View Results</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
                break;
                
            case 'state_results':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $state_id = $_POST['state_id'];
                    $data = $stateController->getStateResults($state_id);
                    displayStateResults($data);
                } else {
                    $states = $stateController->getStates();
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h2>Select State to View Results</h2>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="state">State:</label>
                                    <select class="form-control" id="state" name="state_id" required>
                                        <option value="">Select State</option>
                                        <?php while ($state = $states->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo $state['state_id']; ?>">
                                            <?php echo htmlspecialchars($state['state_name']); ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">View Results</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
                break;
                
            case 'new_result':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $message = $resultController->createResult($_POST);
                    displayResultMessage($message);
                }
                
                $pollingUnits = $resultController->getPollingUnits();
                $parties = $resultController->getParties();
                displayNewResultForm($pollingUnits, $parties);
                break;
                
            case 'create_result':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $message = $resultController->createResult($_POST);
                    displayResultMessage($message);
                    
                    // Show the form again
                    $pollingUnits = $resultController->getPollingUnits();
                    $parties = $resultController->getParties();
                    displayNewResultForm($pollingUnits, $parties);
                }
                break;
                
            default:
                ?>
                <div class="jumbotron">
                    <h1>INEC Polling Unit Results System</h1>
                    <p>This system allows you to view polling unit results, LGA summaries, state results comparison, and add new results.</p>
                    <p>Select an option from the navigation menu to get started.</p>
                    <a class="btn btn-primary" href="index.php?action=polling_unit_results&id=8" role="button">View Sample Polling Unit</a>
                    <a class="btn btn-primary" href="index.php?action=lga_results" role="button">View LGA Results</a>
                    <a class="btn btn-primary" href="index.php?action=state_results" role="button">Check State Results</a>
                    <a class="btn btn-primary" href="index.php?action=new_result" role="button">Add New Result</a>
                </div>
                <?php
                break;
        }
        ?>
    </div>

    <script src="static/js/script.js"></script>
</body>
</html>
<?php
// Close database connection if needed
$db = null;
?>