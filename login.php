<?php
session_start();

require 'conection.php';

// Remove Server and X-Powered-By headers
header_remove("Server");
header_remove("X-Powered-By");

// Set security headers
header("Content-Security-Policy: default-src 'self';");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

function setAuthCookie($con, $username) {//function to set a cookie 
    $token = base64_encode(random_bytes(32));//cookie
    $expiry = time() + 3600;//duration of cookie id current time + hour
    setcookie('auth_token', $token, $expiry, '/', '', false, true);//cookie name auth_cookie

    $query = "UPDATE users SET auth_token = $1 WHERE username = $2";//prepared statement to add cookie to the db
    $stmt = pg_prepare($con, "update_auth_token", $query);
    if (!$stmt) {
        die("Prepare statement failed: " . pg_last_error($con));
    }
    $result = pg_execute($con, "update_auth_token", array($token, $username));
    if (!$result) {
        die("Execute statement failed: " . pg_last_error($con));
    }
}

if (isset($_POST["submit"])) {
    $username = htmlspecialchars($_POST["uname"]);
    $password = htmlspecialchars($_POST["password"]);// check for any speical characters

    $query = "SELECT * FROM users WHERE username = $1";//fetching data from db
    $stmt = pg_prepare($con, "login_query", $query);
    if (!$stmt) {
        die("Prepare statement failed: " . pg_last_error($con));
    }
    $result = pg_execute($con, "login_query", array($username));
    if (!$result) {
        die("Execute statement failed: " . pg_last_error($con));
    }

    $row = pg_fetch_assoc($result);

    if (pg_num_rows($result) > 0) {//check for username
        if (password_verify($password, $row["password"])) {//verify entered password with hashed password in the db
            $_SESSION["login"] = true;
            $_SESSION["username"] = $username;
            setAuthCookie($con, $username);
            http_response_code(200); // OK
            header("Location: user.html");
            exit;
        } else {
            echo "Error: Invalid username or password";
            http_response_code(401); // Unauthorized
        }
    } else {
        echo "Error: Invalid username or password";
        header("Location: login.html");
        http_response_code(401); // Unauthorized
    }
}

pg_close($con);
?>
