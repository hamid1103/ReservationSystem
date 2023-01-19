<?php
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


$action = '';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['id'];
    //get reservation data by id
    $sql = "SELECT * FROM reservaties WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$reservation_id]);
    $reservation = $stmt->fetchAll();
    $resdets = $reservation[0];

    if (isset($_POST['edit'])){
        //als gaat edit
        $action = 'edit';
    }elseif (isset($_POST['edited'])){

        //get post data
        $date = $_POST['date'];
        $time = $_POST['time'];
        $subject = $_POST['subject'];

        //edit db
        $sql = "UPDATE reservaties SET date = :date, time = :time, subject = :subject WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $reservation_id, PDO::PARAM_INT);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        if($_SESSION['id']==6){
            header('Location: admin.php');
        }else{
            header('Location: myreservations.php');
        }

    }elseif (isset($_POST['remove'])){
        $action = 'remove';
    }elseif (isset($_POST['removed'])){
        //remove from db
        $eventid = $resdets['eventID'];
        $query = "DELETE FROM reservaties WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$reservation_id]);

        //call remove api call with eventID
        $url = "http://localhost:3000/delEv/".$eventid;
        $response = file_get_contents($url);

        if($_SESSION['id']==6){
            header('Location: admin.php');
        }else{
            header('Location: myreservations.php');
        }
    }
    else{
        //anders, laat normale pagina zien
        $action = 'details';
    }
}elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
    $action = 'error';
    $msg = "Use post, not get. If you see this error message... sorry, please login and try again via 'my reservations'. ";
}


//check if current user has access to reservation data
//if not, send back to myreservations
if($_SESSION['id'] != '6'){
    if ($_SESSION['id'] != $reservation[0]['email_id']){
        header('Location: ./myreservations.php');
    }
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
                My Reservations
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

<?php if($action == 'details') { ?>
    <div class="container-lg w-25 border border-dark p-3 mt-3">
        <div class="has-text-centered">
            <p class="title">
                Confirmation of reservation data.
            </p>
            <p class="subtitle">
                <!-- Date & time | Name | Subject | Number -->
              <b>Date and time:</b> <?= $resdets['date']." ".$resdets['time']?> <br>
                <b>Subject:</b> <?= $resdets['subject']." ".$resdets['time']?>
            </p>
            <form method='post' action='' class='inline'>
                <input type='hidden' name='id' value='<?php echo $reservation_id ?>'>
                <input type='hidden' name='edit' value='1'>
                <button type='submit'> Edit </button>
            </form>
            <form method='post' action='' class='inline'>
                <input type='hidden' name='id' value='<?php echo $reservation_id ?>'>
                <input type='hidden' name='remove' value='1'>
                <button type='submit'> Remove </button>
            </form>
        </div>
    </div>

<?php } elseif ($action == 'edit') { ?>
<section class="is-flex is-flex-direction-row is-align-items-center is-justify-content-center">
    <div class="container p-3 mt-3">
    </div>
    <div class="container-lg w-25 border border-dark p-3 mt-3">
        <div class="has-text-centered">
            <p class="title">
                Editting
            </p>
            <form method="post" action="">
                <div class="m-3">
                    <label for="InputDate" class="form-label">Datum</label>
                    <input type="date" id="InputDate" class="form-control" onkeyup="showtime(this.value)" value="<?= $resdets['date'] ?>" name="date">
                </div>
                <div class="m-3">
                    <label for="InputTime" class="form-label">Tijd</label>
                    <input type="time" id="InputTime" class="form-control" name="time" value="<?= $resdets['time'] ?>">
                </div>
                <div class="mb-3">
                    <label for="Subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" id="Subject"  value="<?= $resdets['subject'] ?>" name="subject">
                </div>
                <input type='hidden' name='id' value='<?php echo $reservation_id ?>'>
                <input type='hidden' name='edited' value='edited'>
                <input type="submit" name="submit" id="submit">
            </form>
            <form method='post' action='' class='inline'>
                <input type='hidden' name='id' value='<?php echo $reservation_id ?>'>
                <button type='submit'> Cancel Edit </button>
            </form>
        </div>
    </div>
    <div class="container p-3 mt-3">
        <div id="phpFrameHolder">

        </div>
    </div>
</section>
    <script type="text/javascript" src="js/timeshow.js"></script>
<?php } elseif ($action == 'error'){?>

        //error image

    <div class="container-lg w-25 border border-dark p-3 mt-3">
        <div class="has-text-centered">
            <p class="title">
                Error
            </p>
            <p class="subtitle">
                <?php
                echo $msg;
                ?>
            </p>
        </div>
    </div>

<?php } elseif ($action == 'remove') { ?>
    <div class="container-lg w-25 border border-dark p-3 mt-3">
        <div class="has-text-centered">
            <p class="title" style="color: red">
                Are you sure you want to remove this reservation?
            </p>
            <p class="subtitle">
                <!-- Date & time | Name | Subject | Number -->
                <b>Date and time:</b> <?= $resdets['date']." ".$resdets['time']?> <br>
                <b>Subject:</b> <?= $resdets['subject']." ".$resdets['time']?>
            </p>
            <form method='post' action='' class='inline'>
                <input type='hidden' name='id' value='<?php echo $reservation_id ?>'>
                <input type='hidden' name='removed' value='1'>
                <button type='submit'> Confirm remove </button>
            </form>
        </div>
    </div>
<?php } elseif ($action == 'removed') { ?>

<?php }?>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    lucide.createIcons();
</script>
</body>
</html>