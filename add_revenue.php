<?php
// Include the database connection
include('db.php');

// Initialize variables
$revenueName = $revenueDate = '';
$revenueNameErr = '';

// Set the current date automatically
$revenueDate = date('Y-m-d');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate revenue name
    if (empty($_POST["revenue_name"])) {
        $revenueNameErr = "Revenue name is required";
    } else {
        $revenueName = $_POST["revenue_name"];
    }

    // If no errors, proceed with adding the revenue type
    if (empty($revenueNameErr)) {
        // Check if the revenue type already exists
        $sql = "SELECT * FROM revenue_types WHERE revenue_name = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $revenueName);
            $stmt->execute();
            $result = $stmt->get_result();

            // If the revenue name exists, show an alert
            if ($result->num_rows > 0) {
                echo "<script>alert('Revenue type already exists.');</script>";
            } else {
                // Insert the new revenue type if it does not exist
                $sql = "INSERT INTO revenue_types (revenue_name, revenue_date) VALUES (?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ss", $revenueName, $revenueDate);
                    if ($stmt->execute()) {
                        echo "<script>alert('Revenue type added successfully!'); window.location.href='add_revenue.php';</script>";
                    } else {
                        echo "<script>alert('Error: " . $stmt->error . "');</script>";
                    }
                    $stmt->close();
                }
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Revenue Type</title>
    <?php include 'cdn.php'?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/add_revenue.css">
</head>

<body>
    <div class="add_revenue_all">
        <?php include 'sidebar.php'?>
        <div class="add_revenue_box">
            <div class="add_revenue_title">
                <h2>Add Revenue Type</h2>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="forms">
                    <label for="revenue_name">Revenue Name:</label>
                    <input type="text" name="revenue_name" value="<?php echo $revenueName; ?>">
                    <span><?php echo $revenueNameErr; ?></span>
                </div>

                <!-- The revenue date field is removed because it's automatically set -->
                <div class="forms">
                    <label for="revenue_date">Revenue Date:</label>
                    <input type="text" name="revenue_date" value="<?php echo $revenueDate; ?>" readonly>
                </div>

                <div class="forms">
                    <button type="submit">Add Revenue</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
