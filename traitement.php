<?php
include 'layout/header.php'; ?>

<main id="main-container" style="display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f4f4;">
    <div class="month-selection-form">
        <?php
            // Check if a month has been submitted
            if (isset($_POST['submit']) && isset($_POST['month'])) {
                echo "<div class='loading-icon'><i class='fa fa-spinner fa-spin'></i> Processing...</div>";

                $selectedMonth = $_POST['month'];
                $host = 'localhost';
                $db_name = 'dbname';
                $username = 'username';
                $password = 'password';

                // Call the Python script to process data
                $command = escapeshellcmd("python3 traitement.py " . escapeshellarg($selectedMonth) . " " . escapeshellarg($host) . " " . escapeshellarg($db_name) . " " . escapeshellarg($username) . " " . escapeshellarg($password));
                $output = shell_exec($command);
                $result = json_decode($output, true);

                echo "<script>document.querySelector('.loading-icon').style.display = 'none';</script>";

                if ($result && $result['success']) {
                    echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Data processing complete.</div>";
                    header("Location: overview.php?month=" . urlencode($selectedMonth)); // Redirect to overview.php
                    exit;
                } else {
                    echo "<div class='alert alert-danger'><i class='fa fa-exclamation-circle'></i> Data processing failed.</div>";
                }
            }
        ?>

        <form method="POST" action="" class="form-group">
            <label for="month">Select Month (YYYY-MM):</label>
            <input type="month" id="month" name="month" required>
            <button type="submit" name="submit" class="btn btn-primary">Start Processing</button>
        </form>
    </div>
</main>

<script>
document.querySelector('form').addEventListener('submit', function() {
    document.querySelector('.loading-icon').style.display = 'block';
});
</script>

<?php include 'layout/footer.php'; // Ensure this file contains the necessary closing HTML tags ?>

