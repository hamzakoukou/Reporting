<?php
 require "layout/header.php";
// Check if the user is not logged in
if (!isset($_SESSION['user_logged_in'])) {
    // Redirect to login.php
    header('Location: login.php');
    exit; // Stop further execution of the script
}
?>

<style>
    .btn-lg {
    padding: 20px;
    font-size: 18px;
    width: 100%;
    margin-top: 20px;
    text-align: center;
}

.btn-lg h3 {
    margin-top: 10px;
    font-size: 24px;
}

.btn-lg p {
    font-size: 16px;
    color: #ffffff;
}

.animated {
    animation-duration: 1s;
    animation-fill-mode: both;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.fadeIn {
    animation-name: fadeIn;
}

</style>
            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Header -->
                <div class="content bg-image overflow-hidden" style="background:#a32034;">
                    <div class="push-50-t push-15">
                        <h1 class="h2 text-white animated zoomIn">Dashboard</h1>
                        <h2 class="h5 text-white-op animated zoomIn">Welcome <?php echo $_SESSION['username']; ?></h2>
                    </div>
                </div>
                <!-- END Page Header -->

                <!-- Navigation Divs -->
                <div class="content bg-white border-b">
                    <div class="row text-center">
                        <div class="col-xs-4 animated fadeIn">
                            <a href="files.php" class="btn btn-lg btn-primary">
                                <i class="si si-folder"></i>
                                <h3>Files</h3>
                                <p>Upload and manage your files here.</p>
                            </a>
                        </div>
                        <div class="col-xs-4 animated fadeIn" style="animation-delay: 0.5s;">
                            <a href="configurations.php" class="btn btn-lg btn-primary">
                                <i class="si si-settings"></i>
                                <h3>Configurations</h3>
                                <p>Set up your configurations here.</p>
                            </a>
                        </div>
                        <div class="col-xs-4 animated fadeIn" style="animation-delay: 1s;">
                            <?php
                            $filesUploaded = $_SESSION['all_files_uploaded'] ?? false;
                            $configurationsDone = $_SESSION['all_configurations_done'] ?? false;
                            if ($filesUploaded && $configurationsDone): ?>
                                <a href="traitement.php" class="btn btn-lg btn-primary">
                                    <i class="si si-bar-chart"></i>
                                    <h3>Results</h3>
                                    <p>View your results here.</p>
                                </a>
                            <?php else: ?>
                                <button class="btn btn-lg btn-primary" disabled>
                                    <i class="si si-bar-chart"></i>
                                    <h3>Results</h3>
                                    <p>Complete previous steps to view results.</p>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

 <?php require "layout/footer.php" ;?>
