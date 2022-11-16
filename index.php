<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Science Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
<?php
// Enable loading of Composer dependencies
require_once realpath(__DIR__ . '/vendor/autoload.php');
require_once 'GraphHelper.php';

print('PHP Graph Tutorial' . PHP_EOL . PHP_EOL);

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['CLIENT_ID', 'CLIENT_SECRET', 'TENANT_ID', 'AUTH_TENANT', 'GRAPH_USER_SCOPES']);

initializeGraph();

greetUser();

$choice = -1;

while ($choice != 0) {
    echo('Please choose one of the following options:' . PHP_EOL);
    echo('0. Exit' . PHP_EOL);
    echo('1. Display access token' . PHP_EOL);
    echo('2. List my inbox' . PHP_EOL);
    echo('3. Send mail' . PHP_EOL);
    echo('4. List users (requires app-only)' . PHP_EOL);
    echo('5. Make a Graph call' . PHP_EOL);

    $choice = (int)readline('');

    switch ($choice) {
        case 1:
            displayAccessToken();
            break;
        case 2:
            listInbox();
            break;
        case 3:
            sendMail();
            break;
        case 4:
            listUsers();
            break;
        case 5:
            makeGraphCall();
            break;
        case 0:
        default:
            print('Goodbye...' . PHP_EOL);
    }
}

function initializeGraph(): void {
    // TODO
}

function greetUser(): void {
    // TODO
}

function displayAccessToken(): void {
    // TODO
}

function listInbox(): void {
    // TODO
}

function sendMail(): void {
    // TODO
}

function listUsers(): void {
    // TODO
}

function makeGraphCall(): void {
    // TODO
}

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
