<?php 
include 'layout/header.php';

$requiredTables = ['collaborators', 'production', 'charges'];
$allFilesDone = true;
foreach ($requiredTables as $table) {
    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
    if ($stmt->rowCount() == 0) {
        $allFilesDone = false;
        break;
    }
}
$_SESSION['all_files_done'] = $allFilesDone;

?>

<main id="main-container">
   
    <!-- Page Content -->
    <div class="content">
        
        <div class="row">
            <div class="col-lg-12">
                <!-- General File Upload Widget -->
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">File Upload</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <form id="generalUploadForm" action="process_upload.php" method="post" enctype="multipart/form-data">
                            <label>Fichier des collaborateurs</label>
                            <input type="file" id="fileCollaborators" name="collaborators" required><br>
                            <label>Fichier de Production</label>
                            <input type="file" id="fileProduction" name="production" required><br>
                            <label>Fichier de Charges Directes</label>
                            <input type="file" id="fileCharges" name="charges" required><br>
                            <label>Fichier de Charges Indirectes</label>
                            <input type="file" id="fileCharges" name="incharges" multiple required>
                            <br>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                        <div id="generalLoading" style="display:none;">Loading...</div>
                    </div>
                </div>
                <!-- END General File Upload Widget -->
     
                <!-- Specific File Upload Widget for 'Fichier de correspondance de noms.xlsx' -->
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">Additional Files </h3>
                    </div>
                    <div class="block-content block-content-full">
                        <?php
                        $refPath = 'uploads/Fichier de correspondance de noms.xlsx';
                        $annexePath = 'uploads/Annexe.xlsx';
                        if (file_exists($refPath) && file_exists($annexePath)) {
                            echo "<div><strong>File exists:</strong> <a href='$refPath'>Fichier de correspondance de noms.xlsx</a></div>";
                            echo "<div><strong>File exists:</strong> <a href='$annexePath'>Annexe.xlsx</a></div>";
                        } else {
                            ?>
                            <form id="specificUploadForm" action="process_upload.php" method="post" enctype="multipart/form-data">
                                <input type="file" id="fileCorrespondance" name="refFile" multiple required>
                                <input type="file" id="fileAnnexe" name="annexeFile" multiple required>
                                <br>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>
                            <div id="specificLoading" style="display:none;">Loading...</div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- END Specific File Upload Widget -->

                
            <div class="content">
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">Uploaded Files</h3>
                    </div>
                    <div class="block-content">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Date Uploaded</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Example PHP code to fetch files
                                $files = scandir('uploads/'); // Adjust path as necessary
                                foreach ($files as $file) {
                                    if ($file !== "." && $file !== "..") {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($file) . "</td>";
                                        echo "<td>" . date("F d Y H:i:s.", filemtime('uploads/' . $file)) . "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</main>

<script>

document.getElementById('generalUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const loadingIndicator = document.getElementById('generalLoading');
    loadingIndicator.style.display = 'block'; // Show loading indicator

    fetch('process_upload.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text()) // Get the response as text
    .then(text => {
        loadingIndicator.style.display = 'none'; // Hide loading indicator
        console.log('Server Response:', text); // Log the full server response
        alert('Server Response: ' + text); // Show the full server response in an alert
        location.reload(); // Optionally refresh the page
    })
    .catch(error => {
        loadingIndicator.style.display = 'none'; // Hide loading indicator
        console.error('Error:', error); // Log the error
        alert('Error: ' + error.message); // Show the error message in an alert
    });
});

document.getElementById('specificUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const loadingIndicator = document.getElementById('specificLoading');
    loadingIndicator.style.display = 'block'; // Show loading indicator

    fetch('process_upload.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text()) // Get the response as text
    .then(text => {
        loadingIndicator.style.display = 'none'; // Hide loading indicator
        console.log('Server Response:', text); // Log the full server response
        alert('Server Response: ' + text); // Show the full server response in an alert
        location.reload(); // Optionally refresh the page
    })
    .catch(error => {
        loadingIndicator.style.display = 'none'; // Hide loading indicator
        console.error('Error:', error); // Log the error
        alert('Error: ' + error.message); // Show the error message in an alert
    });
});

</script>

<?php include 'layout/footer.php'; ?>