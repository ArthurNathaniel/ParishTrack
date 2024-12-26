<?php
// Include the database connection
include('db.php');

// Initialize the revenue data array
$revenues = [];

// Fetch all revenue types from the database
$sql = "SELECT revenue_name, revenue_date FROM revenue_types ORDER BY revenue_date DESC";
if ($result = $conn->query($sql)) {
    // Store the results in the $revenues array
    while ($row = $result->fetch_assoc()) {
        $revenues[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Revenue Types</title>
    <?php include 'cdn.php'?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/view_revenue.css">
</head>

<body>
    <div class="view_revenue_all">
        <?php include 'sidebar.php'?>
        <div class="view_revenue_box">
            <div class="view_revenue_title">
                <h2>Revenue Types</h2>
            </div>
            <table class="revenue_table">
                <thead>
                    <tr>
                        <th>Revenue Name</th>
                        <th>Revenue Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($revenues)) : ?>
                        <?php foreach ($revenues as $revenue) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($revenue['revenue_name']); ?></td>
                                <td><?php echo htmlspecialchars($revenue['revenue_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="2">No revenue types found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
