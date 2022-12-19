<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Science Planner - Login</title>
    <link rel="stylesheet" href="css/mystyles.css">
</head>
<body class="is-flex is-flex-direction-row">
<section class="d-flex flex-row mb-3 align-content-center justify-content-center">
    <!--Section left of form-->
    <div class="container-lg w-25 border border-dark p-3 mt-3">

    </div>

    <!--Middle-->
    <div class="container-lg w-25 border border-dark p-3 mt-3">
        <div class="login">
            <h1>Login</h1>
            <form action="authenticate.php" method="post">
                <label for="username">

                </label>
                <input type="text" name="username" placeholder="Username" id="username" required>
                <label for="password">
                    <i class="fas fa-lock"></i>
                </label>
                <input type="password" name="password" placeholder="Password" id="password" required>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>



    <!--section right of form-->
    <div class="container-lg w-25 border border-dark p-3 mt-3">
        <a href="index.php">
            <button class="button">Homepage</button>
        </a>
    </div>

</section>


</body>
</html>