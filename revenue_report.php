<?php
// Include the database connection
include('db.php');

// Initialize variables
$startDate = $endDate = '';
$revenueTotals = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["start_date"], $_GET["end_date"])) {
    $startDate = $_GET["start_date"];
    $endDate = $_GET["end_date"];

    // Fetch totals for each revenue type within the selected date range
    $sql = "
        SELECT rt.revenue_name, SUM(rr.amount) AS total_amount 
        FROM recorded_revenues rr 
        INNER JOIN revenue_types rt ON rr.revenue_id = rt.id 
        WHERE rr.record_date BETWEEN ? AND ?
        GROUP BY rt.revenue_name
        ORDER BY rt.revenue_name ASC";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $revenueTotals[] = $row;
        }

        $stmt->close();
    }
} else {
    // Fetch totals for all revenue types if no filter is applied
    $sql = "
        SELECT rt.revenue_name, SUM(rr.amount) AS total_amount 
        FROM recorded_revenues rr 
        INNER JOIN revenue_types rt ON rr.revenue_id = rt.id 
        GROUP BY rt.revenue_name
        ORDER BY rt.revenue_name ASC";

    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $revenueTotals[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Totals by Type</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/view_revenues.css">
</head>

<body>
    <div class="view_revenues_all">
        <?php include 'sidebar.php'; ?>
        <div class="view_revenues_box">
            <div class="view_revenues_title">
                <h2>Revenue Totals by Type</h2>
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
            <!-- Revenue Totals Table -->
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Revenue Type</th>
                        <th>Total Amount (GHS)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($revenueTotals) > 0) : ?>
                        <?php foreach ($revenueTotals as $index => $revenue) : ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($revenue['revenue_name']); ?></td>
                                <td><?php echo number_format($revenue['total_amount'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3">No revenue records found for the selected date range.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>