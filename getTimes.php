<?php
$date = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $date = $_GET['date'];
}
$errorset = false;
if (isset($_GET['error'])){
    $errorset = true;
}else{
}
$response = '';
if($errorset){
    $error = $_GET['error'];
}else{
    $url = "http://localhost:3000/geteventsondate/".$date;
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
    $divstyle = "font-family: 'Titillium Web', sans-serif;color: black;font-size: 1.2em;background-color: orange;border: #0d0d0d solid thick;position: fixed;top: 25%;left: 45%;width: 30vh;padding: 10px;";
    echo "<div style=";
    echo '"'.$divstyle.'">';
    echo "<p>De rode blokjes laten zien op welke tijden ik niet beschikbaar ben.</p>";
    echo "</div>";
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
    echo `<div style="font-family: 'Titillium Web', sans-serif;color: black;font-size: 1.2em;background-color: orange;border: #0d0d0d solid thick;position: fixed;top: 25%;left: 45%;width: 30vh;padding: 10px;">
    <p>$error mag niet. Vul een volledige datum in.</p>
</div>`;
}
?>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    lucide.createIcons();
</script>
</body>
</html>