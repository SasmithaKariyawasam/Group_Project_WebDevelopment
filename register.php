
<?php
include "db.php";

if (isset($_POST["register"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($role == "homeowner" || $role == "repairman") {

        $sql = "INSERT INTO users (name, email, password, role)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt, "ssss",
            $name, $email, $hashedPassword, $role
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Email already exists";
        }

    } else {
        $error = "Invalid user role";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Create Account</h2>

    <?php if (isset($error)) echo $error; ?>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="homeowner">Homeowner</option>
            <option value="repairman">Repairman</option>
        </select>

        <button type="submit" name="register">Register</button>

    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
