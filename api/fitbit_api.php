<?php

include("../config/database.php");

session_start();

header("Content-Type: application/json");

$user_id = $_SESSION['user_id'];

$sql = "SELECT

SUM(duration) total_minutes,

SUM(calories_burned) total_calories

FROM exercises

WHERE user_id='$user_id'

AND exercise_date=CURDATE()";

$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);

echo json_encode([

"steps"=>rand(3500,9000),

"heart_rate"=>rand(65,110),

"distance"=>round(rand(10,80)/10,2),

"exercise_minutes"=>$row['total_minutes'] ?? 0,

"calories_burned"=>$row['total_calories'] ?? 0

]);

?>