<?php
// Include the database connection
include('db.php');

// Initialize variables
$email = $password = '';
$emailErr = $passwordErr = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = $_POST["email"];
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
    }

    // If no errors, proceed with signup
    if (empty($emailErr) && empty($passwordErr)) {
        // Check if email already exists in the database
        $sql = "SELECT * FROM admins WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Email already exists
                $emailErr = "Email already registered.";
            } else {
                // Hash the password for security
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Prepare SQL to insert the admin data into the database
                $sql = "INSERT INTO admins (email, password) VALUES (?, ?)";

                // Prepare statement
                if ($stmt = $conn->prepare($sql)) {
                    // Bind parameters
                    $stmt->bind_param("ss", $email, $hashedPassword);

                    // Execute statement
                    if ($stmt->execute()) {
                        // Redirect after successful registration with an alert
                        echo "<script>alert('Admin registered successfully!'); window.location.href = 'login.php';</script>";
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    // Close statement
                    $stmt->close();
                }
            }
            // Close result
            $result->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/signup.css">
</head>

<body>
    <div class="signup_all">
        <div class="signup_box">
            <div class="signup_title">
            <h2><span>Parish</span>Track- Signup</h2>
            <p>(Admin)</p>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              
                    <div class="forms">
                        <label for="email">Email Address:</label>
                        <input type="email" name="email"  value="<?php echo $email; ?>">
                        <span><?php echo $emailErr; ?></span>
                    </div>
              
               
                    <div class="forms">
                        <label for="password">Password:</label>
                        <input type="password" name="password">
                        <span><?php echo $passwordErr; ?></span>
                    </div>
               
               <div class="forms">
               <button type="submit">Sign Up</button>
               </div>
            </form>
        </div>
    </div>
</body>

</html>
