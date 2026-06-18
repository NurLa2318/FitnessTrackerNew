<?php
session_start();
include('../db.php');

$user_id = $_SESSION['user_id'];

$current = $_POST['current_weight'];
$target = $_POST['target_weight'];
$duration = $_POST['duration'];

$sql = "INSERT INTO weight_goals (user_id,current_weight,target_weight,duration)
VALUES ('$user_id','$current','$target','$duration')";

$conn->query($sql);

header("Location: weight_goals.php");
?>