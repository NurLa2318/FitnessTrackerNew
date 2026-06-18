<?php

include('../config/database.php');
include('../includes/header.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$message = "";

/* DELETE */

if(isset($_GET['delete'])){

    $exercise_id = $_GET['delete'];

    $sql = "DELETE FROM exercises
            WHERE exercise_id='$exercise_id'
            AND user_id='$user_id'";

    mysqli_query($conn,$sql);

    header("Location: exercises.php");
    exit();
}

/* WEATHER API */

$weather_json = @file_get_contents("http://localhost/fitness_tracker/pages/weather_api.php");

$weather = json_decode($weather_json, true);

if(!$weather){
    $weather = [];
}

/* DEFAULT SAFE VALUE */
$currentTemp = $weather['temperature'] ?? null;
$currentWeather = $weather['weather'] ?? 'Unknown';
$currentHumidity = $weather['humidity'] ?? 'N/A';
$outdoorRecommended = $weather['outdoorRecommended'] ?? false;

/* CREATE */

if(isset($_POST['save_exercise'])){

    $exercise_type = $_POST['exercise_type'];
    $duration = $_POST['duration'];
    $intensity = $_POST['intensity'];
    $calories_burned = $_POST['calories_burned'];

    $sql = "INSERT INTO exercises
            (
            user_id,
            exercise_type,
            duration,
            intensity,
            calories_burned,
            weather_condition,
            temperature,
            exercise_date
            )
            VALUES
            (
            '$user_id',
            '$exercise_type',
            '$duration',
            '$intensity',
            '$calories_burned',
            '$currentWeather',
            '$currentTemp',
            CURDATE()
            )";

    if(mysqli_query($conn,$sql)){
        $message = "Exercise added successfully!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Exercise Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-4">

<h2 class="mb-4">🏃 Exercise Management</h2>

<?php if($message!=""){ ?>

<div class="alert alert-success">
    <?php echo $message; ?>
</div>

<?php } ?>

<!-- WEATHER CARD -->

<div class="card shadow-sm mb-4">

<div class="card-body">

<h4>🌤 Current Weather</h4>

<p>
Temperature :
<b><?php echo $currentTemp !== null ? $currentTemp : 'N/A'; ?> °C</b>
</p>

<p>
Condition :
<b><?php echo $currentWeather; ?></b>
</p>

<p>
Humidity :
<b><?php echo $currentHumidity; ?>%</b>
</p>

<hr>

<?php

if(!$outdoorRecommended){

echo "
<div class='alert alert-danger'>

<h5>🌧 Exercise Recommendation</h5>

Rainy weather detected.

<hr>

<ul>
<li>Indoor Workout</li>
<li>Yoga</li>
<li>Treadmill Running</li>
<li>Stretching Exercise</li>
</ul>

</div>";

}
elseif($currentTemp > 32){

echo "
<div class='alert alert-warning'>

<h5>🔥 Exercise Recommendation</h5>

Hot weather detected.

<hr>

<ul>
<li>Light Walking</li>
<li>Indoor Gym Training</li>
<li>Stretching Exercise</li>
<li>Drink More Water</li>
</ul>

</div>";

}
else{

echo "
<div class='alert alert-success'>

<h5>☀ Exercise Recommendation</h5>

Good weather detected.

<hr>

<ul>
<li>Jogging</li>
<li>Running</li>
<li>Cycling</li>
<li>Outdoor Walking</li>
</ul>

</div>";

}

?>

</div>

</div>

<!-- FORM -->

<div class="card shadow-sm mb-4">

<div class="card-body">

<h4>Add Exercise</h4>

<form method="POST">

<input type="text"
name="exercise_type"
class="form-control mb-3"
placeholder="Exercise Type"
required>

<input type="number"
name="duration"
class="form-control mb-3"
placeholder="Duration (Minutes)"
required>

<select
name="intensity"
class="form-control mb-3">

<option value="Low">Low</option>
<option value="Medium">Medium</option>
<option value="High">High</option>

</select>

<input type="number"
step="0.01"
name="calories_burned"
class="form-control mb-3"
placeholder="Calories Burned"
required>

<button
type="submit"
name="save_exercise"
class="btn btn-primary">

Save Exercise

</button>

</form>

</div>

</div>

<!-- TABLE -->

<div class="card shadow-sm">

<div class="card-body">

<h4>Exercise Records</h4>

<table class="table table-bordered">

<tr>

<th>ID</th>
<th>Exercise</th>
<th>Duration</th>
<th>Intensity</th>
<th>Calories</th>
<th>Weather</th>
<th>Temperature</th>
<th>Date</th>
<th>Action</th>

</tr>

<?php

$sql = "SELECT *
        FROM exercises
        WHERE user_id='$user_id'
        ORDER BY exercise_id DESC";

$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($result)){

?>

<tr>

<td><?php echo $row['exercise_id']; ?></td>

<td><?php echo $row['exercise_type']; ?></td>

<td><?php echo $row['duration']; ?></td>

<td><?php echo $row['intensity']; ?></td>

<td><?php echo $row['calories_burned']; ?></td>

<td><?php echo $row['weather_condition']; ?></td>

<td><?php echo $row['temperature']; ?>°C</td>

<td><?php echo $row['exercise_date']; ?></td>

<td>

<a href="edit_exercise.php?id=<?php echo $row['exercise_id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a href="exercises.php?delete=<?php echo $row['exercise_id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this exercise?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

</body>
</html>