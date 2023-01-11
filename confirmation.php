<?php
session_start();
$reqpassword = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['email'])){
        $reqname = $_POST['fullname'];
        $reqemail = $_POST['email'];
        $reqpassword = $_POST['password'];
    }else{
        $reqname = $_SESSION['fullname'];
        $reqemail = $_SESSION['email'];
        $reqpassword = $_SESSION['password'];
    }
    $reqnumber = $_POST['number'];
    $reqsubject = $_POST['subject'];
    $reqdate = $_POST['date'];
    $reqtime = $_POST['time'];
    $resData = array(
        "name" => $reqname,
        "mail" => $reqemail,
        "number" => $reqnumber,
        "subject" => $reqsubject,
        "date" => $reqdate,
        "time" => $reqtime,
    );
}else{
    $msg = 'Error: No query. Please make sure to use the form located in the homepage';
}

//front end php vars
$msg = '';

//Setup MySQL Connection
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

//add to outlook calendar - add +1 hour in the time and save as endtime
//do this first to get the eventid
$endtimeraw = strtotime($reqtime) + 60*60;
$endtime = date('H:i', $endtimeraw);

$url = "http://localhost:3000/syncToLook";
$data = array(
    'subject' => $reqsubject,
    'name' => $reqname,
    'date' => $reqdate,
    'starttime' => $reqtime,
    'endtime' => $endtime,
    'email' => $reqemail,
    'number' => $reqnumber
);

$options = array(
    'http' => array(
        'method'  => 'POST',
        'content' => json_encode( $data ),
        'header'=>  "Content-Type: application/json\r\n" .
            "Accept: application/json\r\n"
    )
);

$context  = stream_context_create( $options );
$result = file_get_contents( $url, false, $context );
$calendarresponse = json_decode( $result );


//Check if an account with email already exists
//prepare query (USING PDO)
$sth = $pdo->prepare("SELECT * FROM accounts WHERE email = ?");
$sth->execute([$reqemail]);
//get all rows and store into $results
$results = $sth->fetchAll();

if($sth->rowCount() == 1){
    //IF account exists
    //get account email_id
    $email_id = $results[0]['id'];

    //get password from user where email = $regemail
    //check if logged in
    if(isset($_SESSION['loggedin'])){
        //if logged in, skip auth and just add to db with required emailID
        $sql = "INSERT INTO reservaties (date, time, number, subject, customer, email_id) VALUES (?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$reqdate, $reqtime, $reqnumber, $reqsubject, $reqname, $_SESSION['id']]);
        $msg = "The reservation has been made, ".$_SESSION['fullname'];
    }else{
        //if not logged in -> $reqpassword would not be a hashed password
        if(password_verify($reqpassword, $results[0]['password'])){
            //Password is correct
            $sql = "INSERT INTO reservaties (date, time, number, subject, customer, email_id) VALUES (?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$reqdate, $reqtime, $reqnumber, $reqsubject, $reqname, $email_id]);
            $msg = "The reservation has been made, ".$results[0]['fullname'];
        }else{
            //Password is incorrect
            $msg = "The reservation has not been made. \n The password that was filled in is wrong. You can log in before making the reservation or try again.";
        }
    }
}else{
    //If account does not exist(else)
    //encrypt password
    $encpassword = password_hash($reqpassword, PASSWORD_DEFAULT);
    //make account
    $sth = $pdo->prepare("INSERT INTO accounts (username, email, password, fullname) VALUES (?, ?, ?, ?)");
    $sth->execute([$reqemail, $reqemail, $encpassword, $reqname]);

    //get emailid
    $email_id_req = $pdo->prepare("SELECT * FROM accounts WHERE email = ?");
    $email_id_req->execute([$reqemail]);
    $results = $email_id_req->fetchAll();
    $email_id = $results[0]['id'];

    //Make reservation into db
    $sql = "INSERT INTO reservaties (date, time, number, subject, customer, email_id, eventID) VALUES (?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$reqdate, $reqtime, $reqnumber, $reqsubject, $reqname, $email_id, $result]);
    $msg = "We made an account for you! Use your provided email and password!";
}

sleep(5);

//echo $result

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
<section class="d-flex flex-row mb-3 align-content-center justify-content-center">
    <!--Section left of form-->
    <div class="container-lg w-25 border border-dark p-3 mt-3">
        <div class="has-text-centered">
            <p class="title">
                Filled in data.
            </p>
            <p class="subtitle">
                <?php
                echo "Name: " . htmlentities($resData['name']) . "<br>";
                echo "Date and Time: " . htmlentities($resData['date']) . "   " . $resData['time'] . "<br>";
                echo "Email: " . htmlentities($resData['mail']) . "<br>";
                echo "Mobile Number: " . htmlentities($resData['number']) . "<br>";
                echo "Subject: " . htmlentities($resData['subject']) . "<br>";
                ?>
            </p>
        </div>
    </div>

    <!--Middle-->
    <div class="container-lg w-25 border border-dark p-3 mt-3">
        <div class="has-text-centered">
            <p class="title">
                Confirmation of reservation data.
            </p>
            <p class="subtitle">
                <?php
                echo $msg;
                ?>
            </p>
        </div>
    </div>

    <a href="index.php">
        <button class="button">Homepage</button>
    </a>

    <!--section right of form-->
    <div class="container-lg w-25 border border-dark p-3 mt-3">
    </div>

</section>


</body>
</html>


