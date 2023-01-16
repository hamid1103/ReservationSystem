<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Science Planner - Contact</title>
    <link rel="stylesheet" href="css/mystyles.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/customs.css">
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

<section class="is-flex is-flex-direction-row is-align-items-center is-justify-content-center">
    <!--Section left of form-->
    <div class="container p-3 mt-3">
        <div class="has-text-centered">
            <p class="title">
                Telefonisch:
            </p>
            <p class="subtitle">
                +31 06 30364221
            </p>
        </div>
    </div>

    <!--Form in the middle-->
    <div class="container p-3 mt-3">
        <div class="has-text-centered">
            <p class="title">
                Email:
            </p>
            <p class="subtitle">
              melleqk@gmail.com
            </p>
        </div>
    </div>

    <!--section right of form-->
    <div class="container p-3 mt-3">
        <div class="has-text-centered">
            <p class="title">
                Email:
            </p>
            <p class="subtitle">
                info@mllq.com
            </p>
        </div>
    </div>

</section>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
</body>
</html>