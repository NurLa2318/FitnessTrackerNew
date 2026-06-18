<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>

<?php include('../includes/header.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Jogging Tracker</title>

    <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        #map {
            height: 500px;
            width: 100%;
            margin-top: 10px;
        }

        .panel {
            text-align: center;
            padding: 15px;
            background: white;
        }

        button {
            padding: 10px 15px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .start {
            background: green;
            color: white;
        }

        .stop {
            background: red;
            color: white;
        }

        .card {
            font-size: 18px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<h2>🏃 Live Jogging Tracker</h2>

<div class="panel">

    <select id="activityType">
        <option value="Run">Run</option>
        <option value="Walk">Walk</option>
        <option value="Jog">Jog</option>
        <option value="Cycling">Cycling</option>
    </select>

    <br><br>

    <button class="start" onclick="startTracking()">
        Start Activity
    </button>

    <button class="stop" onclick="stopTracking()">
        Stop Activity
    </button>

    <div class="card">
        📏 Distance:
        <b><span id="distance">0</span></b> KM
    </div>

    <div class="card">
        🏃 Activity:
        <b><span id="activityLabel">Not Started</span></b>
    </div>

</div>

<div id="map"></div>

<script>
let map;
let path = [];
let polyline;
let watchId;
let totalDistance = 0;
let userMarker = null;
let startTime;

// INIT MAP
function initMap() {

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: { lat: 3.1390, lng: 101.6869 }
    });

    // LINE PATH
    polyline = new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: "#FF0000",
        strokeOpacity: 1.0,
        strokeWeight: 4
    });

    polyline.setMap(map);

    // INITIAL MARKER
    userMarker = new google.maps.Marker({
        position: { lat: 3.1390, lng: 101.6869 },
        map: map,
        title: "You are here 📍",
        icon: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
    });
}

// START TRACKING
function startTracking() {

startTime = new Date();

document.getElementById("activityLabel").innerText =
document.getElementById("activityType").value;

    if (navigator.geolocation) {

        watchId = navigator.geolocation.watchPosition(
            function(position) {

                let lat = position.coords.latitude;
                let lng = position.coords.longitude;

                let newPoint = new google.maps.LatLng(lat, lng);

                // ADD TO PATH
                path.push(newPoint);
                polyline.setPath(path);

                // MOVE MAP CENTER
                map.setCenter(newPoint);

                // MOVE MARKER
                userMarker.setPosition(newPoint);

                // CALCULATE DISTANCE
                if (path.length > 1) {

                    let lastPoint = path[path.length - 2];

                    let dist = google.maps.geometry.spherical.computeDistanceBetween(
                        lastPoint,
                        newPoint
                    );

                    totalDistance += dist;

                    document.getElementById("distance").innerText =
                        (totalDistance / 1000).toFixed(2);
                }
            },
            function(error) {
                alert("GPS Error: " + error.message);
            },
            {
                enableHighAccuracy: true,
                maximumAge: 0
            }
        );

    } else {
        alert("Geolocation not supported");
    }
}

// STOP TRACKING
function stopTracking() {

    navigator.geolocation.clearWatch(watchId);

    let distanceKM =
        (totalDistance / 1000).toFixed(2);

    let activity =
        document.getElementById("activityType").value;

    fetch("save_run.php", {

        method: "POST",

        headers: {
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body:
        "activity_type=" + activity +
        "&distance_km=" + distanceKM

    })

    .then(response => response.text())

    .then(data => {

        alert(
            "🏁 Activity Saved!\n\n" +
            "Activity: " + activity +
            "\nDistance: " + distanceKM + " KM"
        );

        location.reload();

    });

}
</script>

<!-- GOOGLE MAPS API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCM6OcVtGhPVPq5gpjpJXP_lcQVPBSoqzI&callback=initMap&libraries=geometry" async defer></script>

</body>
</html>