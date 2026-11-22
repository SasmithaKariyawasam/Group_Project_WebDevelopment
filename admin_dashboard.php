
<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <h2>FixMate Admin</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="../logout.php">Logout</a>
</div>

<div class="container">

    <h2>Manage Users</h2>

    <?php while ($user = mysqli_fetch_assoc($result)) { ?>

        <div class="job">

            <h3><?php echo htmlspecialchars($user["name"]); ?></h3>

            <p>Email: <?php echo htmlspecialchars($user["email"]); ?></p>

            <p>Role: <?php echo htmlspecialchars($user["role"]); ?></p>

            <p>
                Verification:
                <?php
                if ($user["verified"] == 1) {
                    echo "Verified";
                } else {
                    echo "Not Verified";
                }
                ?>
            </p>

            <?php if ($user["role"] == "repairman" &&
                      $user["verified"] == 0) { ?>

                <a href="verify.php?id=<?php echo $user["id"]; ?>">
                    Verify Repairman
                </a>

            <?php } ?>

        </div>

    <?php } ?>

</div>

</body>
</html>
