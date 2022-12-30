<?php

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

<form method="post" action="http://localhost:3000/updatePost" class="is-flex is-flex-direction-column">
    <label for="client_id">Client_ID</label>
    <input type="text" name="client_id" id="client_id" value="<?= $response['clientId'] ?>">

    <label for="client_secret">Client_Secret</label>
    <input type="text" name="client_secret" id="client_secret" value="<?= $response['clientSecret'] ?>">

    <label for="sub">Sub</label>
    <input type="text" name="sub" id="sub" value="<?= $response['sub'] ?>">

    <label for="dataCenter">Data Center ID</label>
    <input type="text" name="dataCenter" id="dataCenter" value="<?= $response['dataCenter'] ?>">

    <label for="accessToken">Access Token</label>
    <input type="text" name="accessToken" id="accessToken" value="<?= $response['accessToken'] ?>" >
</form>

</body>
</html>

