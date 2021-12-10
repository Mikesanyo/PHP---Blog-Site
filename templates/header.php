<?php
$site_logo = "i <i class='bi bi-suit-heart-fill'></i> Car";
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- favicon -->
    <link rel="icon" href="images\pen.svg" sizes="any" type="image/svg+xml">
    <!-- style css -->
    <link rel="stylesheet" href="css/style.css">
    <title>
        <?= $page_title ?> - iCar </title>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- strech the header and the footer -->
    <header>
        <nav class="navbar navbar-expand-sm navbar-dark bg-primary shadow-lg">
            <div class="container">
                <!-- site logo -->
                <a class="navbar-brand" href="./"><?= $site_logo ?></a>
                <!--the Hamburger Button  -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- the collapsed navbar -->
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                    <!--  Site Left Navigation -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?= active_nav_link('Home') ?>" aria-current="page" href="./">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= active_nav_link('About') ?>" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= active_nav_link('Blog') ?>" href="blog.php">Blog</a>
                        </li>
                    </ul>
                    <!-- Site Right Navigation -->
                    <?php if (!empty($_SESSION['user_name'])) : ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a href="profile.php" class="nav-link"><?= $_SESSION['user_name']?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Log Out</a>
                            </li>
                        </ul>
                    <?php else : ?>

                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?= active_nav_link('Sign In') ?>" href="signin.php">Sign In </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= active_nav_link('Sign Up') ?>" href="signup.php">Sign Up </a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <!-- strech the header and the footer -->
    <main class="flex-fill">