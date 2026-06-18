<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/fitness_tracker/includes/theme.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold"
           href="<?php echo isset($_SESSION['user_id']) ? '../pages/dashboard.php' : '../index.php'; ?>">
            🏋️ Fitness Tracker
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarContent"
                aria-controls="navbarContent"
                aria-expanded="false"
                aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            <ul class="navbar-nav me-auto">

                <?php if(isset($_SESSION['user_id'])): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="../pages/dashboard.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../pages/weight_goals.php">
                            Weight Goals
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../pages/meals.php">
                            Meals
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../pages/water_intake.php">
                            Water Intake
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../pages/exercises.php">
                            Exercise
                        </a>
                    </li>

                    <?php if(file_exists(__DIR__ . '/../pages/exercise_map.php')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/exercise_map.php">
                            Activity Location
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(file_exists(__DIR__ . '/../pages/progress_report.php')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/progress_report.php">
                            Progress Report
                        </a>
                    </li>
                    <?php endif; ?>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../pages/login.php">
                            Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../pages/register.php">
                            Register
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../pages/progress_report.php">
                            Progress Report
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

            <?php if(isset($_SESSION['user_id'])): ?>

                <span class="navbar-text text-white me-3">

                    👤 <?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?>

                </span>

                <a href="../pages/logout.php"
                   class="btn btn-danger">

                    Logout

                </a>

            <?php endif; ?>

        </div>

    </div>

</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>