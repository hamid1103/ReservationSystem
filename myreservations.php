<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];

$dt = new DateTime;
$ndt = new DateTime();
if (isset($_GET['year']) && isset($_GET['week'])) {
    $ndt->setISODate($_GET['year'], $_GET['week']);
    $dt->setISODate($_GET['year'], $_GET['week']);
} else {
    $ndt->setISODate($dt->format('o'), $dt->format('W'));
    $dt->setISODate($dt->format('o'), $dt->format('W'));
}
$year = $dt->format('o');
$week = $dt->format('W');

$nyear = $ndt->format('o');
$nweek = $ndt->format('W');

$host = 'localhost';
$user = 'root';
$db = 'reservatiesysteem';
$pass = ''; //No PW for dev mode - MAKE SURE U GOT SECURE PW LATER IN PRODUCTION
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
    <title>Science Planner - Admin page</title>
    <link rel="stylesheet" href="css/mystyles.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">

        <?php
        if ($_SESSION['name'] == 'admin') {?>
            <!--User icon-->
            <a class="navbar-item" href="admin.php">
                <p class="title">
                    Admin
                </p>
            </a>
        <?php } else{ ?>
            <!--User icon-->
            <a class="navbar-item">
                <p class="title">
                    <?= htmlentities($_SESSION['fullname'])?>
                </p>
            </a>
        <?php } ?>



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
                    <a class="button is-primary" href="logout.php">
                        Logout
                    </a>
                    <a class="button is-light" href="login.php">
                        Log in
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<section class="is-flex is-flex-direction-row is-align-content-center is-justify-content-center">
    <!--Section left of form-->
    <div class="container p-3 mt-3">
    </div>

    <!--Form in the middle-->
    <div class="container p-3 mt-3 has-text-centered">
        <p class="title">Week Schedule</p>
        <div class="is-flex is-justify-content-space-between">
            <a class="has-text-black is-underlined is-bold" href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week-1).'&year='.$year; ?>">Pre Week</a> <!--Previous week-->
            <a class="has-text-black is-underlined is-bold" href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week+1).'&year='.$year; ?>">Next Week</a> <!--Next week-->
        </div>

        <table class="table is-bordered">
            <tr>
                <?php
                do {
                    echo "<td>" . $dt->format('l') . "<br>" . $dt->format('d M Y') . "</td>\n";
                    $dt->modify('+1 day');
                } while ($week == $dt->format('W'));
                ?>
            </tr>
            <tr>
                <?php
                do {
                    echo "<td>";

                    $day = $ndt->format('Y') . "-".$ndt->format('m')."-".$ndt->format('d');
                    //mysql> SELECT CURDATE();
                    //        -> '2008-06-13'
                    //$sql = "SELECT * FROM reservaties WHERE YEARWEEK(`date`, 1) = YEARWEEK('$day', 1); ";

                    //$sql = "SELECT * FROM reservaties WHERE date = '$day'";
                    $sql = "SELECT reservaties.*, accounts.email as email FROM reservaties
                        LEFT JOIN accounts ON accounts.id = reservaties.email_id
                        WHERE reservaties.date ='$day' AND reservaties.email_id = $user_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $results = $stmt->fetchAll();
                    foreach ($results as $result){
                        echo "<a href='details.php?id=".htmlentities($result['id'])."'>";
                        echo "<div class='tdiv'>";
                        echo "<div class='rstdiv'>";
                        echo htmlentities($result['date']) . " " . htmlentities($result['time']);
                        echo "</div>";
                        echo "<br>";

                        echo "<div class='rstdiv'>";
                        echo htmlentities($result['number']);
                        echo "</div>";
                        echo "<br>";

                        echo "<div class='rstdiv'>";
                        echo htmlentities($result['subject']);
                        echo "</div>";
                        echo "<br>";

                        echo "<form method='post' action='./details.php' class='inline'>";
                        echo "<input type='hidden' name='id' value='".$result['id']."'>";
                        echo "<button type='submit'> Details/Edit Reservation </button>";
                        echo "</form>";

                        echo "</div>";
                        echo "</a>";
                    }

                    echo "</td>";
                    $ndt->modify('+1 day');
                } while ($nweek == $ndt->format('W'));
                ?>
            </tr>
        </table>
    </div>

    <!--section right of form-->
    <div class="container p-3 mt-3">
    </div>

</section>


</body>
</html>
