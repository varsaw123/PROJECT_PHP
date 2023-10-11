<?php
require 'conection.php';
session_start();

if (isset($_POST["submit"])) {
    // Set security headers
    header_remove("Server");
    header_remove("X-Powered-By");
    header("Content-Security-Policy: default-src 'self';");
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");

    // Validate user inputs
    $first = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST["uname"], FILTER_SANITIZE_STRING);
    $password = $_POST["password"]; // Don't filter the password.

    // Initialize a variable to track if there are any errors
    $error = false;
    $errorMsg = "";

    // Check if username is already taken
    $duplicate_check = pg_prepare($con, "duplicate_check", "SELECT * FROM users WHERE username = $1");
    $result = pg_execute($con, "duplicate_check", array($username));

    if (pg_num_rows($result) > 0) {
        // Username already taken
        $error = true;
        $errorMsg = "Username already taken.";
    } else {
        // Validate password strength
        if (strlen($password) < 8) {
            $error = true;
            $errorMsg = "Password must be at least 8 characters long.";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert user data into the database using prepared statement
            $insert_query = pg_prepare($con, "insert_query", "INSERT INTO users (firstname, username, password) VALUES ($1, $2, $3)");
            $insert_result = pg_execute($con, "insert_query", array($first, $username, $hashedPassword));

            if ($insert_result) {
                $_SESSION["username"] = $username;
                $_SESSION["login"] = true;
                header("Location: login.html");
                exit(); // Redirect and exit immediately.
            } else {
                $error = true;
                $errorMsg = "Error: " . pg_last_error($con);
            }
        }
    }
}

// Display error message if there was an issue
if ($error) {
    echo "Registration failed. $errorMsg";
}

pg_close($con);
?>
