<?php
include('../db.php');

$id = $_POST['id'];
$current = $_POST['current_weight'];
$target = $_POST['target_weight'];
$duration = $_POST['duration'];

$sql = "UPDATE weight_goals 
SET current_weight='$current',
    target_weight='$target',
    duration='$duration'
WHERE goal_id=$id";

$conn->query($sql);

header("Location: weight_goals.php");
?>