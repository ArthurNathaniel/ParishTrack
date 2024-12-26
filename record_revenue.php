<?php
// Include the database connection
include('db.php');

// Initialize variables
$revenueId = $amount = $recordDate = '';
$revenueIdErr = $amountErr = '';

// Set the current date automatically
$recordDate = date('Y-m-d');

// Fetch all revenue types for the dropdown
$revenueTypes = [];
$sql = "SELECT id, revenue_name FROM revenue_types ORDER BY revenue_name ASC";
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $revenueTypes[] = $row;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate revenue selection
    if (empty($_POST["revenue_id"])) {
        $revenueIdErr = "Please select a revenue type.";
    } else {
        $revenueId = $_POST["revenue_id"];
    }

    // Validate amount
    if (empty($_POST["amount"])) {
        $amountErr = "Amount is required.";
    } elseif (!is_numeric($_POST["amount"]) || $_POST["amount"] <= 0) {
        $amountErr = "Please enter a valid amount.";
    } else {
        $amount = $_POST["amount"];
    }

    // If no errors, proceed to insert the record
    if (empty($revenueIdErr) && empty($amountErr)) {
        $sql = "INSERT INTO recorded_revenues (revenue_id, amount, record_date) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ids", $revenueId, $amount, $recordDate);
            if ($stmt->execute()) {
                echo "<script>alert('Revenue recorded successfully!');</script>";
            } else {
                echo "<script>alert('Error recording revenue: " . $stmt->error . "');</script>";
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
    <title>Record Revenue</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/record_revenue.css">
</head>

<body>
    <div class="record_revenue_all">
        <?php include 'sidebar.php'; ?>
        <div class="record_revenue_box">
            <div class="record_revenue_title">
                <h2>Record Revenue</h2>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="forms">
                    <label for="revenue_id">Revenue Type:</label>
                    <select name="revenue_id">
                        <option value="" hidden>Select Revenue Type</option>
                        <?php foreach ($revenueTypes as $type) : ?>
                            <option value="<?php echo $type['id']; ?>" <?php echo $revenueId == $type['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($type['revenue_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span><?php echo $revenueIdErr; ?></span>
                </div>

                <div class="forms">
                    <label for="amount">Amount (GHS):</label>
                    <input type="number" name="amount" value="<?php echo $amount; ?>" step="0.01">
                    <span><?php echo $amountErr; ?></span>
                </div>

                <div class="forms">
                    <label for="record_date">Record Date:</label>
                    <input type="text" name="record_date" value="<?php echo $recordDate; ?>" readonly>
                </div>

                <div class="forms">
                    <button type="submit">Record Revenue</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
