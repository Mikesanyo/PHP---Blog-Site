<?php
require_once 'app/helpers.php';
session_start();
redirect_auth(false, './signin.php');

if (validate_csrf() && isset($_GET['pid']) && is_numeric($_GET['pid'])) { //was the delete button pressed = we got value via GET method AND if the value is a number then...
    $pid = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);

    if ($pid) { //only the user that posted the post allow to delete
        $uid = $_SESSION['user_id'];
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
        $query = "DELETE FROM posts WHERE id=$pid AND user_id=$uid";
        $result = mysqli_query($link, $query);

        if ($result && mysqli_affected_rows($link) === 1) {
            header('location: ./blog.php');
            exit;
        } else{
            exit("Couldn't delete post");
        }
    }
}
header('Location: ./blog.php');
exit;

