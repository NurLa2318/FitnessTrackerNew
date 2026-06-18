<?php
include('../config/database.php');
include('../includes/header.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =========================
   WEIGHT GOAL
========================= */

$goal_sql = "SELECT *
             FROM weight_goals
             WHERE user_id='$user_id'
             ORDER BY goal_id DESC
             LIMIT 1";

$goal_result = mysqli_query($conn,$goal_sql);
$goal = mysqli_fetch_assoc($goal_result);

/* =========================
   GET USER INFO
========================= */
$user_sql = "SELECT * FROM users WHERE user_id='$user_id'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

/* =========================
   WEATHER API (SAFE)
========================= */
$weather_json = @file_get_contents("http://localhost/fitness_tracker/pages/weather_api.php");

$weather = null;
if($weather_json !== false){
    $weather = json_decode($weather_json, true);
}

$temp = $weather['temperature'] ?? null;
$condition = $weather['weather'] ?? 'N/A';
$humidity = $weather['humidity'] ?? 'N/A';
$outdoorRecommended = $weather['outdoorRecommended'] ?? false;

/* =========================
   WATER INTAKE TODAY
========================= */
$water_sql = "SELECT SUM(amount_ml) as total_water 
              FROM water_intake 
              WHERE user_id='$user_id' AND DATE(intake_date)=CURDATE()";
$water_result = mysqli_query($conn, $water_sql);
$water = mysqli_fetch_assoc($water_result);

/* =========================
   MEALS TODAY
========================= */
$meal_sql = "SELECT SUM(calories) as total_calories 
             FROM meals 
             WHERE user_id='$user_id' AND meal_date=CURDATE()";
$meal_result = mysqli_query($conn, $meal_sql);
$meal = mysqli_fetch_assoc($meal_result);

/* =========================
   EXERCISE TODAY
========================= */

$ex_sql = "SELECT
           SUM(duration) as total_exercise,
           SUM(calories_burned) as total_burned
           FROM exercises
           WHERE user_id='$user_id'
           AND exercise_date=CURDATE()";

$ex_result = mysqli_query($conn, $ex_sql);
$exercise = mysqli_fetch_assoc($ex_result);

/* =========================
   DEFAULT VALUES
========================= */
$total_water = $water['total_water'] ?? 0;
$total_calories = $meal['total_calories'] ?? 0;
$total_exercise = $exercise['total_exercise'] ?? 0;
$total_burned = $exercise['total_burned'] ?? 0;

/* =========================
   CALCULATION
========================= */
$calories_burned = $total_burned;
$net_calories = $total_calories - $calories_burned;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .card-box {
            border-radius: 15px;
            padding: 20px;
            color: white;
        }

        .blue { background: #4e73df; }
        .green { background: #1cc88a; }
        .orange { background: #f6c23e; }
        .red { background: #e74a3b; }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .value {
            font-size: 28px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container mt-4">

    <h2>👋 Welcome, <?php echo $user['name']; ?></h2>
    <p>Here is your daily fitness summary</p>

    <!-- WEATHER -->
    <div class="card mt-3 p-3">
        <h5>🌤 Live Weather (External API)</h5>

        <?php if($temp !== null) { ?>

            <p><strong>Temperature:</strong> <?php echo $temp; ?>°C</p>

            <p><strong>Condition:</strong> <?php echo $condition; ?></p>

            <p><strong>Humidity:</strong> <?php echo $humidity; ?>%</p>

            <hr>

            <?php

            if($outdoorRecommended){

                echo "
                <div class='alert alert-success'>
                ✅ Outdoor Exercise Recommended
                </div>";

            }else{

                echo "
                <div class='alert alert-warning'>
                ⚠ Indoor Exercise Recommended
                </div>";

            }

            ?>

        <?php } else { ?>

            <div class='alert alert-secondary'>
                ⚠ Weather data not available.
            </div>

        <?php } ?>
    </div>

    <!-- STATS CARDS -->
    <div class="row mt-4">

        <div class="col-md-3">
            <div class="card-box blue"
                onclick="window.location='water_intake.php'">

                <div class="title">💧 Water Intake</div>
                <div class="value"><?php echo $total_water; ?> ml</div>

            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box orange"
                onclick="window.location='meals.php'">

                <div class="title">🍗 Calories Intake</div>
                <div class="value"><?php echo $total_calories; ?></div>

            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box green"
                onclick="window.location='exercises.php'">

                <div class="title">🏃 Exercise Time</div>
                <div class="value"><?php echo $total_exercise; ?> min</div>

            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box red">
                <div class="title">🔥 Net Calories</div>
                <div class="value"><?php echo $net_calories; ?></div>

            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-dark"
                onclick="window.location='exercises.php'">

                <div class="title">🔥 Calories Burned</div>
                <div class="value"><?php echo $calories_burned; ?></div>
            </div>
        </div>

    </div>

    <?php

    $goal_result = mysqli_query($conn,$goal_sql);
    $goal = mysqli_fetch_assoc($goal_result);

    if($goal){
    ?>

    <div class="card mt-3 p-3">

    <h5>🎯 Current Weight Goal</h5>

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

    <div class="card mt-4 p-3">

<h5>📊 Progress Report</h5>

<table class="table table-bordered">

<tr>
    <th>Category</th>
    <th>Value</th>
</tr>

<tr>
    <td>Water Intake</td>
    <td><?php echo $total_water; ?> ml</td>
</tr>

<tr>
    <td>Calories Intake</td>
    <td><?php echo $total_calories; ?></td>
</tr>

<tr>
    <td>Exercise Time</td>
    <td><?php echo $total_exercise; ?> min</td>
</tr>

<tr>
    <td>Calories Burned</td>
    <td><?php echo $calories_burned; ?></td>
</tr>

<tr>
    <td>Net Calories</td>
    <td><?php echo $net_calories; ?></td>
</tr>

</table>

</div>

<h5 class="mt-4">📈 Fitness Status</h5>

<?php

if($net_calories > 1000){

echo "
<div class='alert alert-danger'>
High calorie surplus detected.
</div>";

}
elseif($net_calories > 500){

echo "
<div class='alert alert-warning'>
Moderate calorie surplus detected.
</div>";

}
else{

echo "
<div class='alert alert-success'>
Healthy calorie balance achieved.
</div>";

}

?>

    <?php } ?>

    <!-- SUMMARY -->
    <div class="card mt-4 p-3">
        <h5>📊 Today Recommendation</h5>

        <?php
        if($net_calories > 500){
            echo "<div class='alert alert-warning'>⚠ High calorie intake today. Increase exercise.</div>";
        } else {
            echo "<div class='alert alert-success'>✅ Good balance today. Keep it up!</div>";
        }
        ?>
    </div>

</div>

</body>
</html>