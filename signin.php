<?php
require_once 'app/helpers.php';
//to implant coockie to stay logged in while browsing
session_start();
redirect_auth();
$page_title = 'Sign In';
require_once 'templates/header.php';

//Once form is submited we asking isset/checking if declared and NOT NULL
if (validate_csrf() && isset($_POST['submit'])) {
    //the Connection to DB
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB); //rows 6-9 above
    //Input validatoin and secure from attackers
    $email = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
    $email = trim($email);
    $email = mysqli_real_escape_string($link,$email);
    
    $password = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
    $password = trim($password);
    
    $is_form_valid = true;
    //Notice Errors
    if (!$email) {
        $is_form_valid = false;
        $errors['email'] = '* A Valid Email is Required';
    }
    if (!$password) {
        $is_form_valid = false;
        $errors['password'] = '* Please Enter your password';
    }
    
    if ($is_form_valid) {
        //the Query we send to the DB cause we want a specific email and not all emails
        $query = "SELECT * FROM users WHERE email='$email'";

        $result = mysqli_query($link, $query);
        //Check if Exist in DB
        //if the connections($result) and the email is found in(_num_rows($result))
        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            //Set User (Session) as Connected to the site
            if (password_verify($password,$user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                header('location: ./profile.php');
                die;
            } else {
                $errors['submit'] = '* Wrong Password';
            }
        } else {
            $errors['submit'] = '* User not found or wrong password';
        }
    }
}
?>

<section class="container p-4">
    <div class="col-md-6">
        <h1 class="mt-4 display-text-6">Enter Email and Password to Sign-in:</h1>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit.</p>
        <form method="post">

            <input type="hidden" name='token' value="<?= csrf(); ?>">

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" value="<?= posted_value('email') ?>" class="form-control" id="email" aria-describedby="emailHelp">
                <?= field_errors('email') ?>
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password">
                <?= field_errors('password') ?>
            </div>

            <button type="submit" name="submit" value="submit" class="btn btn-primary">Sign In</button>
            <?=field_errors('submit')?>
        </form>
    </div>
</section>

<?php
include_once 'templates/footer.php';
?>