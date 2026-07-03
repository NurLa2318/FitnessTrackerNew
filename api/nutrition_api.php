<?php

include("../config/database.php");

header("Content-Type: application/json");

$food = $_GET['food'] ?? '';

if($food == ""){
    echo json_encode([
        "status"=>"error",
        "message"=>"Food name required"
    ]);
    exit();
}

$sql = "SELECT *
        FROM nutrition
        WHERE food_name LIKE '%$food%'
        LIMIT 1";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==0){

    echo json_encode([
        "status"=>"not_found"
    ]);

    exit();
}

$row = mysqli_fetch_assoc($result);

echo json_encode($row);

?>