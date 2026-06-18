<?php

session_start();

include('../config/database.php');

$user_id = $_SESSION['user_id'];

$activity_type = $_POST['activity_type'];
$distance_km = $_POST['distance_km'];

$sql = "INSERT INTO run_history
(
user_id,
activity_type,
distance_km,
activity_date
)
VALUES
(
'$user_id',
'$activity_type',
'$distance_km',
CURDATE()
)";

mysqli_query($conn,$sql);

echo "success";

?>