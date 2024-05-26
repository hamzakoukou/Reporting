<?php
include 'connection.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['register-username'];
    $email = $_POST['register-email'];
    $password = $_POST['register-password']; // Consider hashing the password
    $last_login = date('Y-m-d H:i:s'); // Current time as last login
    $super_var = 0; // Default value for super_var

    $query = "INSERT INTO users (username, email, password, last_login, super_var) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $pdo->prepare($query)) {
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        $stmt->bindParam(3, $password, PDO::PARAM_STR);
        $stmt->bindParam(4, $last_login, PDO::PARAM_STR);
        $stmt->bindParam(5, $super_var, PDO::PARAM_INT);
        $stmt->execute();

        header("location: login.php"); // Redirect to login page
        exit;
    } else {
        $error = 'Error in registration. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html class="no-focus">
    <head>
        <meta charset="utf-8">
        <title>Sign Up</title>
        <link rel="stylesheet" href="assets/css/Linedata.css">
    </head>
    <body>
        <div class="content overflow-hidden">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <div class="block block-themed animated fadeIn">
                        <div class="block-header bg-success">
                            <h3 class="block-title">Register</h3>
                        </div>
                        <div class="block-content block-content-full block-content-narrow">
                            <form class="form-horizontal" action="signup.php" method="post">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <input class="form-control" type="text" id="register-username" name="register-username" placeholder="Please enter a username" required>
                                        <label for="register-username">Username</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <input class="form-control" type="email" id="register-email" name="register-email" placeholder="Please provide your email" required>
                                        <label for="register-email">Email</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <input class="form-control" type="password" id="register-password" name="register-password" placeholder="Choose a strong password.." required>
                                        <label for="register-password">Password</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <button class="btn btn-block btn-success" type="submit">Sign Up</button>
                                    </div>
                                </div>
                            </form>
                            <?php if ($error): ?>
                                <p class="text-danger"><?= htmlspecialchars($error) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/js/core/jquery.min.js"></script>
        <script src="assets/js/core/bootstrap.min.js"></script>
    </body>
</html>
