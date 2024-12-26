<?php
// Include the database connection
include('db.php');

// Initialize variables
$totalRevenue = 0;
$totalExpenses = 0;
$remainingBalance = 0;
$filter = 'month'; // Default filter is 'month'

// Initialize filter variables
$selectedMonth = date('m');
$selectedYear = date('Y');
$selectedDate = date('Y-m-d');

// Check if the filter form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['filter'])) {
        $filter = $_POST['filter'];
    }
    if (isset($_POST['month'])) {
        $selectedMonth = $_POST['month'];
    }
    if (isset($_POST['year'])) {
        $selectedYear = $_POST['year'];
    }
    if (isset($_POST['date'])) {
        $selectedDate = $_POST['date'];
    }
}

// Set the date range based on the selected filter
switch ($filter) {
    case 'date':
        $startDate = $selectedDate;
        $endDate = $selectedDate;
        break;
    case 'month':
        $startDate = $selectedYear . '-' . $selectedMonth . '-01'; // First day of selected month
        $endDate = date('Y-m-t', strtotime($startDate)); // Last day of selected month
        break;
    case 'year':
        $startDate = $selectedYear . '-01-01'; // First day of selected year
        $endDate = $selectedYear . '-12-31'; // Last day of selected year
        break;
    default:
        $startDate = $selectedYear . '-' . $selectedMonth . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
}

// Fetch total revenue within the selected date range
$sqlRevenue = "SELECT SUM(amount) AS total_revenue FROM recorded_revenues WHERE record_date BETWEEN ? AND ?";
if ($stmtRevenue = $conn->prepare($sqlRevenue)) {
    $stmtRevenue->bind_param("ss", $startDate, $endDate);
    $stmtRevenue->execute();
    $resultRevenue = $stmtRevenue->get_result();
    $row = $resultRevenue->fetch_assoc();
    $totalRevenue = $row['total_revenue'] ?? 0; // Use 0 if no revenue is found
    $stmtRevenue->close();
}

// Fetch total expenses within the selected date range
$sqlExpenses = "SELECT SUM(amount) AS total_expenses FROM expenditures WHERE date BETWEEN ? AND ?";
if ($stmtExpenses = $conn->prepare($sqlExpenses)) {
    $stmtExpenses->bind_param("ss", $startDate, $endDate);
    $stmtExpenses->execute();
    $resultExpenses = $stmtExpenses->get_result();
    $row = $resultExpenses->fetch_assoc();
    $totalExpenses = $row['total_expenses'] ?? 0; // Use 0 if no expenses are found
    $stmtExpenses->close();
}

// Calculate the remaining balance
$remainingBalance = $totalRevenue - $totalExpenses;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue vs Expenses</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/revenue_expenses.css">
</head>

<body>
    <div class="revenue_expenses_all">
        <?php include 'sidebar.php'; ?>
        <div class="revenue_expenses_box">
            <div class="revenue_expenses_title">
                <h2>Revenue vs Expenses</h2>
            </div>

            <!-- Filter Form -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="filter-form">
              <div class="forms">
              <label for="filter">Select Period:</label>
                <select name="filter" id="filter" onchange="this.form.submit()">
                    <option value="month" <?php echo $filter == 'month' ? 'selected' : ''; ?>>This Month</option>
                    <option value="year" <?php echo $filter == 'year' ? 'selected' : ''; ?>>This Year</option>
                    <option value="date" <?php echo $filter == 'date' ? 'selected' : ''; ?>>Specific Date</option>
                </select>
              </div>

                <!-- Show Month Filter if Month is Selected -->
                <?php if ($filter == 'month' || $filter == 'year'): ?>
                  <div class="forms">
                  <label for="month">Month:</label>
                    <select name="month" id="month" onchange="this.form.submit()">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?php echo $selectedMonth == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                  </div>
                <?php endif; ?>

                <!-- Show Year Filter if Year is Selected -->
                <?php if ($filter == 'year'): ?>
                  <div class="forms">
                  <label for="year">Year:</label>
                    <select name="year" id="year" onchange="this.form.submit()">
                        <?php for ($y = 2020; $y <= date('Y'); $y++): ?>
                            <option value="<?php echo $y; ?>" <?php echo $selectedYear == $y ? 'selected' : ''; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                  </div>
                <?php endif; ?>

                <!-- Show Date Picker if Date is Selected -->
                <?php if ($filter == 'date'): ?>
                 <div class="forms">
                 <label for="date">Select Date:</label>
                 <input type="date" name="date" id="date" value="<?php echo $selectedDate; ?>" onchange="this.form.submit()">
                 </div>
                <?php endif; ?>
            </form>

            <div class="revenue_expenses_content">
    <table border="1" cellpadding="10" cellspacing="0" class="revenue-expenses-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount (GHS)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Total Revenue</strong></td>
                <td><?php echo number_format($totalRevenue, 2); ?></td>
            </tr>
            <tr>
                <td><strong>Total Expenses</strong></td>
                <td><?php echo number_format($totalExpenses, 2); ?></td>
            </tr>
            <tr>
                <td><strong>Remaining Balance</strong></td>
                <td><?php echo number_format($remainingBalance, 2); ?></td>
            </tr>
        </tbody>
    </table>
</div>

        </div>
    </div>
</body>

</html>
