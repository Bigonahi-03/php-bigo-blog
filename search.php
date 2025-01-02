<!-- Header Section -->
<?php include "./include/layout/header.php"; ?>

<?php
// Search in the posts table
if (isset($_GET['search'])) {
    $keyword = htmlspecialchars($_GET['search']) ;
    $posts = $db->prepare("SELECT * FROM posts WHERE title LIKE :keyword");
    $posts->execute(['keyword' => "%$keyword%"]);
}

?>

<main>
    <!-- Content Section -->
    <section class="mt-4">
        <div class="row">
            <!-- Posts Content -->
            <div class="col-lg-8">
                <div class="row">
                    <div class="col">
                        
                        <!-- Show searched posts -->
                        <div class="alert alert-secondary">
                            پست های مرتبط با کلمه [<?= $_GET['search'] ?> ]
                        </div>
                        <?php if ($posts->rowCount() == 0): ?>
                            <div class="alert alert-danger">
                                مقاله مورد نظر پیدا نشد !!!!
                            </div>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach ($posts as $post):
                                    $categorie_id = $post['category_id'];
                                    $categoriy = $db->query("SELECT * FROM categories WHERE id = $categorie_id")->fetch();
                                ?>

                                    <div class="col-sm-6">
                                        <div class="card">
                                            <img
                                                src="./uploads/posts/<?= $post['image'] ?>"
                                                class="card-img-top"
                                                alt="post-image" />
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="card-title fw-bold"><?= $post['title'] ?></h5>
                                                    <div>
                                                        <span class="badge text-bg-secondary"><?= $categoriy['title'] ?></span>
                                                    </div>
                                                </div>
                                                <p class="card-text text-secondary pt-3">
                                                    <?= substr($post['body'], 0, 900) . "..." ?>
                                                </p>
                                                <div
                                                    class="d-flex justify-content-between align-items-center">
                                                    <a href="single.php?post=<?= $post['id'] ?>" class="btn btn-sm btn-dark">مشاهده</a>

                                                    <p class="fs-7 mb-0">نویسنده : <?= $post['author'] ?> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                            </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <?php
            include "./include/layout/sidebar.php";
            ?>
        </div>
    </section>
</main>

<!-- Footer Section -->
<?php
include "./include/layout/footer.php";
?>