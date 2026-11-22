
<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "repairman") {
    header("Location: login.php");
    exit();
}

$id = $_SESSION["user_id"];

$sql = "SELECT verified FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user["verified"] != 1) {
    die("Your account is waiting for admin verification");
}

$sql = "SELECT * FROM jobs WHERE status = 'Open' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Repairman Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <h2>FixMate</h2>
    <a href="dashboard.php">Available Jobs</a>
    <a href="my_jobs.php">My Jobs</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

    <h2>Available Repair Jobs</h2>

    <?php while ($job = mysqli_fetch_assoc($result)) { ?>

        <div class="job">

            <h3><?php echo htmlspecialchars($job["title"]); ?></h3>

            <p><?php echo htmlspecialchars($job["description"]); ?></p>

            <p>Location: <?php echo htmlspecialchars($job["location"]); ?></p>

            <a href="quote.php?id=<?php echo $job["id"]; ?>">Submit Quotation</a>

        </div>

    <?php } ?>

</div>

</body>
</html>
