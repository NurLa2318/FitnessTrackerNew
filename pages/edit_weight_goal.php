<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

include "../config/database.php";
include('../includes/header.php');

$user_id = $_SESSION['user_id'];
$goal_id = $_GET['id'];

$sql = "SELECT * FROM weight_goals
        WHERE goal_id='$goal_id'
        AND user_id='$user_id'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update_goal'])){

    $current_weight = $_POST['current_weight'];
    $target_weight = $_POST['target_weight'];
    $duration = $_POST['duration'];
    $status = $_POST['status'];

    $update = "UPDATE weight_goals
               SET current_weight='$current_weight',
                   target_weight='$target_weight',
                   duration='$duration',
                   status='$status'
               WHERE goal_id='$goal_id'";

    mysqli_query($conn,$update);

    header("Location: weight_goals.php");
    exit();
}
?>

<div class="container mt-5">

<div class="card shadow-lg border-0">

<div class="card-header bg-warning">
<h3 class="mb-0">⚖ Edit Weight Goal</h3>
</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">
<label class="form-label">Current Weight (kg)</label>
<input type="number"
step="0.01"
name="current_weight"
class="form-control"
value="<?php echo $row['current_weight']; ?>"
required>
</div>

<div class="mb-3">
<label class="form-label">Target Weight (kg)</label>
<input type="number"
step="0.01"
name="target_weight"
class="form-control"
value="<?php echo $row['target_weight']; ?>"
required>
</div>

<div class="mb-3">
<label class="form-label">Duration</label>

<select name="duration" class="form-select">

<option value="1 Month" <?php if($row['duration']=="1 Month") echo "selected"; ?>>
1 Month
</option>

<option value="3 Months" <?php if($row['duration']=="3 Months") echo "selected"; ?>>
3 Months
</option>

<option value="6 Months" <?php if($row['duration']=="6 Months") echo "selected"; ?>>
6 Months
</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">Status</label>

<select name="status" class="form-select">

<option value="Active"
<?php if($row['status']=="Active") echo "selected"; ?>>
Active
</option>

<option value="Completed"
<?php if($row['status']=="Completed") echo "selected"; ?>>
Completed
</option>

<option value="Cancelled"
<?php if($row['status']=="Cancelled") echo "selected"; ?>>
Cancelled
</option>

</select>

</div>

<button type="submit"
name="update_goal"
class="btn btn-warning">
Update Goal
</button>

<a href="weight_goals.php"
class="btn btn-secondary">
Back
</a>

</form>

</div>
</div>
</div>