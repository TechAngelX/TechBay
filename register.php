<?php include_once("header.php") ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="my-3">Register New Account</h2>

    <form id="registrationForm" method="POST" action="process_registration.php">
        <div class="form-group">
            <label for="accountType"><strong>Registering as a:</strong></label>
            <div class="row">
                <div class="col-auto">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer" checked>
                        <label class="form-check-label" for="accountBuyer">Buyer</label>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
                        <label class="form-check-label" for="accountSeller">Seller</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="username"><strong>Username</strong><span class="required error" id="username-info"></span></label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                <small id="usernameHelp" class="form-text text-muted">Check availability</small>
            </div>
            <div class="form-group col-md-4">
                <label for="signup-password"><strong>Password</strong><span class="required error" id="signup-password-info"></span></label>
                <input type="password" class="form-control" id="signup-password" name="signup-password" placeholder="Enter your password" required>
            </div>
            <div class="form-group col-md-4">
                <label for="confirm-password"><strong>Confirm Password</strong><span class="required error" id="confirm-password-info"></span></label>
                <input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
            </div>
        </div>

        <!-- Email, Address, Postcode -->
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="email"><strong>Email</strong></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                <small id="emailHelp" class="form-text text-muted">Optional</small>
            </div>
            <div class="form-group col-md-4">
                <label for="address"><strong>Address</strong></label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter your address">
                <small id="addressHelp" class="form-text text-muted">Optional</small>
            </div>
            <div class="form-group col-md-4">
                <label for="postcode"><strong>Postcode</strong></label>
                <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Enter your postcode">
                <small id="postCodeHelp" class="form-text text-muted">Optional</small>
            </div>
        </div>

        <!-- Login Link -->
        <div class="form-group text-center">
            <p>Already have an account? <a href="#" data-toggle="modal" data-target="#loginModal">Login</a></p>
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </div>
    </form>
</div>

<script>
    document.getElementById("registrationForm").addEventListener("submit", async function(event) {
        event.preventDefault(); // Prevent form submission initially

        var password = document.getElementById("signup-password").value;
        var confirmPassword = document.getElementById("confirm-password").value;
        var passwordError = document.getElementById("confirm-password-info");
        var username = document.getElementById("username").value;
        var usernameInfo = document.getElementById("username-info");

        // Reset previous error messages
        passwordError.textContent = "";
        passwordError.classList.remove("error-msg");
        usernameInfo.textContent = "";

        // Validate password matching
        if (password !== confirmPassword) {
            passwordError.innerHTML = "<b>Passwords do not match.</b>";
            passwordError.style.fontSize = "smaller";
            passwordError.classList.add("error-msg");
            return; // Exit the function to prevent form submission
        }

        // Check username availability
        let isUsernameAvailable = await checkUsernameAvailability(username);
        if (isUsernameAvailable) {
            // If username is available and passwords match, submit the form
            event.target.submit();
        } else {
            usernameInfo.textContent = "Username is already taken.";
            usernameInfo.style.color = "red";
        }
    });

    async function checkUsernameAvailability(username) {
        try {
            let response = await fetch('check_username.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ username: username }),
            });
            let data = await response.json();
            return data.available;
        } catch (error) {
            console.error('Error checking username availability:', error);
            return false; // Treat error as username not available
        }
    }
</script>

</body>
</html>
