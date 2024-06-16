<?php
// Include database connection
include_once("db_connection.php");

// Initialize variables
$username = $password = $username_error = $password_error = $login_err = "";

// Handle POST login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (isset($_POST["username"])) {
        $username = trim($_POST["username"]);
        if (empty($username)) {
            $username_error = "Please enter your username";
        }
    } else {
        $username_error = "Please enter your username";
    }

    // Validate password
    if (isset($_POST["password"])) {
        $password = trim($_POST["password"]);
        if (empty($password)) {
            $password_error = "Please enter your password";
        }
    } else {
        $password_error = "Please enter your password";
    }

    // Proceed if no errors
    if (empty($username_error) && empty($password_error)) {
        // Prepare SQL statement
        $sql = "SELECT user_ID, userName, hashedPassword, userRole FROM Users WHERE userName = :username";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind parameters
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Check if username exists
                if ($stmt->rowCount() == 1) {
                    // Fetch result
                    $row = $stmt->fetch();
                    $userId = $row['user_ID'];
                    $username = $row['userName'];
                    $hashed_password = $row['hashedPassword'];
                    $userRole = $row['userRole'];

                    // Verify password
                    if (password_verify($password, $hashed_password)) {
                        // Start session
                        session_start();

                        // Store data in session variables
                        $_SESSION["logged_in"] = true;
                        $_SESSION["id"] = $userId;
                        $_SESSION["username"] = $username;
                        $_SESSION["account_type"] = $userRole;

                        // Redirect based on account type
                        if ($userRole === 'buyer') {
                            header("Location: buyer.php");
                            exit();
                        } elseif ($userRole === 'seller') {
                            header("Location: seller.php");
                            exit();
                        } else {
                            // Handle unexpected account type (though it should not happen)
                            $login_err = "Unknown account type.";
                        }
                    } else {
                        // Invalid password
                        $login_err = "Invalid password.";
                    }
                } else {
                    // Username doesn't exist
                    $login_err = "Username doesn't exist";
                }
            } else {
                // Execution error
                $login_err = "Oops! Something went wrong. Please try again later.";
            }
        } else {
            // Failed to prepare statement
            $login_err = "Oops! Something went wrong. Please try again later.";
        }
    }
}

// Redirect to header.php with error message
header("Location: header.php?login_err=" . urlencode($login_err));
exit();
?>
