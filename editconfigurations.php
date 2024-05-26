<?php
include 'layout/header.php';
include 'connection.php';


$tableName = $_GET['table'] ?? '';
$displayName =(urldecode($_GET['displayName'] ?? 'Unknown Table'));

if (!in_array($tableName, ['codesap', 'costdirect', 'tarifsjh', 'typecost'])) {
    die('Invalid table name.');
}

$successMessage = ''; // Variable to store success message

function getFirstColumnName($pdo, $tableName) {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `$tableName`");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['Field']; // 'Field' contains the column name
}

function fetchData($pdo, $tableName) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `$tableName`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

function deleteRow($pdo, $tableName, $uniqueValue) {
    try {
        $stmt = $pdo->prepare("DELETE FROM `$tableName` WHERE " . getFirstColumnName($pdo, $tableName) . " = ?");
        $stmt->execute([$uniqueValue]);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

function saveRow($pdo, $tableName, $uniqueValue, $data) {
    $firstColumnName = getFirstColumnName($pdo, $tableName);
    if ($uniqueValue) {
        // Update existing row
        $setPart = [];
        $params = [];
        foreach ($data as $key => $value) {
            if ($key != $firstColumnName) { // Ensure the unique column isn't mistakenly updated
                $setPart[] = "`$key` = ?";
                $params[] = $value;
            }
        }
        $params[] = $uniqueValue;
        $stmt = $pdo->prepare("UPDATE `$tableName` SET " . implode(', ', $setPart) . " WHERE $firstColumnName = ?");
        $stmt->execute($params);
    } else {
        // Insert new row
        $resultMessage = insertRow($pdo, $tableName, $data);
        if (strpos($resultMessage, 'Database error') !== false) {
            die($resultMessage);
        }
        $successMessage = $resultMessage;
    }
}

function getTableColumns($pdo, $tableName) {
    try {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `$tableName`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

function getInputType($sqlType) {
    $type = strtolower($sqlType);
    if (strpos($type, 'int') !== false || strpos($type, 'decimal') !== false || strpos($type, 'float') !== false || strpos($type, 'double') !== false) {
        return 'number';
    } elseif (strpos($type, 'date') !== false || strpos($type, 'time') !== false) {
        return 'date';
    } elseif (strpos($type, 'blob') !== false || strpos($type, 'binary') !== false) {
        return 'file';
    } else {
        return 'text';
    }
}

function insertRow($pdo, $tableName, $data, $excludeKeys = []) {
    // Filter out keys that should be excluded from the insert statement
    $filteredData = array_filter($data, function($key) use ($excludeKeys) {
        return !in_array($key, $excludeKeys);
    }, ARRAY_FILTER_USE_KEY);

    $keys = array_keys($filteredData);
    $placeholders = array_fill(0, count($filteredData), '?');

    $sql = "INSERT INTO `$tableName` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $placeholders) . ")";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute(array_values($filteredData));
        return "Row inserted successfully.";
    } catch (PDOException $e) {
        return "Database error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $uniqueValue = $_POST[getFirstColumnName($pdo, $tableName)]; // Get unique identifier from POST data
    $data = $_POST;
    unset($data['update']); // Exclude the update button name from data array
    unset($data['delete']); // Exclude the delete button name from data array
    unset($data['add']);    // Exclude the add button name from data array

    if (isset($_POST['delete'])) {
        deleteRow($pdo, $tableName, $uniqueValue);
        $successMessage = 'Row deleted successfully.';
    } elseif (isset($_POST['update'])) {
        saveRow($pdo, $tableName, $uniqueValue, $data);
        $successMessage = 'Row updated successfully.';
    } elseif (isset($_POST['add'])) {
        
        $successMessage = insertRow($pdo, $tableName, $data);
    }
}

$data = fetchData($pdo, $tableName);
$columns = getTableColumns($pdo,$tableName);


?>

<main id="main-container">
    <?php 
    if ($successMessage): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="row">
            <div class="col-sm-10">
                <h2>Manage Data for Table: <?= $displayName ?></h2>
            </div>
            <div class="col-sm-1">
                    <!-- Button to trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRowModal">Add Row</button>
            </div>
        </div>
    </div>

    <div class='table-responsive'>
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <?php foreach ($data[0] ?? [] as $header => $value): ?>
                        <th><?= htmlspecialchars($header) ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <form method="POST" action="">
                            <?php foreach ($row as $key => $cell): ?>
                                <td><input type="<?= getInputType($column['Type']) ?>" name="<?= $key ?>" value="<?= htmlspecialchars($cell) ?>"></td>
                            <?php endforeach; ?>
                            <td>
                                <button type="submit" name="update">Update</button>
                                <button type="submit" name="delete" onclick="return confirm('Are you sure?')">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-labelledby="addRowModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRowModalLabel">Add New Row</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <?php foreach ($columns as $column): ?>
                            <div class="form-group">
                                <label for="<?= htmlspecialchars($column['Field']) ?>"><?= htmlspecialchars($column['Field']) ?>:</label>
                                <input type="<?= getInputType($column['Type']) ?>" class="form-control" id="<?= htmlspecialchars($column['Field']) ?>" name="<?= htmlspecialchars($column['Field']) ?>" placeholder="<?= htmlspecialchars($column['Type']) ?>">
                            </div>
                        <?php endforeach; ?>
                        <button type="submit" name="add" class="btn btn-primary">Add Row</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and its dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</main>

<?php include 'layout/footer.php'; ?>

