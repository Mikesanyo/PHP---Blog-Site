<?php
session_start();
require_once 'app/helpers.php';
redirect_auth(false, './signin.php');

$uid = $_SESSION['user_id'] ?? null;
$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
$query = "SELECT * FROM users WHERE id=$uid";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
} else {
    header('location: ./blog.php');
    exit();
}

/*
* show all user's posts 
*/
$all_posts = "SELECT * FROM posts WHERE user_id=$uid ORDER BY created_at DESC";
$posts_result = mysqli_query($link, $all_posts);

if ($all_posts && mysqli_num_rows($posts_result) > 0) {
    $post = mysqli_fetch_assoc($posts_result);
}


$page_title = 'About';
require_once 'templates/header.php';
?>

<section class="container p-4 col-md-8">

    <img src="images/profiles/<?= $user['profile_image'] ?>" id="photo" class="mt-5 rounded float-start" alt="<?= $user['name'] ?>" style="width:100px; height:100px; object-fit:cover; margin-right:15px;">


    <div class="row">
        <h1 class="text-primary display-6 mt-5 mb-1"><?= $user['name'] ?></h1>
        <div><?= $user['email'] ?></div>


    </div>


    <!-- show all posts if exist -->
    <?php if (!empty($post)) : ?>
        <h2 class="display-5 mt-4 mb-4">Your Latest Post</h2>
        <?php while ($post = mysqli_fetch_assoc($posts_result)) : ?>
            <div class="my-3">
                <div class="card">
                    <div class="card-header d-flex">
                        <!-- htmlentities prevent from attacker to script our code -->
                        <div class="me-auto bd-highlight">
                            <img src="images/profiles/<?= $user['profile_image'] ?>" alt="default profile image" srcset="" style="height:35px; width:35px;">
                            <span><?= htmlentities($user['name']); ?></span>
                        </div>
                        <span><?= ago($post['created_at']); ?></span>

                        <div class="dropdown">
                            <a class="dropdown-toggle text-dark dropdown-no-arrow" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i>
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <!-- sending the data via "GET" method by using href...?pid and then the php part that stores the post's id -->
                                <li><a class="dropdown-item dropdown-no-arrow" href="edit-post.php?pid=<?= $post['id'] ?>&token=<?= $_SESSION['token'] ?>"><i class="bi bi-pencil"></i> Edit Post</a></li>

                                <li><a class="dropdown-item dropdown-no-arrow" href="delete-post.php?pid=<?= $post['id'] ?>&token=<?= $_SESSION['token'] ?>"><i class="bi bi-trash"></i> Delete Post</a></li>
                            </ul>
                        </div>


                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlentities($post['title']); ?></h5>
                        <p class="card-text"><?= nl2br(htmlentities($post['article'])); ?></p>
                        <!-- adit/delete button/link -->
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <h3>You Haven't Posted yet.</h3>
            <h4>Start <a href="./add-post.php" class="btn btn-primary">here</h4></a>

        <?php endif; ?>

            </div>

</section>

<?php
include_once 'templates/footer.php';
?>