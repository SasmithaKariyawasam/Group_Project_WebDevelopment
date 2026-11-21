
<?php
session_start();
include "db.php";

if (isset($_POST["login"])) {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user["password"])) {

            if ($user["role"] == "repairman" && $user["verified"] == 0) {
                $error = "Your account is waiting for admin verification";
            } else {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["role"] = $user["role"];

                if ($user["role"] == "homeowner") {
                    header("Location: homeowner_dashboard.php");
                    exit();
                }

                if ($user["role"] == "repairman") {
                    header("Location: repairman_dashboard.php");
                    exit();
                }

                if ($user["role"] == "admin") {
                    header("Location: admin_dashboard.php");
                    exit();
                }
            }

        } else {
            $error = "Incorrect password";
        }

    } else {
        $error = "Account not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>User Login</h2>

    <?php if (isset($error)) echo $error; ?>

    <form method="POST">

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">Login</button>

    </form>

    <p>New user? <a href="register.php">Register</a></p>
</div>

</body>
</html>
