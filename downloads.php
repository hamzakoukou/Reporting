<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include 'layout/header.php';

// Ensure the downloads directory exists
$downloadsDir = 'downloads/';
if (!is_dir($downloadsDir)) {
    mkdir($downloadsDir, 0777, true);
}

$month = $_GET['month'];

function fetchMonthlyData($pdo, $month) {
    $stmt = $pdo->prepare("SELECT * FROM `your_table_name` WHERE DATE_FORMAT(your_date_column, '%Y-%m') = :month");
    $stmt->bindParam(':month', $month);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$monthlyData = fetchMonthlyData($pdo, $month);

if (!empty($monthlyData)) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->fromArray(array_keys($monthlyData[0]), NULL, 'A1');
    $sheet->fromArray($monthlyData, NULL, 'A2');

    $writer = new Xlsx($spreadsheet);
    $fileName = "monthly_data_$month.xlsx";
    $filePath = $downloadsDir . $fileName;

    $writer->save($filePath);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    readfile($filePath);
    exit;
} else {
    echo "No data available for download.";
}
?>

<div class="content">
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Downloaded Reports</h3>
        </div>
        <div class="block-content">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Date Downloaded</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch downloaded files
                    $downloadFiles = scandir($downloadsDir); // Adjust path as necessary
                    foreach ($downloadFiles as $file) {
                        if ($file !== "." && $file !== "..") {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($file) . "</td>";
                            echo "<td>" . date("F d Y H:i:s.", filemtime($downloadsDir . $file)) . "</td>";
                            echo "<td><a href='downloads/$file' download>Download</a></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'layout/footer.php';?>
