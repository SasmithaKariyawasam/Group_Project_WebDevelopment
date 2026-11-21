
<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "homeowner") {
    header("Location: login.php");
    exit();
}

if (isset($_POST["accept"])) {

    $quote_id = $_POST["quote_id"];
    $homeowner_id = $_SESSION["user_id"];

    // Get quotation details
    $sql = "SELECT quotations.*, jobs.homeowner_id, jobs.status AS job_status
            FROM quotations
            JOIN jobs ON quotations.job_id = jobs.id
            WHERE quotations.id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $quote_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $quote = mysqli_fetch_assoc($result);

        if ($quote["homeowner_id"] == $homeowner_id &&
            $quote["job_status"] == "Open" &&
            $quote["status"] == "Pending") {

            $job_id = $quote["job_id"];
            $repairman_id = $quote["repairman_id"];
            $price = $quote["price"];

            $sql = "UPDATE quotations SET status = 'Accepted' WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $quote_id);
            mysqli_stmt_execute($stmt);

            // Reject other quotations
            $sql = "UPDATE quotations SET status = 'Rejected'
                    WHERE job_id = ? AND id != ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $job_id, $quote_id);
            mysqli_stmt_execute($stmt);

            // Create booking
            $sql = "INSERT INTO bookings
                    (job_id, homeowner_id, repairman_id, price)
                    VALUES (?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt, "iiid",
                $job_id, $homeowner_id, $repairman_id, $price
            );
            mysqli_stmt_execute($stmt);

            // Update job status
            $sql = "UPDATE jobs SET status = 'Booked' WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $job_id);
            mysqli_stmt_execute($stmt);

            header("Location: bookings.php");
            exit();

        } else {
            die("You cannot accept this quotation");
        }

    } else {
        die("Quotation not found");
    }
}
?>
