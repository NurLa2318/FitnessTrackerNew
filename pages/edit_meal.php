<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

include "../config/database.php";
include('../includes/header.php');

$user_id = $_SESSION['user_id'];
$meal_id = $_GET['id'];

$sql = "SELECT * FROM meals
        WHERE meal_id='$meal_id'
        AND user_id='$user_id'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update_meal'])){

    $food_name = $_POST['food_name'];
    $meal_type = $_POST['meal_type'];
    $quantity = $_POST['quantity'];
    $calories = $_POST['calories'];
    $protein = $_POST['protein'];
    $carbs = $_POST['carbs'];
    $fat = $_POST['fat'];

    $update = "UPDATE meals SET
               food_name='$food_name',
               meal_type='$meal_type',
               quantity='$quantity',
               calories='$calories',
               protein='$protein',
               carbs='$carbs',
               fat='$fat'
               WHERE meal_id='$meal_id'";

    mysqli_query($conn,$update);

    header("Location: meals.php");
    exit();
}
?>

<div class="container mt-5">

<div class="card shadow-lg border-0">

<div class="card-header bg-success text-white">
<h3 class="mb-0">🍽 Edit Meal</h3>
</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">
<label class="form-label">Food Name</label>
<input type="text" name="food_name"
class="form-control"
value="<?php echo $row['food_name']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Meal Type</label>
<select name="meal_type" class="form-select">

<option value="Breakfast" <?php if($row['meal_type']=="Breakfast") echo "selected"; ?>>Breakfast</option>

<option value="Lunch" <?php if($row['meal_type']=="Lunch") echo "selected"; ?>>Lunch</option>

<option value="Dinner" <?php if($row['meal_type']=="Dinner") echo "selected"; ?>>Dinner</option>

<option value="Snack" <?php if($row['meal_type']=="Snack") echo "selected"; ?>>Snack</option>

</select>
</div>

<div class="mb-3">
<label class="form-label">Quantity</label>
<input type="number" name="quantity"
class="form-control"
value="<?php echo $row['quantity']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Calories</label>
<input type="number" step="0.01" name="calories"
class="form-control"
value="<?php echo $row['calories']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Protein (g)</label>
<input type="number" step="0.01" name="protein"
class="form-control"
value="<?php echo $row['protein']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Carbs (g)</label>
<input type="number" step="0.01" name="carbs"
class="form-control"
value="<?php echo $row['carbs']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Fat (g)</label>
<input type="number" step="0.01" name="fat"
class="form-control"
value="<?php echo $row['fat']; ?>" required>
</div>

<button type="submit"
name="update_meal"
class="btn btn-success">
Update Meal
</button>

<a href="meals.php" class="btn btn-secondary">
Back
</a>

</form>

</div>
</div>
</div>