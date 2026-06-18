<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

include "../config/database.php";
include('../includes/header.php');

$user_id = $_SESSION['user_id'];
$intake_id = $_GET['id'];

$sql = "SELECT *
        FROM water_intake
        WHERE intake_id='$intake_id'
        AND user_id='$user_id'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update_water'])){

    $amount_ml = $_POST['amount_ml'];

    $update = "UPDATE water_intake
               SET amount_ml='$amount_ml'
               WHERE intake_id='$intake_id'";

    mysqli_query($conn,$update);

    header("Location: water_intake.php");
    exit();
}
?>

<div class="container mt-5">

<div class="card shadow-lg border-0">

<div class="card-header bg-primary text-white">
<h3 class="mb-0">💧 Edit Water Intake</h3>
</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label class="form-label">
Amount (ml)
</label>

<input type="number"
name="amount_ml"
class="form-control"
value="<?php echo $row['amount_ml']; ?>"
required>

</div>

<button type="submit"
name="update_water"
class="btn btn-primary">
Update Water Intake
</button>

<a href="water_intake.php"
class="btn btn-secondary">
Back
</a>

</form>

</div>
</div>
</div>