<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reqname = $_POST['fullname'];
    $reqemail = $_POST['email'];
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

$sql = "INSERT INTO reservaties (date, time, email, number, subject, customer) VALUES (?,?,?,?,?,?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$reqdate, $reqtime, $reqemail, $reqnumber, $reqsubject, $reqname]);

//send mail shit - Implement later. We can use a google account or via ms graph or via some email helper
/*
 * //The mail that's send will contain a file for adding reservation to own digital agenda and a link that will allow
 * // the customer to change/cancel their reservation
$to = $reqemail;
$from = 'sender@email.com';
$fromName = 'ReservatieSysteem';

$subject = "Confirmation";

$message = "Hello, ".$reqname.".\nYour reservation has been made.";

// Additional headers
$headers = 'From: '.$fromName.'<'.$from.'>';

// Send email
if(mail($to, $subject, $message, $headers)){
    echo 'Email has sent successfully.';
}else{
    echo 'Email sending failed.';
}
*/


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
                echo "Name: " . $resData['name'] . "<br>";
                echo "Date and Time: " . $resData['date'] . "   " . $resData['time'] . "<br>";
                echo "Email: " . $resData['mail'] . "<br>";
                echo "Mobile Number: " . $resData['number'] . "<br>";
                echo "Subject: " . $resData['subject'] . "<br>";
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
                Your reservation has been succesfully made.
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


