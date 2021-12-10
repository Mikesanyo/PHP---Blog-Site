<?php
require_once 'app/helpers.php';
session_start();
$page_title = 'Blog';
require_once 'templates/header.php';

$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
//show me user name, and all posts
//connect "posts" table and "users" table where user.id = post.user_id
//order by time-created (Descending)
$query = "SELECT users.name,users.profile_image, posts.*
          FROM posts JOIN users ON users.id = posts.user_id
          ORDER BY created_at DESC";
$result = mysqli_query($link, $query);
?>

<section class="container p-4 col-md-6">
    <!-- column inside row/section isn't the best way to use bootstrap but imma try it anyway -->
    <h1 class="text-primary display-3"><i class="bi bi-pen"></i>Blog</h1>

    <!--  "add post" Button will be displayed  IF LOGGED IN -->
    <?php if (is_logged_in()) : ?>
        <a class="btn btn-primary" href="add-post.php" role="button"><i class="bi bi-plus-circle-fill" style="margin-right:7px;"></i> Add New Post</a>
    <?php endif; ?>

    <?php if ($result && mysqli_num_rows($result) > 0) : ?>
        <h2 class="mt-2 mb-2 display-6">i <span class="text-primary"><i class='bi bi-suit-heart-fill'></i></span> Car Posts</h2>

        <?php while ($post = mysqli_fetch_assoc($result)) : ?>
            <div class="my-3">
                <div class="card">
                    <div class="card-header d-flex">
                        <!-- htmlentities prevent from attacker to script our code -->
                        <div class="me-auto bd-highlight">
                            <img src="images/profiles/<?= $post['profile_image'] ?>" alt="default profile image" srcset="" style="height:35px; width:35px;">
                            <span><?= htmlentities($post['name']); ?></span>
                        </div>
                        <span><?= ago($post['created_at']); ?></span>

                        <!-- Edit or Delete Post if Logged in as Author -->
                        <?php if (is_logged_in() && $post['user_id'] === $_SESSION['user_id']) : ?>
                            <div class="dropdown">
                                <a class="dropdown-toggle text-dark dropdown-no-arrow" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i>
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <!-- sending the data via "GET method by using href "?pid" and then the php part that stores the post's id -->
                                    <li><a class="dropdown-item dropdown-no-arrow" href="edit-post.php?pid=<?= $post['id'] ?>&token=<?= $_SESSION['token'] ?>"><i class="bi bi-pencil"></i> Edit Post</a></li>
                                    
                                    <li><a class="dropdown-item dropdown-no-arrow" href="delete-post.php?pid=<?= $post['id'] ?>&token=<?= $_SESSION['token'] ?>"><i class="bi bi-trash"></i> Delete Post</a></li>
                                </ul>
                            </div>

                            

                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title"><?= htmlentities($post['title']); ?></h5>
                        <p class="card-text"><?= nl2br(htmlentities($post['article'])); ?></p>
                        <!-- adit/delete button/link -->
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

    <?php else : ?>
        <h2>No Posts yet. Be the first to post on our site.</h2>
    <?php endif; ?>

</section>
<?php
include_once 'templates/footer.php';
?>