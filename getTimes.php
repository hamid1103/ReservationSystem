<?php

$date = $_GET['date'];
$errorset = false;
if (isset($_GET['error'])){
    $error = $_GET['error'];
    $errorset = true;
}else{
}
$response = '';
if($errorset){

}else{
    $url = "http://localhost:3000/geteventsondate/".$date;
    //$url = `http://localhost:3000/geteventsondate/{$date}`;
    $response = file_get_contents($url);
    $decoded_json = json_decode($response, true);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Science Planner</title>
    <link rel="stylesheet" href="css/getTimes.css">
</head>
<body>

<?php
if ($errorset == false) {
//voor elk event in response ( in volgorde )
    //nieuwe div
        //zet in div: timestart, timeend
foreach ($decoded_json as $date){
    ?>

    <div class="time">
        <p> Busy</p>
        <p>from: <?php
            $starttime = preg_split('/\./', $date['startTime'][1]);
            print $starttime[0]
            ?></p>
        <p>to: <?php
            $endtime = preg_split('/\./', $date['endTime'][1]);
            print $endtime[0]
            ?></p>
    </div>

    <?php
}


}else{
    echo 'Error';
    print_r($error);
}

?>

</body>
</html>