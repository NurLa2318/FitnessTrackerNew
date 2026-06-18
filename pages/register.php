<?php
include "../config/database.php";
include('../includes/header.php');

$message = "";

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(name,email,password,age)
            VALUES('$name','$email','$hashedPassword','$age')";

    if(mysqli_query($conn,$sql)){
        $message = "Registration Successful!";
    }else{
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>

<body>

<div class="auth-page">

    <div class="login-card">

        <div class="logo-title">

            <h2>🏋️ Fitness Tracker</h2>

            <p>Create New Account</p>

        </div>

        <?php if($message!=""){ ?>

            <div class="alert alert-success">

                <?php echo $message; ?>

            </div>

        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Name</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>

                <input type="password"
                       name="password"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Age</label>

                <input type="number"
                       name="age"
                       class="form-control"
                       required>
            </div>

            <button type="submit"
                    name="register"
                    class="btn btn-primary w-100">
                Register
            </button>

        </form>

        <div class="text-center mt-3">

            Already have an account?

            <a href="login.php">
                Login Here
            </a>

        </div>

    </div>

</div>

</body>
</html>