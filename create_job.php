
<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "homeowner") {
    header("Location: login.php");
    exit();
}

if (isset($_POST["submit"])) {

    $title = $_POST["title"];
    $description = $_POST["description"];
    $location = $_POST["location"];
    $homeowner_id = $_SESSION["user_id"];

    $sql = "INSERT INTO jobs
            (homeowner_id, title, description, location)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt, "isss",
        $homeowner_id, $title, $description, $location
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Job</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>Post a Repair Job</h2>

    <form method="POST">

        <input type="text" name="title" placeholder="Job Title" required>

        <textarea name="description" placeholder="Describe the problem" required></textarea>

        <input type="text" name="location" placeholder="Your Location" required>

        <button type="submit" name="submit">Post Job</button>

    </form>

    <a href="dashboard.php">Back</a>

</div>

</body>
</html>
