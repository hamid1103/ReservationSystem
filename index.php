<?php
$loggedin = false;

//start session (with this we can use login stuff
session_start();
//Setup MySQL Connection
$host = 'localhost';
$user = 'root';
$db = 'reservatiesysteem';
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
    <link rel="stylesheet" href="css/customs.css">
</head>
<body>
<nav class="navbar color_orange" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">

        <?php
        if (isset($_SESSION['loggedin'])) {
            $loggedin = true;
            if ($_SESSION['name'] == 'admin') {?>
                <!--User icon-->
                <a class="navbar-item" href="admin.php">
                    <p class="title">
                        <i icon-name="aperture"></i>
                        Admin
                    </p>
                </a>
            <?php } else{ ?>
                <!--calendar icon-->
                <a class="navbar-item">
                    <p class="title">
                        <i icon-name="calendar-days"></i>
                        <?= htmlentities($_SESSION['fullname'])?>
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
            <a class="navbar-item" href="index.php">
                Home
            </a>

            <a class="navbar-item" href="myreservations.php">
                Mijn Reserveringen
            </a>

            <a class="navbar-item" href="contact.php">
                Contact
            </a>

        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <a class="button is-primary" href="logout.php">
                        <i icon-name="log-out"></i>Logout
                    </a>
                    <a class="button is-light" href="login.php">
                        <i icon-name="log-in"></i>Log in
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
        <?php if($loggedin == false) {?>
        <div class="mb-3 formrule">
           <label for="fullname" class="form-label">Volledige naam<i icon-name="contact"></i></label>
           <input type="text" class="form-control" id="fullname" name="fullname">
        </div>
        <div class="mb-3 formrule">
            <label for="email" class="form-label">Email address<i icon-name="mails"></i></label>
            <input type="email" class="form-control" id="email" name="email">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3 formrule">
            <label for="password" class="form-label">Wachtwoord<i icon-name="key"></i></label>
            <input type="password" class="form-control" id="password" name="password">
            <div id="passwordHelp" class="form-text">This is the password you want to use for changing or cancelling your reservations.</div>
        </div>
        <?php } ?>
        <div class="mb-3 formrule">
            <label for="number" class="form-label">Telefoon Nummer<i icon-name="phone"></i></label>
            <input type="number" class="form-control" id="number" name="number">
        </div>
        <div class="mb-3 formrule">
            <label for="Subject" class="form-label">Onderwerp<i icon-name="text-cursor"></i></label>
            <input type="text" class="form-control" id="Subject" name="subject">
        </div>
        <div class="mb-3 formrule">
            <label for="InputDate" class="form-label">Datum<i icon-name="calendar"></i></label>
            <input type="date" id="InputDate" class="form-control" onkeyup="showtime(this.value)" name="date">
        </div>
        <div class="mb-3 formrule">
            <label for="InputTime" class="form-label">Tijd<i icon-name="timer"></i></label>
            <input type="time" id="InputTime" class="form-control" name="time">
        </div>
        <!--<div class="mb-3 formrule">
            <div>
            <input ontoggle="" type="checkbox" class="custcheckbox" id="switch" name="SetDuration"/><label class="custcheckboxlabel" for="switch">Wilt u zelf bepalen hoelang de afspraak duurt? (standaard is 1 uur)</label>
            </div>
        </div>
        <div class="mb-3 formrule" id="hoelang">
            <select name="duration">
                <option value="0" disabled>Kies duratie:</option>
                <option value="1">30 minuten</option>
                <option value="2">1 uur</option>
                <option value="3">1:30 uur</option>
                <option value="4">2 uur</option>
                <option value="5">langer</option>
            </select>
        </div>-->

        <input class="button is-primary" type="submit" name="submit" id="submit">
    </form>
</div>

    <!--section right of form-->
    <div class="container p-3 mt-3">
        <div id="phpFrameHolder">
            <iframe src="placeholdertimes.php" style="width: 640px; height: 480px;"></iframe>
        </div>
    </div>

</section>



<script type="text/javascript" src="js/timeshow.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
</body>
</html>
