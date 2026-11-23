
<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "repairman") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["job_id"])) {
    header("Location: repairman_dashboard.php");
    exit();
}

$job_id = $_GET["job_id"];
$repairman_id = $_SESSION["user_id"];

$sql = "SELECT * FROM jobs WHERE id = ? AND status = 'Open'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $job_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) != 1) {
    die("Job not found or no longer available.");
}

$job = mysqli_fetch_assoc($result);

if (isset($_POST["submit_quote"])) {

    $price = $_POST["price"];
    $message = $_POST["message"];

    if ($price <= 0) {
        $error = "Please enter a valid price.";
    } else {

        $check_sql = "SELECT id FROM quotations
                      WHERE job_id = ? AND repairman_id = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "ii", $job_id, $repairman_id);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "You have already submitted a quotation for this job.";
        } else {

            $sql = "INSERT INTO quotations
                    (job_id, repairman_id, price, message, status)
                    VALUES (?, ?, ?, ?, 'Pending')";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt, "iids",
                $job_id, $repairman_id, $price, $message
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: repairman_dashboard.php");
                exit();
            } else {
                $error = "Failed to submit quotation.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Quotation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>Submit Quotation</h2>

    <h3><?php echo htmlspecialchars($job["title"]); ?></h3>

    <p>
        <?php echo htmlspecialchars($job["description"]); ?>
    </p>

    <p>
        Location:
        <?php echo htmlspecialchars($job["location"]); ?>
    </p>

    <?php if (isset($error)) echo "<p>" . htmlspecialchars($error) . "</p>"; ?>

    <form method="POST">

        <input
            type="number"
            name="price"
            placeholder="Your Price"
            min="0.01"
            step="0.01"
            required
        >

        <textarea
            name="message"
            placeholder="Write your quotation details"
            required
        ></textarea>

        <button type="submit" name="submit_quote">
            Submit Quotation
        </button>

    </form>

    <p>
        <a href="repairman_dashboard.php">Back to Dashboard</a>
    </p>

</div>

</body>
</html>
