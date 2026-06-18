<?php
session_start();

include('../config/database.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$exercise_id = $_GET['id'];

$sql = "SELECT *
        FROM exercises
        WHERE exercise_id='$exercise_id'
        AND user_id='$user_id'";

$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);

if(!$row){
    die("Exercise record not found.");
}

if(isset($_POST['update_exercise'])){

    $exercise_type = $_POST['exercise_type'];
    $duration = $_POST['duration'];
    $intensity = $_POST['intensity'];
    $calories_burned = $_POST['calories_burned'];

    $update = "UPDATE exercises
               SET
               exercise_type='$exercise_type',
               duration='$duration',
               intensity='$intensity',
               calories_burned='$calories_burned'
               WHERE exercise_id='$exercise_id'
               AND user_id='$user_id'";

    if(mysqli_query($conn,$update)){
        header("Location: exercises.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Exercise</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-body">

<h2 class="mb-4">✏️ Edit Exercise</h2>

<form method="POST">

<div class="mb-3">

<label class="form-label">
Exercise Type
</label>

<input
type="text"
name="exercise_type"
class="form-control"
value="<?php echo $row['exercise_type']; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">
Duration (Minutes)
</label>

<input
type="number"
name="duration"
class="form-control"
value="<?php echo $row['duration']; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">
Intensity
</label>

<select
name="intensity"
class="form-control">

<option
value="Low"
<?php if($row['intensity']=="Low") echo "selected"; ?>>
Low
</option>

<option
value="Medium"
<?php if($row['intensity']=="Medium") echo "selected"; ?>>
Medium
</option>

<option
value="High"
<?php if($row['intensity']=="High") echo "selected"; ?>>
High
</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">
Calories Burned
</label>

<input
type="number"
step="0.01"
name="calories_burned"
class="form-control"
value="<?php echo $row['calories_burned']; ?>"
required>

</div>

<button
type="submit"
name="update_exercise"
class="btn btn-success">

Update Exercise

</button>

<a href="exercises.php"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</body>
</html>