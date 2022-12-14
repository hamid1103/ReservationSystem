<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reqname = $_POST['fullname'];
    $reqemail = $_POST['email'];
    $reqnumber = $_POST['number'];
    $reqsubject = $_POST['subject'];
    $reqpassword = $_POST['password'];
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



//Check if an account with email already exists
//prepare query (USING PDO)
$sth = $pdo->prepare("SELECT * FROM accounts WHERE email = ?");
$sth->execute([$reqemail]);
//get all rows and store into $results
$results = $sth->fetchAll();

if($sth->rowCount() == 1){
    //IF account exists
    //for now nothing
    echo "Found a matching account for ". $reqemail;
    //get account email_id
    echo $sth->rowCount();
    $email_id = $results[0]['id'];
    echo $email_id;

    //TODO: password check
    //"This account already exists, and your password does not match"

    //Make reservation into db
    $sql = "INSERT INTO reservaties (date, time, number, subject, customer, email_id) VALUES (?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$reqdate, $reqtime, $reqnumber, $reqsubject, $reqname, $email_id]);
}else{
    echo "Making a account with " . $reqemail;
    //If accoune does not exist(else)
    //encrypt password
    $encpassword = password_hash($reqpassword, PASSWORD_DEFAULT);
    //make account
    $sth = $pdo->prepare("INSERT INTO accounts (id, username, email, password, fullname) VALUES (NULL, ?, ?, ?, ?)");
    $sth->execute([$reqemail, $reqemail, $encpassword, $reqname]);

    //get emailid
    $email_id_req = $pdo->prepare("SELECT * FROM accounts WHERE email = ?");
    $email_id_req->execute([$reqemail]);
    $results = $email_id_req->fetchAll();
    $email_id = $results[0]['id'];

    //Make reservation into db
    $sql = "INSERT INTO reservaties (date, time, number, subject, customer, email_id) VALUES (?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$reqdate, $reqtime, $reqnumber, $reqsubject, $reqname, $email_id]);
    $msg = "We made an account for you! Use your provided email and password!";
}
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


