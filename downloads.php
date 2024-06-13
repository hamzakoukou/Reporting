<?php
require 'vendor/autoload.php';
include 'layout/header.php';

// Ensure the downloads directory exists
$downloadsDir = 'downloads/';
if (!is_dir($downloadsDir)) {
    mkdir($downloadsDir, 0777, true);
}

$month = $_GET['month'] ?? NULL;

$command = escapeshellcmd("python download_reports.py " . escapeshellarg($month) . " " . escapeshellarg($host) . " " . escapeshellarg($db_name) . " " . escapeshellarg($username) . " " . escapeshellarg($password));
$output = shell_exec($command);

if (file_exists($output)) {
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"" . basename($output) . "\"");
    readfile($output);
    exit;
} else {
    echo "No data available for download.";
}
?>

<main id="main-container">
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
                    $downloadFiles = scandir($downloadsDir);
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
                </main>

<?php include 'layout/footer.php';?>
