<?php
// Include the database connection
include('db.php');

// Initialize variables
$searchTerm = '';
$startDate = $endDate = '';
$expenditures = [];

// Handle search form submission
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search_term'], $_GET['start_date'], $_GET['end_date'])) {
    $searchTerm = $_GET['search_term'];
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];

    // SQL query for filtered results
    $sql = "SELECT * FROM expenditures WHERE 1";

    // Add date range filter if provided
    if (!empty($startDate) && !empty($endDate)) {
        $sql .= " AND date BETWEEN ? AND ?";
    }

    // Add search term filter if provided
    if (!empty($searchTerm)) {
        $sql .= " AND (purpose LIKE ?)";
    }

    // Prepare and execute the query
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters based on which filters are applied
        if (!empty($startDate) && !empty($endDate) && !empty($searchTerm)) {
            $searchTerm = "%$searchTerm%";
            $stmt->bind_param("sss", $startDate, $endDate, $searchTerm);
        } elseif (!empty($startDate) && !empty($endDate)) {
            $stmt->bind_param("ss", $startDate, $endDate);
        } elseif (!empty($searchTerm)) {
            $searchTerm = "%$searchTerm%";
            $stmt->bind_param("s", $searchTerm);
        }

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();
        
        // Fetch all records
        while ($row = $result->fetch_assoc()) {
            $expenditures[] = $row;
        }
        
        // Close the prepared statement
        $stmt->close();
    }
} else {
    // Fetch all records if no filter is applied
    $sql = "SELECT * FROM expenditures ORDER BY date DESC";
    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $expenditures[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenditure History</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/view_revenues.css">
</head>

<body>
    <div class="view_revenues_all">
        <?php include 'sidebar.php'; ?>
        <div class="view_revenues_box">
            <div class="view_revenues_title">
                <h2>Expenditure History</h2>
            </div>

            <!-- Search Form -->
            <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="search_form">
                <div class="forms">
                    <label for="search_term">Search by Purpose:</label>
                    <input type="text" name="search_term" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search purpose">
                </div>

                <div class="forms">
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                </div>

                <div class="forms">
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                </div>

                <div class="forms">
                    <button type="submit">Search</button>
                </div>
            </form>
<br>
<br>

            <!-- Expenditure History Table -->
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Amount (GHS)</th>
                        <th>Purpose</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($expenditures) > 0) : ?>
                        <?php foreach ($expenditures as $index => $expenditure) : ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($expenditure['date']); ?></td>
                                <td><?php echo number_format($expenditure['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($expenditure['purpose']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4">No expenditure records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
