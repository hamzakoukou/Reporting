<?php
include 'layout/header.php';
include 'connection.php';// Assuming this file contains your database connection and functions

$tableName = $_GET['table'] ?? 'default_table_name'; // Get the table name from the URL parameter

// Fetch table data
function fetchTableData($pdo, $tableName) {
    $stmt = $pdo->prepare("SELECT * FROM `$tableName`");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$tableData = fetchTableData($pdo, $tableName);

$tableDisplayNames = [
    'codesap' => 'Codes SAP',
    'costdirect' => 'Codes Costs Directs',
    'tarifsjh' => 'Tarifs JH',
    'typecost' => 'Types de Costs'
];

function getDisplayName($tableName, $tableDisplayNames) {
    return $tableDisplayNames[$tableName] ?? $tableName; // Return the table name itself if not found in the dictionary
}
?>

<h1>Edit <?= getDisplayName($tableName, $tableDisplayNames) ?></h1>

<form method="POST" action="saveChanges.php">
    <table class="table">
        <thead>
            <tr>
                <?php foreach (array_keys($tableData[0]) as $columnName): ?>
                    <th><?= $columnName ?></th>
                <?php endforeach; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tableData as $row): ?>
                <tr>
                    <?php foreach ($row as $columnName => $value): ?>
                        <td>
                            <input type="text" name="<?= $columnName ?>[]" value="<?= htmlspecialchars($value) ?>">
                        </td>
                    <?php endforeach; ?>
                    <td>
                        <button type="submit" name="update" value="<?= isset($row['id']) ? $row['id'] : '' ?>">Update</button>
                        <button type="submit" name="delete" value="<?= isset($row['id']) ? $row['id'] : '' ?>">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>

<?php include 'layout/footer.php'; ?>

