
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

            session_regenerate_id(true);

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

            $error = "Invalid user role";

        } else {
            $error = "Incorrect password";
        }

    } else {
        $error = "Account not found";
    }
}
?>
