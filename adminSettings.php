<?php
/*// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
    //If user = not admin redirect to the login page...
}elseif($_SESSION['name'] != 'admin'){
    header('Location: index.php');
    exit;
}*/

function callAPI($method, $url, $data){
    $curl = curl_init();
    switch ($method){
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'APIKEY: 111111111111111111111',
        'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;
}

//check status of restapi
$get_data = callAPI('GET', 'http://localhost:3000/checkInit', false);
$response = json_decode($get_data, true);
if($response['auth'] == 'true'){

}else{
    header('Location: http://localhost:3000/msAuth');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Science Planner</title>
    <link rel="stylesheet" href="css/mystyles.css">
    <script src="https://elements.cronofy.com/js/CronofyElements.v1.52.5.js"></script>
</head>
<body>

<?php

$get_data = callAPI('GET', 'http://localhost:3000/getEnv', false);
$response = json_decode($get_data, true);

?>

    <label for="client_id">Client_ID</label>
    <input type="text" name="client_id" id="client_id" value="<?= ['env']['CLIENT_ID'] ?>">

    <label for="AUTH_TENANT">AUTH_TENANT</label>
    <input type="text" name="AUTH_TENANT" id="AUTH_TENANT" value="<?= $response['env']['AUTH_TENANT'] ?>">

</body>
</html>

