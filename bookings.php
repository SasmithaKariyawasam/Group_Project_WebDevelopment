
<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "homeowner") {
    header("Location: login.php");
    exit();
}

$id = $_SESSION["user_id"];

$sql = "SELECT bookings.*, jobs.title, users.name
        FROM bookings
        JOIN jobs ON bookings.job_id = jobs.id
        JOIN users ON bookings.repairman_id = users.id
        WHERE bookings.homeowner_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>My Bookings</h2>

    <?php while ($booking = mysqli_fetch_assoc($result)) { ?>

        <div class="job">

            <h3><?php echo htmlspecialchars($booking["title"]); ?></h3>

            <p>Repairman: <?php echo htmlspecialchars($booking["name"]); ?></p>

            <p>Price: Rs. <?php echo htmlspecialchars($booking["price"]); ?></p>

            <p>Job Status: <?php echo htmlspecialchars($booking["status"]); ?></p>

            <p>Payment: <?php echo htmlspecialchars($booking["payment_status"]); ?></p>

            <?php if ($booking["status"] == "Completed" &&
                      $booking["payment_status"] == "Unpaid") { ?>

                <a class="button" href="pay.php?id=<?php echo $booking["id"]; ?>">
                    Make Payment
                </a>

            <?php } ?>

        </div>

    <?php } ?>

    <a href="homeowner_dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>
