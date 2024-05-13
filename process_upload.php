
<?php

// Include PhpSpreadsheet library
require_once 'vendor/autoload.php';

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$db_name = 'db';
$username = 'root';
$password = '';

try {
  // PDO connection
  $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Error connecting to database: " . $e->getMessage());
}

$upload_dir = 'uploads/';

if (!is_dir($upload_dir)) {
  mkdir($upload_dir, 0775, true); // Create directory with permissions
}

print_r($_FILES);

// Handle uploaded files
if (isset($_FILES['files'])) {
  $errors = [];

  foreach ($_FILES['files']['error'] as $key => $error) {
    if ($error === UPLOAD_ERR_OK) {
      $filename = $_FILES['files']['name'][$key];
      $tmp_name = $_FILES['files']['tmp_name'][$key];
    

      // Check for valid Excel file (replace with your extension check if needed)
      if (pathinfo($filename)['extension'] === 'xlsx') {
        // Generate unique filename 
        // $new_filename = uniqid('', true) . '.' . pathinfo($filename)['extension']; 
        // $destination = $upload_dir . $new_filename;

        $destination = $upload_dir . $filename;

        // Move uploaded file to the upload directory
        if (move_uploaded_file($tmp_name, $destination)) {
          // $verification_result = verify_data_python($destination); // Call Python script with full path
          // if ($verification_result['success']) {
          //   processExcelFile($destination, $conn);
          // } else {
          //   $errors[] = "Data verification failed for '" . $filename . "': " . $verification_result['message'];
          //   // Optionally, remove the uploaded file here if verification fails
          // }
          echo 'Moving succes !';
        } else {
          $errors[] = "Error moving file '" . $filename . "'";
        }
      } else {
        $errors[] = "Invalid file format for '" . $filename . "'";
      }
    } else {
      $errors[] = "Error uploading '" . $filename . "': " . $_FILES['files']['error'][$key];
    }
  }

  // Display any errors encountered during upload
  if ($errors) {
    echo "<b>Upload Errors:</b><br>";
    foreach ($errors as $error) {
      echo "- " . $error . "<br>";
    }
  }
} else {
  echo "No files selected for upload.";
}

// Close database connection (PDO handles it automatically)

function verify_data_python($filepath) {
  // Assuming Python script is in the same directory
  $python_output = exec("python verify_data.py $filepath 2>&1", $output_array, $return_var);

  if ($return_var === 0) {
    return ['success' => true, 'message' => implode("\n", $output_array)];
  } else {
    return ['success' => false, 'message' => implode("\n", $output_array)];
  }
}

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

?>

