<?php

// Include PhpSpreadsheet library
require_once 'vendor/autoload.php';

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$db_name = 'db';
$username = 'root';
$password = '';

// try {
//   // PDO connection
//   $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
//   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//   die("Error connecting to database: " . $e->getMessage());
// }

$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
  mkdir($upload_dir, 0775, true); // Create directory with permissions
}
$refFilePath = $upload_dir . 'Fichier de correspondance de noms.xlsx'; // Reference file path

$response = ['success' => false, 'message' => ''];

if (!file_exists($refFilePath)) {
    $response['message'] = 'Reference file does not exist. Please upload it first.';
    echo json_encode($response);
    exit();
}

if (isset($_FILES['collaborators'], $_FILES['production'], $_FILES['charges'])) {
    $collaboratorsPath = $upload_dir . $_FILES['collaborators']['name'];
    $productionPath = $upload_dir . $_FILES['production']['name'];
    $chargesPath = $upload_dir . $_FILES['charges']['name'];

    if (move_uploaded_file($_FILES['collaborators']['tmp_name'], $collaboratorsPath) &&
        move_uploaded_file($_FILES['production']['tmp_name'], $productionPath) &&
        move_uploaded_file($_FILES['charges']['tmp_name'], $chargesPath)) {
        // Call Python script to verify data
        $command = escapeshellcmd("python verify_data.py $collaboratorsPath $productionPath $chargesPath $refFilePath");
        $output = shell_exec($command);
        $result = json_decode($output, true);

        if ($result && $result['success']) {
            $response = ['success' => true, 'message' => 'Files processed successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Error processing files: ' . $result['message']];
        }
    } else {
        $response['message'] = 'Failed to upload files.';
    }
} else {
    $response['message'] = 'All files must be uploaded.';
}

echo json_encode($response);
exit();


function processExcelFile($filepath, $conn) {
  // Create a new PhpSpreadsheet object
  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
  $spreadsheet = $reader->load($filepath);

  // Get the active sheet
  $sheet = $spreadsheet->getActiveSheet();

  // Iterate through rows (skip header row)
  $data = $sheet->toArray(null, true, true, true); // Include header row

  // Assuming first row contains column names (adjust as needed)
  $column_names = array_slice($data[1], 1); // Extract column names (skip column A)

  for ($row = 2; $row <= count($data); $row++) {
    $extracted_data = array_slice($data[$row], 1); // Extract data from row (skip column A)

    // Prepare the INSERT query (replace with your table name)
    $sql = "INSERT INTO your_table (" . implode(',', $column_names) . ") VALUES (:col1, :col2, ...)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters dynamically based on the number of columns
    $params = [];
    foreach ($column_names as $i => $col_name) {
      $params[":col" . ($i + 1)] = $extracted_data[$i]; // Bind data with named placeholders
    }

    // Execute the INSERT query
    try {
      $stmt->execute($params);
      echo "Record from '" . pathinfo($filepath)['basename'] . "' inserted successfully.<br>";
    } catch (PDOException $e) {
      echo "Error inserting record: " . $e->getMessage() . "<br>";
    }
  }
}

