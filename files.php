<?php 
include 'layout/header.php';

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
                            <input type="file" id="file1" name="files[]" multiple required>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                        <div id="generalLoading" style="display:none;">Loading...</div>
                    </div>
                </div>
                <!-- END General File Upload Widget -->
                
                <!-- Specific File Upload Widget for 'Fichier de correspondance de noms.xlsx' -->
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">Fichier de correspondance de noms</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <?php
                        $filePath = 'uploads/Fichier de correspondance de noms.xlsx';
                        if (file_exists($filePath)) {
                            echo "<div><strong>File exists:</strong> <a href='$filePath'>Fichier de correspondance de noms.xlsx</a></div>";
                        } else {
                            ?>
                            <form id="specificUploadForm" action="process_upload.php" method="post" enctype="multipart/form-data">
                                <input type="file" id="fileCorrespondance" name="files[]" multiple required>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>
                            <div id="specificLoading" style="display:none;">Loading...</div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- END Specific File Upload Widget -->
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</main>

<script>
document.getElementById('generalUploadForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const loadingIndicator = document.getElementById('generalLoading');
    loadingIndicator.style.display = 'block'; // Show loading indicator

    fetch('process_upload.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        loadingIndicator.style.display = 'none'; // Hide loading indicator
        alert(data.message);
    })
    .catch(error => {
        loadingIndicator.style.display = 'none'; // Hide loading indicator
        alert('Error: ' + error.message);
    });
});

document.getElementById('specificUploadForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const loadingIndicator = document.getElementById('specificLoading');
    loadingIndicator.style.display = 'block'; // Show loading indicator

    fetch('process_upload.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        loadingIndicator.style.display = 'none'; // Hide loading indicator
        alert(data.message);
        if (data.success) {
            // Reload or update the page content to reflect the uploaded file
            window.location.reload();
        }
    })
    .catch(error => {
        loadingIndicator.style.display = 'none'; // Hide loading indicator
        alert('Error: ' + error.message);
    });
});
</script>

<?php include 'layout/footer.php'; ?>
