<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection
    include_once("db_connection.php");

    // Retrieve form data
    $accountType = $_POST["accountType"]; // Whether Buyer or Seller. Should be same as name="accountType" defined in the register.php file
    $username = $_POST["username"];  // should be same as name="username" defined in the register.php file
    $password = $_POST["signup-password"];  // should be same as name="signup-password" defined in the register.php file
    $passwordConfirmation = $_POST["confirm-password"];  // should be same as name="confirm-password" defined in the register.php file
    $email = isset($_POST["email"]);  // should be same as name="email" defined in the register.php file

     // should be same as name="email" defined in the register.php file
    $address = $_POST["address"];  // should be same as name="address" defined in the register.php file
    $postcode = $_POST["postcode"];  // should be same as name="postcode" defined in the register.php file

    // Validate password confirmation (optional but recommended)
    if ($password !== $passwordConfirmation) {
        header("Location: register.php?error=Passwords do not match");
        exit; // Exit if passwords don't match
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists
    $stmt_check_username = $pdo->prepare("SELECT COUNT(*) AS count FROM Users WHERE userName = ?");
    $stmt_check_username->execute([$username]);
    $row = $stmt_check_username->fetch(PDO::FETCH_ASSOC);
    $username_count = $row['count'];

    if ($username_count > 0) {
        // Username already exists, handle accordingly (e.g., show an error message)
        header("Location: register.php?error=Username already exists. Please choose a different username.");
        exit;
    }

    // Prepare and execute the insert query
    $stmt = $pdo->prepare("INSERT INTO Users (userRole, userName, hashedPassword, email, address, postcode) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$accountType, $username, $hashedPassword, $email, $address, $postcode]);

    // Check if insertion was successful
    if ($stmt->rowCount() > 0) {
        // Redirect based on account type
        if ($accountType === 'seller') {
            header("Location: seller.php");
            exit();

        } elseif ($accountType === 'buyer') {
            header("Location: buyer.php");
            exit();
        }
    } else {
        echo "Error registering user";
    }
}
?>
