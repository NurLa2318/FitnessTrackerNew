<?php
session_start();
include "../config/database.php";
include('../includes/header.php');

$message = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "Invalid Password!";
        }

    } else {
        $message = "Email Not Found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<div class="auth-page">

    <div class="login-card">

        <div class="logo-title">

            <h2>🏋️ Fitness Tracker</h2>

            <p class="text-muted">
                Login to your account
            </p>

        </div>

        <?php if($message!=""){ ?>

            <div class="alert alert-danger">

                <?php echo $message; ?>

            </div>

        <?php } ?>

        <form method="POST">

            <div class="mb-3">

                <label>Email</label>

                <input type="email"
                    name="email"
                    class="form-control"
                    required>

            </div>

            <div class="mb-3">

                <label>Password</label>

                <input type="password"
                    name="password"
                    class="form-control"
                    required>

            </div>

            <button type="submit"
                    name="login"
                    class="btn btn-primary w-100">

                Login

            </button>

        </form>

        <div class="text-center mt-3">

            Don't have an account?

            <a href="register.php">

                Register Here

            </a>

        </div>

    </div>
</div>

</body>
</html>