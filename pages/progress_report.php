<?php
include('../includes/header.php');
include('../config/database.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =========================
   TOTAL CALORIES INTAKE
========================= */

$sql1 = "SELECT SUM(calories) AS total_calories
         FROM meals
         WHERE user_id='$user_id'";

$result1 = mysqli_query($conn,$sql1);
$row1 = mysqli_fetch_assoc($result1);

$total_calories = $row1['total_calories'] ?? 0;

/* =========================
   TOTAL EXERCISE
========================= */

$sql2 = "SELECT
         SUM(duration) AS total_minutes,
         SUM(calories_burned) AS total_burned
         FROM exercises
         WHERE user_id='$user_id'";

$result2 = mysqli_query($conn,$sql2);
$row2 = mysqli_fetch_assoc($result2);

$total_minutes = $row2['total_minutes'] ?? 0;
$total_burned = $row2['total_burned'] ?? 0;

/* =========================
   TOTAL WATER
========================= */

$sql3 = "SELECT SUM(amount_ml) AS total_water
         FROM water_intake
         WHERE user_id='$user_id'";

$result3 = mysqli_query($conn,$sql3);
$row3 = mysqli_fetch_assoc($result3);

$total_water = $row3['total_water'] ?? 0;

/* =========================
   LATEST WEIGHT GOAL
========================= */

$sql4 = "SELECT *
         FROM weight_goals
         WHERE user_id='$user_id'
         ORDER BY goal_id DESC
         LIMIT 1";

$result4 = mysqli_query($conn,$sql4);
$goal = mysqli_fetch_assoc($result4);

/* =========================
   NET CALORIES
========================= */

$net_calories = $total_calories - $total_burned;
?>

<!DOCTYPE html>
<html>
<head>

<title>Progress Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-4">

<h2>📊 Progress Report</h2>

<hr>

<div class="row">

<div class="col-md-3">
<div class="card bg-primary text-white mb-3">
<div class="card-body">

<h5>Total Calories Intake</h5>

<h3><?php echo $total_calories; ?></h3>

</div>
</div>
</div>

<div class="col-md-3">
<div class="card bg-success text-white mb-3">
<div class="card-body">

<h5>Calories Burned</h5>

<h3><?php echo $total_burned; ?></h3>

</div>
</div>
</div>

<div class="col-md-3">
<div class="card bg-warning text-dark mb-3">
<div class="card-body">

<h5>Total Exercise</h5>

<h3><?php echo $total_minutes; ?> min</h3>

</div>
</div>
</div>

<div class="col-md-3">
<div class="card bg-info text-white mb-3">
<div class="card-body">

<h5>Water Intake</h5>

<h3><?php echo $total_water; ?> ml</h3>

</div>
</div>
</div>

</div>

<!-- NET CALORIES -->

<div class="card mt-3">

<div class="card-body">

<h4>🔥 Net Calories</h4>

<h2><?php echo $net_calories; ?></h2>

</div>

</div>

<!-- WEIGHT GOAL -->

<?php if($goal){ ?>

<div class="card mt-3">

<div class="card-body">

<h4>🎯 Current Weight Goal</h4>

<p>
Current Weight:
<b><?php echo $goal['current_weight']; ?> kg</b>
</p>

<p>
Target Weight:
<b><?php echo $goal['target_weight']; ?> kg</b>
</p>

<p>
Duration:
<b><?php echo $goal['duration']; ?></b>
</p>

</div>

</div>

<?php } ?>

<!-- SIMPLE RECOMMENDATION -->

<div class="card mt-3">

<div class="card-body">

<h4>💡 Health Recommendation</h4>

<?php

if($net_calories > 500){

echo "
<div class='alert alert-warning'>
High calorie intake detected. Increase exercise activities.
</div>";

}
else{

echo "
<div class='alert alert-success'>
Good progress. Continue maintaining your healthy lifestyle.
</div>";

}

?>

</div>

</div>

</div>

</body>
</html>