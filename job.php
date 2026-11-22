
<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "homeowner") {
    header("Location: login.php");
    exit();
}

$job_id = $_GET["id"];
$homeowner_id = $_SESSION["user_id"];

$sql = "SELECT * FROM jobs WHERE id = ? AND homeowner_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $job_id, $homeowner_id);
mysqli_stmt_execute($stmt);

$job_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($job_result) == 0) {
    die("Job not found");
}

$job = mysqli_fetch_assoc($job_result);

$sql = "SELECT quotations.*, users.name
        FROM quotations
        JOIN users ON quotations.repairman_id = users.id
        WHERE quotations.job_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $job_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2><?php echo htmlspecialchars($job["title"]); ?></h2>

    <p><?php echo htmlspecialchars($job["description"]); ?></p>

    <p>Location: <?php echo htmlspecialchars($job["location"]); ?></p>

    <p>Status: <?php echo htmlspecialchars($job["status"]); ?></p>

    <h3>Repairman Quotations</h3>

    <?php while ($quote = mysqli_fetch_assoc($result)) { ?>

        <div class="job">

            <h4><?php echo htmlspecialchars($quote["name"]); ?></h4>

            <p>Price: Rs. <?php echo htmlspecialchars($quote["price"]); ?></p>

            <p><?php echo htmlspecialchars($quote["message"]); ?></p>

            <p>Status: <?php echo htmlspecialchars($quote["status"]); ?></p>

            <?php if ($job["status"] == "Open" && $quote["status"] == "Pending") { ?>

                <form action="accept_quote.php" method="POST">

                    <input type="hidden" name="quote_id" value="<?php echo $quote["id"]; ?>">

                    <button type="submit" name="accept">Accept Quotation</button>

                </form>

            <?php } ?>

        </div>

    <?php } ?>

    <a href="dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>
