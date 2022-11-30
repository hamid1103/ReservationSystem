<?php

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

/*$sql = "SELECT * FROM reservaties WHERE date >= ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll();*/



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Science Planner - Admin page</title>
    <link rel="stylesheet" href="css/mystyles.css">
</head>
<body>
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
                    $sql = "SELECT * FROM reservaties WHERE date LIKE " . $day;
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $results = $stmt->fetchAll();
                    echo $sql;
                    echo $results;
                /*foreach ($results as $result){
                    echo "<div clas='tdiv'>";
                    echo $result;
                    echo "</div>";
                }*/

                    echo "</td>";
                    //echo "<td>" . $ndt->format('l') . "<br>" . $ndt->format('d M Y') . "</td>\n";
                    $ndt->modify('+1 day');
                } while ($nweek == $ndt->format('W'));
                ?>

                <!--<td>
                    //<//? //php
/*                        echo "<div class='tdiv'>";
                            //add new div that represents appointments
                    $day = $ndt-> format('d M Y');
                    $sql = "SELECT * FROM reservaties WHERE date LIKE " . $day;
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $results = $stmt->fetchAll();
                        echo "</div>";
                    */? //>
                </td>-->

            </tr>
        </table>
    </div>

    <!--section right of form-->
    <div class="container p-3 mt-3">
    </div>

</section>


</body>
</html>