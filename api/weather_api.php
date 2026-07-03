<?php

$apiKey = "f41694b73a23f1ab0a5b4872e91023ec";
$city = $_GET['city'] ?? 'Alor Setar';

$url = "https://api.openweathermap.org/data/2.5/weather?q="
    . urlencode($city)
    . "&appid=" . $apiKey
    . "&units=metric";

$response = file_get_contents($url);

$data = json_decode($response, true);

if(!isset($data['main'])){

    echo json_encode([
        "status" => "error",
        "message" => "Weather data not found"
    ]);

    exit();
}

$temp = $data['main']['temp'];
$weather = $data['weather'][0]['main'];
$humidity = $data['main']['humidity'];

$outdoorRecommended = true;

if(
    $weather == "Rain" ||
    $weather == "Thunderstorm"
){
    $outdoorRecommended = false;
}

echo json_encode([

    "status" => "success",

    "city" => $city,

    "temperature" => $temp,

    "weather" => $weather,

    "humidity" => $humidity,

    "outdoorRecommended" => $outdoorRecommended

]);

