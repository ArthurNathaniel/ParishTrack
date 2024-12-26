<?php
// Start the session to access the logged-in admin's data
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get current hour for greeting
$currentHour = date("H");
$greeting = "";

// Determine the time of day and set greeting message
if ($currentHour >= 5 && $currentHour < 12) {
    $greeting = "Good Morning";
} elseif ($currentHour >= 12 && $currentHour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}

// Array of Bible quotes about finances
$bibleQuotes = [
    "The blessing of the Lord brings wealth, without painful toil for it. - Proverbs 10:22",
    "Honor the Lord with your wealth, with the firstfruits of all your crops. - Proverbs 3:9",
    "For where your treasure is, there your heart will be also. - Matthew 6:21",
    "But remember the Lord your God, for it is he who gives you the ability to produce wealth. - Deuteronomy 8:18",
    "Whoever is faithful in little things is faithful in much. - Luke 16:10"
];

// Pick a random Bible quote
$randomQuote = $bibleQuotes[array_rand($bibleQuotes)];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/dashboard.css">
</head>

<body>
<?php include 'sidebar.php'?>

     <div class="dashboard_all">
        <div class="dashboard_box">
            <div class="dashboard_header">
                <h2><?php echo $greeting . ", " . $_SESSION['admin_email']; ?>!</h2>
            </div>

            <div class="bible_quote">
                <p><strong>Random Bible Quote about Finances:</strong></p>
                <blockquote><?php echo $randomQuote; ?></blockquote>
            </div>

            <div class="logout_btn">
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div> 
</body>

</html>
