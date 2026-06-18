<?php
include('../db.php');

$id = $_GET['id'];

$sql = "DELETE FROM weight_goals WHERE goal_id=$id";
$conn->query($sql);

header("Location: weight_goals.php");
?>