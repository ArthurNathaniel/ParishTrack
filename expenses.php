<?php
// Include the database connection
include('db.php');

// Initialize variables
$date = $amount = $purpose = '';
$error = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["date"];
    $amount = $_POST["amount"];
    $purpose = $_POST["purpose"];

    // Validate input
    if (empty($date) || empty($amount) || empty($purpose)) {
        $error = "Please fill in all fields.";
    } else {
        // Insert the expenditure record into the database
        $sql = "INSERT INTO expenditures (date, amount, purpose) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sds", $date, $amount, $purpose);
            if ($stmt->execute()) {
                $successMessage = "Expenditure recorded successfully!"; 
            } else {
                $error = "Failed to record expenditure.";
            }
            $stmt->close();
        } else {
            $error = "Failed to prepare the SQL statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Expenditure</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/view_revenues.css">
</head>

<body>
    <div class="view_revenues_all">
        <?php include 'sidebar.php'; ?>
        <div class="view_revenues_box">
            <div class="view_revenues_title">
                <h2>Record Expenditure</h2>
            </div>

            <!-- Expenditure Form -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="expenditure_form">
                <div class="forms">
                    <label for="date">Date:</label>
                    <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
                </div>

                <div class="forms">
                    <label for="amount">Amount (GHS):</label>
                    <input type="number" name="amount" value="<?php echo htmlspecialchars($amount); ?>" step="0.01" required>
                </div>
                <div class="forms">
                    <label for="purpose">Purpose:</label>
                    <textarea name="purpose" required><?php echo htmlspecialchars($purpose); ?></textarea>
                </div>
                <div class="forms">
                    <button type="submit">Record Expenditure</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Display success or error message using JavaScript alert -->
    <?php if (isset($successMessage)) : ?>
        <script>
            alert("<?php echo $successMessage; ?>"); window.location.href = 'expenses.php';
        </script>
    <?php elseif (!empty($error)) : ?>
        <script>
            alert("<?php echo $error; ?>");
        </script>
    <?php endif; ?>
</body>

</html>
