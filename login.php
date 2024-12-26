<?php
// Include the database connection
include('db.php');

// Initialize variables
$email = $password = '';
$emailErr = $passwordErr = $loginErr = '';

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

    // If no errors, proceed with login
    if (empty($emailErr) && empty($passwordErr)) {
        // Check if email exists in the database
        $sql = "SELECT * FROM admins WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Email found, now check password
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    // Start session and set session variables
                    session_start();
                    $_SESSION['admin_id'] = $row['id'];
                    $_SESSION['admin_email'] = $row['email'];

                    // Redirect to admin dashboard or other page
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $loginErr = "Invalid password.";
                }
            } else {
                $loginErr = "Email not registered.";
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
    <title>Admin Login</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class="login_all">
        <div class="login_box">
            <div class="login_title">
                <h2><span>Parish</span>Track -  Login</h2>
                <p>(Admin)</p>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              
                <div class="forms">
                    <label for="email">Email Address:</label>
                    <input type="email" name="email" value="<?php echo $email; ?>">
                    <span><?php echo $emailErr; ?></span>
                </div>

                <div class="forms">
                    <label for="password">Password:</label>
                    <input type="password" name="password">
                    <span><?php echo $passwordErr; ?></span>
                </div>

                <div class="forms">
                    <button type="submit">Login</button>
                    <span><?php echo $loginErr; ?></span>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
