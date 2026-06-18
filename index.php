<?php
session_start();

if(isset($_SESSION['user_id'])){
    header("Location: pages/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Fitness Tracker</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    margin:0;
    padding:0;
    background:#f4f8fb;
    font-family:'Segoe UI',sans-serif;
}

/* HERO */

.hero{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;

    background-image:url('images.jpg');
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;

    color:white;
}

.hero-content{
    text-align:center;
    max-width:800px;
}

.hero h1{
    font-size:60px;
    font-weight:bold;
    margin-bottom:20px;
}

.hero p{
    font-size:20px;
    margin-bottom:30px;
}

/* BUTTON */

.btn-custom{
    padding:12px 30px;
    font-size:18px;
    border-radius:30px;
    margin:5px;
}

/* FEATURES */

.features{
    padding:80px 0;
}

.feature-card{
    background:white;
    border:none;
    border-radius:15px;
    padding:30px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    transition:0.3s;
}

.feature-card:hover{
    transform:translateY(-5px);
}

.feature-icon{
    font-size:50px;
    margin-bottom:15px;
}

/* FOOTER */

.footer{
    background:#212529;
    color:white;
    text-align:center;
    padding:20px;
}

</style>

</head>
<body>

<!-- HERO SECTION -->

<section class="hero">

<div class="hero-content">

<h1>🏋️ Fitness Tracker</h1>

<p>
Track your fitness journey, monitor meals,
water intake, exercise activities and weight goals
all in one place.
</p>

<a href="pages/login.php"
class="btn btn-light btn-custom">

Login

</a>

<a href="pages/register.php"
class="btn btn-success btn-custom">

Register

</a>

</div>

</section>

<!-- FEATURES -->

<section class="features">

<div class="container">

<div class="text-center mb-5">

<h2>Why Use Fitness Tracker?</h2>

<p>
Manage your health goals efficiently.
</p>

</div>

<div class="row g-4">

<div class="col-md-3">

<div class="feature-card">

<div class="feature-icon">
⚖️
</div>

<h4>Weight Goals</h4>

<p>
Set and track your target weight.
</p>

</div>

</div>

<div class="col-md-3">

<div class="feature-card">

<div class="feature-icon">
🍎
</div>

<h4>Meal Tracking</h4>

<p>
Record calories and nutrition intake.
</p>

</div>

</div>

<div class="col-md-3">

<div class="feature-card">

<div class="feature-icon">
💧
</div>

<h4>Water Intake</h4>

<p>
Monitor daily hydration levels.
</p>

</div>

</div>

<div class="col-md-3">

<div class="feature-card">

<div class="feature-icon">
🏃
</div>

<h4>Exercise Log</h4>

<p>
Track workouts and calories burned.
</p>

</div>

</div>

</div>

</div>

</section>

<!-- API SECTION -->

<section class="container mb-5">

<div class="card shadow border-0">

<div class="card-body text-center">

<h3>🌤 Weather API Integration</h3>

<p>
This system integrates an external Weather API
to provide weather-based exercise recommendations.
</p>

</div>

</div>

</section>

<!-- FOOTER -->

<footer class="footer">

<p class="mb-0">

Fitness Tracker System © <?php echo date("Y"); ?>

</p>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>