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

    $goal_id = $_GET['delete'];

    $sql = "DELETE FROM weight_goals
            WHERE goal_id='$goal_id'
            AND user_id='$user_id'";

    mysqli_query($conn,$sql);

    header("Location: weight_goals.php");
    exit();
}

if(isset($_POST['save_goal'])){

    $current_weight = $_POST['current_weight'];
    $target_weight = $_POST['target_weight'];
    $duration = $_POST['duration'];

    $sql = "INSERT INTO weight_goals
            (user_id,current_weight,target_weight,duration)
            VALUES
            ('$user_id','$current_weight','$target_weight','$duration')";

    if(mysqli_query($conn,$sql)){
        $message = "Weight goal saved successfully!";
    }else{
        $message = mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Weight Goals Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.page-header{
    background:linear-gradient(135deg,#4e73df,#36b9cc);
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

.goal-card{
    background:linear-gradient(135deg,#4e73df,#1cc88a);
    color:white;
    border-radius:15px;
    padding:20px;
    text-align:center;
    margin-bottom:20px;
}

.goal-number{
    font-size:32px;
    font-weight:bold;
}

.badge-status{
    font-size:14px;
    padding:8px 12px;
}

</style>

</head>

<body>

<div class="container mt-4">

    <div class="page-header">

        <h2>🎯 Weight Goals Management</h2>

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

    $goal_count_sql = "SELECT COUNT(*) as total_goals
                       FROM weight_goals
                       WHERE user_id='$user_id'";

    $goal_count_result = mysqli_query($conn,$goal_count_sql);
    $goal_count = mysqli_fetch_assoc($goal_count_result);

    ?>

    <div class="goal-card">

        <h4>Total Goals Created</h4>

        <div class="goal-number">
            <?php echo $goal_count['total_goals']; ?>
        </div>

    </div>

    <!-- FORM -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h4 class="mb-3">➕ Create New Goal</h4>

            <form method="POST">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Current Weight (kg)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="current_weight"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Target Weight (kg)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="target_weight"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Duration
                        </label>

                        <select
                            name="duration"
                            class="form-control"
                            required>

                            <option value="">Select Duration</option>
                            <option value="1 Month">1 Month</option>
                            <option value="3 Months">3 Months</option>
                            <option value="6 Months">6 Months</option>

                        </select>

                    </div>

                </div>

                <button
                    type="submit"
                    name="save_goal"
                    class="btn btn-success">

                    Save Goal

                </button>

            </form>

        </div>

    </div>

    <!-- TABLE -->

    <div class="card shadow-sm">

        <div class="card-body">

            <h4 class="mb-3">
                📋 Your Weight Goals
            </h4>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                    <tr>

                        <th>ID</th>
                        <th>Current Weight</th>
                        <th>Target Weight</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php

                    $sql = "SELECT * FROM weight_goals
                            WHERE user_id='$user_id'
                            ORDER BY goal_id DESC";

                    $result = mysqli_query($conn,$sql);

                    while($row = mysqli_fetch_assoc($result)){

                    ?>

                    <tr>

                        <td><?php echo $row['goal_id']; ?></td>

                        <td>
                            <?php echo $row['current_weight']; ?> kg
                        </td>

                        <td>
                            <?php echo $row['target_weight']; ?> kg
                        </td>

                        <td>
                            <?php echo $row['duration']; ?>
                        </td>

                        <td>

                            <?php

                            if($row['status']=="Completed"){

                                echo "<span class='badge bg-success badge-status'>Completed</span>";

                            }elseif($row['status']=="In Progress"){

                                echo "<span class='badge bg-warning text-dark badge-status'>In Progress</span>";

                            }else{

                                echo "<span class='badge bg-secondary badge-status'>".$row['status']."</span>";

                            }

                            ?>

                        </td>

                        <td>

                            <a
                            href="edit_weight_goal.php?id=<?php echo $row['goal_id']; ?>"
                            class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <a
                            href="weight_goals.php?delete=<?php echo $row['goal_id']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete this weight goal?')">

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