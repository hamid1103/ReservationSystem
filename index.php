<?php
session_start();
//Setup MySQL Connection
$host = 'localhost';
$user = 'root';
$db = '';
$pass = '';
$port = "3306";
$charset = 'utf8mb4';

$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
try {
    $pdo = new \PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Science Planner</title>
    <link rel="stylesheet" href="css/mystyles.css">
</head>
<body>

<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">

        <?php
        if (isset($_SESSION['loggedin'])) {
            if ($_SESSION['name'] == 'admin') {?>
            <!--User icon-->
            <a class="navbar-item" href="/admin.php">
                <p class="title">
                    Admin
                </p>
            </a>
        <?php } else{ ?>
                <!--User icon-->
                <a class="navbar-item">
                    <p class="title">
                        <?= $_SESSION['name']?>
                    </p>
                </a>
        <?php } } ?>



        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item">
                Home
            </a>

            <a class="navbar-item">
                Documentation
            </a>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    More
                </a>

                <div class="navbar-dropdown">
                    <a class="navbar-item">
                        About
                    </a>
                    <a class="navbar-item">
                        Jobs
                    </a>
                    <a class="navbar-item">
                        Contact
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item">
                        Report an issue
                    </a>
                </div>
            </div>
        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <a class="button is-primary">
                        <strong>Sign up</strong>
                    </a>
                    <a class="button is-light">
                        Log in
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<section class="is-flex is-flex-direction-row is-align-items-center is-justify-content-center">
    <!--Section left of form-->
    <div class="container p-3 mt-3">
    </div>

    <!--Form in the middle-->
<div class="container p-3 mt-3">
    <form action="confirmation.php" method="post">
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="number" class="form-label">Mobile Number</label>
            <input type="number" class="form-control" id="number" name="number">
        </div>
        <div class="mb-3">
            <label for="Subject" class="form-label">Subject</label>
            <input type="text" class="form-control" id="Subject" name="subject">
        </div>
        <div class="m-3">
            <label for="InputDate" class="form-label">Datum</label>
            <input type="date" id="InputDate" class="form-control" name="date">
        </div>
        <div class="m-3">
            <label for="InputTime" class="form-label">Tijd</label>
            <input type="time" id="InputTime" class="form-control" name="time">
        </div>

        <input type="submit" name="submit" id="submit">
    </form>
</div>

    <!--section right of form-->
    <div class="container p-3 mt-3">
    </div>

</section>


</body>
</html>
