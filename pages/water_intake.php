<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

include "../config/database.php";
include('../includes/header.php');

$user_id = $_SESSION['user_id'];
$message = "";

/* CREATE */

if(isset($_POST['save_water'])){

    $amount_ml = $_POST['amount_ml'];

    $sql = "INSERT INTO water_intake
            (user_id, amount_ml, intake_date)
            VALUES
            ('$user_id','$amount_ml',CURDATE())";

    if(mysqli_query($conn,$sql)){
        $message = "Water intake added successfully!";
    }
}

/* DELETE */

if(isset($_GET['delete'])){

    $intake_id = $_GET['delete'];

    $sql = "DELETE FROM water_intake
            WHERE intake_id='$intake_id'
            AND user_id='$user_id'";

    mysqli_query($conn,$sql);

    header("Location: water_intake.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Water Intake Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.page-header{
    background:linear-gradient(135deg,#36b9cc,#1cc88a);
    color:white;
    padding:25px;
    border-radius:15px;
    margin-bottom:20px;
}

.card{
    border:none;
    border-radius:15px;
}

.table th{
    background:#36b9cc;
    color:white;
}

.water-card{
    background:linear-gradient(135deg,#36b9cc,#4e73df);
    color:white;
    border-radius:15px;
    padding:20px;
    text-align:center;
    margin-bottom:20px;
}

.water-value{
    font-size:32px;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="container mt-4">

    <div class="page-header">

        <h2>💧 Water Intake Management</h2>

        <p class="mb-0">
            Welcome, <?php echo $_SESSION['name']; ?>
        </p>

    </div>

    <?php if($message!=""){ ?>

        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>

    <?php } ?>

    <?php

    $today_sql = "SELECT SUM(amount_ml) AS total_water
                  FROM water_intake
                  WHERE user_id='$user_id'
                  AND intake_date=CURDATE()";

    $today_result = mysqli_query($conn,$today_sql);

    $today = mysqli_fetch_assoc($today_result);

    $total_water = $today['total_water'] ?? 0;

    ?>

    <div class="water-card">

        <h4>Today's Water Intake</h4>

        <div class="water-value">
            <?php echo $total_water; ?> ml
        </div>

    </div>

    <!-- ADD WATER FORM -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h4 class="mb-3">➕ Add Water Intake</h4>

            <form method="POST">

                <label class="form-label">
                    Water Intake (ml)
                </label>

                <input
                    type="number"
                    name="amount_ml"
                    class="form-control mb-3"
                    required>

                <button
                    type="submit"
                    name="save_water"
                    class="btn btn-primary">

                    Add Water Intake

                </button>

            </form>

        </div>

    </div>

    <!-- RECORDS TABLE -->

    <div class="card shadow-sm">

        <div class="card-body">

            <h4 class="mb-3">
                📋 Your Water Intake Records
            </h4>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Amount (ml)</th>
                            <th>Date</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    $sql = "SELECT *
                            FROM water_intake
                            WHERE user_id='$user_id'
                            ORDER BY intake_id DESC";

                    $result = mysqli_query($conn,$sql);

                    while($row = mysqli_fetch_assoc($result)){
                    ?>

                    <tr>

                        <td><?php echo $row['intake_id']; ?></td>

                        <td>
                            <?php echo $row['amount_ml']; ?> ml
                        </td>

                        <td>
                            <?php echo $row['intake_date']; ?>
                        </td>

                        <td>

                            <a
                                href="edit_water.php?id=<?php echo $row['intake_id']; ?>"
                                class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <a
                                href="water_intake.php?delete=<?php echo $row['intake_id']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this record?')">

                                Delete

                            </a>

                        </td>

                    </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>