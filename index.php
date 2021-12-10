<?php
require_once 'app/helpers.php';
session_start();
$page_title = 'Home';
require_once 'templates/header.php';
?>

<section class="container text-center p-4">
    <h1 class="text-primary display-3 mt-4">Welcome to iCar</h1>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum, placeat.</p>
    <?php if(!is_logged_in()): ?>
    <a class="btn btn-outline-primary btn-lg" href="singup.php" role="button">Join the iCar blog</a>
    <?php endif;?>
</section>

<section class="container p-4">
    <div class="row">
        <div class="col-6">
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolorum nesciunt dolores vel officia,
                architecto error officiis enim rerum ea culpa provident fuga atque facere numquam porro omnis
                aut ut ad fugit eos blanditiis quod sed laborum? Quod perspiciatis sed sint facere assumenda est
                nihil? Tenetur!</p>
        </div>
        <div class="col-6">
            <img src="images\car-race-960_720.jpg" class="img-fluid" alt="blue race car picture">
        </div>
    </div>
</section>

<?php
include_once 'templates/footer.php';
?>