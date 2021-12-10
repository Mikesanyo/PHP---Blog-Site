<?php
require_once 'app/helpers.php';
session_start();

if (validate_csrf() && isset($_POST['submit'])) {
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
    //SANITIZE = removing rubbish that doesnt match except
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING); //Strip tags and HTML-encode double and single quotes 
    $name = trim($name); //trim the "spaces"
    $name = mysqli_real_escape_string($link, $name); //mysql_real_escape_string() prepends backslashes to the following characters: \x00, \n, \r, \, ', " and \x1a.

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); //Remove all characters except letters, digits and !#$%&'*+-=?^_`{|}~@.[].
    $email = trim($email);
    $email = mysqli_real_escape_string($link, $email);

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $password = trim($password);
    //mysqli-real-escape-string can mess up with the password...

    $profile_image = 'default_profile.png';
    $image = $_FILES['image'] ?? null; //null coalescing (if there is no image, return NULL)

    $is_form_valid = true;

    /*
    * validate name
    */
    if (!$name || mb_strlen($name) < 2 || mb_strlen($name) > 50) {
        $is_form_valid = false;
        $errors['name'] = '* A valid name is required with a minimun 2 characters and maximun 50';
    }
    /*
    * validate email 
    */
    if (!$email) {
        $is_form_valid = false;
        $errors['email'] = '* A valid emails is required';
    } else {
        //checking if the email submited already exist in our DB
        $query = "SELECT email FROM users WHERE email='$email'";
        $result = mysqli_query($link, $query);

        if (!$result) {
            $is_form_valid = false;
            $errors['email'] = '* Error';
        }
        if ($result && mysqli_num_rows($result) > 0) {
            $is_form_valid = false;
            $errors['email'] = '* Email is already exist, try another email';
        }
    }

    /*
    * validate password
    */

    if (!$password || mb_strlen($password) < 6 || mb_strlen($password) > 20) {
        $is_form_valid = false;
        $errors['password'] = '* Password must be minimum 6 characters and maximum 20';
    } elseif (!preg_match("/(?=.*[a-z])(?=.*[A-Z])/", $password)) {
        $is_form_valid = false;
        $errors['password'] = '* Please use minimum 1 Upper case letter and 1 low case letter';
    }
    /*
    * validate image and move to profiles directory
    * else - generate random bot-avatar instead
    */
    if ($is_form_valid) {
        
        $allowed=['jpg','jpeg','png','gif'];
        define('MAX_FILE_SIZE',1024* 1024* 2); //maximum of 2 MegaBytes
        if (
            isset($image) &&  // if image exist
            isset($image['name']) &&  // $image => name (key in associative array)
            $image['error'] === UPLOAD_ERR_OK && // if there is no error / '0' 
            is_uploaded_file($image['tmp_name']) && // return true/false if the temporary_name 'key' exist
            $image['size'] <= MAX_FILE_SIZE && 
            in_array(pathinfo($image['name'])['extension'],$allowed)
            ) {
                $profile_image = date('Y.m.d.H.i.s') . '-' . $image['name'];
                move_uploaded_file($image['tmp_name'], "images/profiles/$profile_image");
            } else {
                $profile_image = random_image();
            }
            
    /*
    * upload to DB 
    */
        $password = password_hash($password, PASSWORD_BCRYPT); //encrypt the password so none in the DB can use it
        $query = "INSERT INTO users VALUES (NULL,'$email','$password','$name','$profile_image')";
        $result = mysqli_query($link, $query);

        if ($result && mysqli_affected_rows($link) === 1) { //was the user Signed-up ? then log him in right away.
            $uid = (string) mysqli_insert_id($link);
            //returns the value of the "Auto Increment" column in the last query.
            $_SESSION['user_id'] = $uid;
            $_SESSION['user_name'] = $name;
            header('location: ./profile.php');
            exit;
        } else {
            exit('DB ERROR.');
        }
    }
}

$page_title = 'Sign Up';
require_once 'templates/header.php';
?>

<section class="container p-4">
    <div class="col-md-6">
        <h1 class="mt-4 display-text-6">Signed up for a new account</h1>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit.</p>

        <form method="post" enctype="multipart/form-data">
            <!-- enctype is a must cause of the file we upload -->

            <input type="hidden" name="token" value="<?= csrf(); ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" value="<?= posted_value('name') ?>" class="form-control" id="name">
                <?= field_errors('name') ?>
                <div id="nameHelp" class="form-text"></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" value="<?= posted_value('email') ?>" class="form-control" id="email">
                <?= field_errors('email') ?>
                <div id="emailHelp" class="form-text"></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password">
                <?= field_errors('password') ?>
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Upload Profile Picture</label>
                <input class="form-control" type="file" name="image" id="formFile">
            </div>

            <button type="submit" name="submit" value="submit" class="btn btn-primary">Sign In</button>
            <?= field_errors('submit') ?>
        </form>

    </div>
</section>

<?php
include_once 'templates/footer.php';
?>