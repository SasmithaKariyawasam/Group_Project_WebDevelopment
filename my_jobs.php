<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "repairman") {
    header("Location: login.php");
    exit();
}

$id = $_SESSION["user_id"];

$sql = "SELECT bookings.*, jobs.title, jobs.location
        FROM bookings
        JOIN jobs ON bookings.job_id = jobs.id
        WHERE bookings.repairman_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Jobs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>My Assigned Jobs</h2>

    <?php while ($job = mysqli_fetch_assoc($result)) { ?>

        <div class="job">

            <h3><?php echo htmlspecialchars($job["title"]); ?></h3>

            <p>Location: <?php echo htmlspecialchars($job["location"]); ?></p>

            <p>Price: Rs. <?php echo htmlspecialchars($job["price"]); ?></p>

            <p>Status: <?php echo htmlspecialchars($job["status"]); ?></p>

            <form action="update_status.php" method="POST">

                <input type="hidden" name="booking_id"
                       value="<?php echo $job["id"]; ?>">

                <select name="status">
                    <option value="Booked">Booked</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>

                <button type="submit" name="update">Update Status</button>

            </form>

        </div>

    <?php } ?>

    <a href="dashboard.php">Back to Dashboard</a>

</div>
_
</body>
</html>
