<?php
// Include the database connection
include('db.php');

// Initialize variables
$startDate = $endDate = '';
$recordedRevenues = [];
$totalAmount = 0.0; // Initialize total revenue

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["start_date"], $_GET["end_date"])) {
    $startDate = $_GET["start_date"];
    $endDate = $_GET["end_date"];

    // Fetch filtered records
    $sql = "
        SELECT rr.id, rt.revenue_name, rr.amount, rr.record_date 
        FROM recorded_revenues rr 
        INNER JOIN revenue_types rt ON rr.revenue_id = rt.id 
        WHERE rr.record_date BETWEEN ? AND ?
        ORDER BY rr.record_date DESC";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $recordedRevenues[] = $row;
            $totalAmount += $row['amount']; // Accumulate the total amount
        }

        $stmt->close();
    }
} else {
    // Fetch all records if no filter is applied
    $sql = "
        SELECT rr.id, rt.revenue_name, rr.amount, rr.record_date 
        FROM recorded_revenues rr 
        INNER JOIN revenue_types rt ON rr.revenue_id = rt.id 
        ORDER BY rr.record_date DESC";

    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $recordedRevenues[] = $row;
            $totalAmount += $row['amount']; // Accumulate the total amount
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recorded Revenues</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/view_revenues.css">
</head>

<body>
    <div class="view_revenues_all">
        <?php include 'sidebar.php'; ?>
        <div class="view_revenues_box">
            <div class="view_revenues_title">
                <h2>Recorded Revenues</h2>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="filter_form">
               <div class="forms">
               <label for="start_date">Start Date:</label>
               <input type="date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" required>
               </div>

               <div class="forms">
               <label for="end_date">End Date:</label>
               <input type="date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" required>
               </div>

               <div class="forms">
               <button type="submit">Filter</button>
               </div>
            </form>
<br>
<br>
            <!-- Revenue Records Table -->
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Revenue Type</th>
                        <th>Amount (GHS)</th>
                        <th>Record Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($recordedRevenues) > 0) : ?>
                        <?php foreach ($recordedRevenues as $index => $revenue) : ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($revenue['revenue_name']); ?></td>
                                <td><?php echo number_format($revenue['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($revenue['record_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4">No recorded revenues found for the selected date range.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total Revenue</th>
                        <th colspan="2"> GHS <?php echo number_format($totalAmount, 2); ?> </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
