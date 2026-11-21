
<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "homeowner") {
    header("Location: login.php");
    exit();
}

$id = $_SESSION["user_id"];

$sql = "SELECT * FROM jobs WHERE homeowner_id = ? ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Homeowner Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <h2>FixMate</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="create_job.php">Create Job</a>
    <a href="bookings.php">My Bookings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?></h2>

    <h3>My Repair Requests</h3>

    <a class="button" href="create_job.php">Create New Job</a>

    <?php while ($job = mysqli_fetch_assoc($result)) { ?>

        <div class="job">
            <h3><?php echo htmlspecialchars($job["title"]); ?></h3>

            <p><?php echo htmlspecialchars($job["description"]); ?></p>

            <p>Location: <?php echo htmlspecialchars($job["location"]); ?></p>

            <p>Status: <?php echo htmlspecialchars($job["status"]); ?></p>

            <a href="job.php?id=<?php echo $job["id"]; ?>">View Job</a>
        </div>

    <?php } ?>

</div>

</body>
</html>
