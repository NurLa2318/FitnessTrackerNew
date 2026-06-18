<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

include "../config/database.php";
include('../includes/header.php');

$message = "";
$user_id = $_SESSION['user_id'];

if(isset($_GET['delete'])){

    $meal_id = $_GET['delete'];

    $sql = "DELETE FROM meals
            WHERE meal_id='$meal_id'
            AND user_id='$user_id'";

    mysqli_query($conn,$sql);

    header("Location: meals.php");
    exit();
}

if(isset($_POST['save_meal'])){

    $food_name = $_POST['food_name'];
    $meal_type = $_POST['meal_type'];
    $quantity = $_POST['quantity'];
    $calories = $_POST['calories'];
    $protein = $_POST['protein'];
    $carbs = $_POST['carbs'];
    $fat = $_POST['fat'];

    $sql = "INSERT INTO meals
            (user_id,food_name,meal_type,quantity,calories,protein,carbs,fat,meal_date)
            VALUES
            ('$user_id','$food_name','$meal_type','$quantity',
             '$calories','$protein','$carbs','$fat',CURDATE())";

    if(mysqli_query($conn,$sql)){
        $message = "Meal added successfully!";
    }else{
        $message = mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Meal Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.page-header{
    background:linear-gradient(135deg,#4e73df,#1cc88a);
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
    background:#4e73df;
    color:white;
}

.btn-success{
    border-radius:10px;
}

</style>

</head>

<body>

<div class="container mt-4">

    <div class="page-header">

        <h2>🍽 Meal Management</h2>

        <p class="mb-0">
            Welcome, <?php echo $_SESSION['name']; ?>
        </p>

    </div>

    <?php if($message!=""){ ?>

        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>

    <?php } ?>

    <!-- ADD MEAL FORM -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h4 class="mb-3">➕ Add Meal Record</h4>

            <form method="POST">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Food Name</label>

                        <input
                            type="text"
                            name="food_name"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Meal Type</label>

                        <select
                            name="meal_type"
                            class="form-control"
                            required>

                            <option value="">Select Meal Type</option>
                            <option value="Breakfast">Breakfast</option>
                            <option value="Lunch">Lunch</option>
                            <option value="Dinner">Dinner</option>
                            <option value="Snack">Snack</option>

                        </select>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">Quantity</label>

                        <input
                            type="number"
                            name="quantity"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">Calories</label>

                        <input
                            type="number"
                            step="0.01"
                            name="calories"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-2 mb-3">

                        <label class="form-label">Protein (g)</label>

                        <input
                            type="number"
                            step="0.01"
                            name="protein"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-2 mb-3">

                        <label class="form-label">Carbs (g)</label>

                        <input
                            type="number"
                            step="0.01"
                            name="carbs"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-2 mb-3">

                        <label class="form-label">Fat (g)</label>

                        <input
                            type="number"
                            step="0.01"
                            name="fat"
                            class="form-control"
                            required>

                    </div>

                </div>

                <button
                    type="submit"
                    name="save_meal"
                    class="btn btn-success">

                    Save Meal

                </button>

            </form>

        </div>

    </div>

    <!-- RECORD TABLE -->

    <div class="card shadow-sm">

        <div class="card-body">

            <h4 class="mb-3">📋 Your Meal Records</h4>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Food</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Calories</th>
                            <th>Protein</th>
                            <th>Carbs</th>
                            <th>Fat</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    $sql = "SELECT * FROM meals
                            WHERE user_id='$user_id'
                            ORDER BY meal_id DESC";

                    $result = mysqli_query($conn,$sql);

                    while($row = mysqli_fetch_assoc($result)){
                    ?>

                    <tr>

                        <td><?php echo $row['meal_id']; ?></td>
                        <td><?php echo $row['food_name']; ?></td>
                        <td><?php echo $row['meal_type']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['calories']; ?></td>
                        <td><?php echo $row['protein']; ?></td>
                        <td><?php echo $row['carbs']; ?></td>
                        <td><?php echo $row['fat']; ?></td>

                        <td>

                            <a
                                href="edit_meal.php?id=<?php echo $row['meal_id']; ?>"
                                class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <a
                                href="meals.php?delete=<?php echo $row['meal_id']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this meal?')">

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