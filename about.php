<?php
session_start();
require_once 'app/helpers.php';
$page_title = 'About';
require_once 'templates/header.php';
?>

<section class="container p-4">
    <h1 class="text-primary display-3">About</h1>
</section>

<?php
include_once 'templates/footer.php';
?>