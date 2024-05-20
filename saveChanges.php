<?php
include 'connection.php';
include 'configurations.php';

function modifyTableData($pdo, $tableName, $operation, $data, $condition = '') {
    if ($operation === 'update') {
        $setParts = [];
        foreach ($data as $column => $value) {
            $setParts[] = "`$column` = " . $pdo->quote($value);
        }
        $sql = "UPDATE `$tableName` SET " . implode(', ', $setParts);
        if ($condition) {
            $sql .= " WHERE $condition";
        }
    } elseif ($operation === 'insert') {
        $columns = implode('`, `', array_keys($data));
        $values = implode(', ', array_map([$pdo, 'quote'], array_values($data)));
        $sql = "INSERT INTO `$tableName` (`$columns`) VALUES ($values)";
    } elseif ($operation === 'delete') {
        $sql = "DELETE FROM `$tableName`";
        if ($condition) {
            $sql .= " WHERE $condition";
        }
    } else {
        throw new Exception("Unsupported operation");
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return ($operation === 'select') ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->rowCount();
}

if (isset($_POST['update'])) {
    $id = $_POST['update'];
    $data = array_combine(array_keys($_POST), array_map(function($item) { return $item[0]; }, $_POST));
    unset($data['update'], $data['delete']); // Remove control fields

    modifyTableData($pdo, $_GET['table'], 'update', $data, "id = $id");
}

if (isset($_POST['delete'])) {
    $id = $_POST['delete'];
    modifyTableData($pdo, $_GET['table'], 'delete', [], "id = $id");
}

header("Location:configurations.php"); // Redirect back 
exit;

