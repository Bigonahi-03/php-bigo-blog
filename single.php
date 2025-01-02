<!-- Header Section -->
<?php include "./include/layout/header.php";?>

<?php
// Get the desired article
if (isset($_GET['post'])) {
    $postId =htmlspecialchars($_GET['post']) ;
    $post = $db->prepare("SELECT * FROM posts WHERE id = :id");
    $post->execute(['id' => $postId]);
    $post = $post->fetch();
}
?>

<main>
    <!-- Content -->
    <section class="mt-4">
        <div class="row">
            <!-- Posts & Comments Content -->
            <?php if (empty($post)): ?>
                <div class="alert alert-danger">
                    مقاله مورد نظر پیدا نشد !!!!
                </div>
            <?php else : ?>
                <div class="col-lg-8">
                    <div class="row justify-content-center">
                        <?php
                        $categorie_id = $post['category_id'];
                        $category = $db->query("SELECT * FROM categories WHERE id = $categorie_id")->fetch();
                        ?>
                        <!-- Post Section -->
                        <div class="col">
                            <div class="card">
                                <img src="./uploads/posts/<?= $post['image'] ?>" class="card-img-top" alt="post-image" />
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title fw-bold">
                                            <?= $post['title'] ?>
                                        </h5>
                                        <div>
                                            <span class="badge text-bg-secondary"><?= $category['title'] ?></span>
                                        </div>
                                    </div>
                                    <p class="card-text text-secondary text-justify pt-3">
                                        <?= $post['body'] ?>
                                    </p>
                                    <div>
                                        <p class="fs-6 mt-5 mb-0">
                                            نویسنده : <?= $post['author'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-4" />

                        <!-- Comment Section -->
                         <?php
                            $nameValidationMessage = "";
                            $commentValidationMessage = "";
                            $message = "";
                            if(isset($_POST['commentSubmit'])){
                                $name = $_POST['name'];
                                $comment = $_POST['comment'];
                                if(empty($name)){
                                    $nameValidationMessage = "فیلد نام الزامی است.";
                                }elseif(empty($comment)){
                                    $commentValidationMessage = "فیلد متن کامنت الزامی است.";
                                }else {
                                    
                                    $comments = $db->prepare(" INSERT INTO comments (name, comment, post_id) value (:name, :comment, :post_id)");
                                    $comments->execute(['name' => $name,'comment' => $comment, 'post_id' => $postId]);

                                    $message = "کامنت شما با موفقیت ثبت شد، پس از تایید نمایش داده می شود.";
                                }
                            }
                              

                               
                         ?>
                        <div class="col">
                            <!-- Comment Form -->
                            <div class="card">
                                <div class="card-body">
                                    <p class="fw-bold fs-5">
                                        ارسال کامنت
                                    </p>
                                    <div class="form-text text-success"><?= $message ?></div>

                                    <form method="post" >
                                        <div class="mb-3">
                                            <label class="form-label">نام</label>
                                            <input type="text" name="name" class="form-control" />
                                            <div class="form-text text-danger"><?= $nameValidationMessage ?></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">متن کامنت</label>
                                            <textarea class="form-control" name="comment" rows="3"></textarea>
                                            <div class="form-text text-danger"><?= $commentValidationMessage ?></div>
                                        </div>
                                        <button type="submit" name="commentSubmit" class="btn btn-dark">
                                            ارسال
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <hr class="mt-4" />
                            <!-- Comment Content -->
                            <?php
                            $comments = $db->prepare("SELECT * FROM comments WHERE post_id = :id AND status = 1 ");
                            $comments->execute(['id' => $postId]);

                            ?>
                            <p class="fw-bold fs-6">تعداد کامنت : <?= $comments->rowCount() ?></p>

                            <?php if ($comments->rowCount() == 0): ?>
                                <div class="alert alert-danger">
                                    برای این مقاله کامنتی ثبت نشده است.
                                </div>

                            <?php else : ?>
                                <?php foreach ($comments as $comment): ?>
                                    <div class="card bg-light-subtle mb-3">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <img src="./assets/images/profile.png" width="45" height="45" alt="user-profle" />
                                                <h5 class="card-title me-2 mb-0">
                                                    <?= $comment['name'] ?>
                                                </h5>
                                            </div>
                                            <p class="card-text pt-3 pr-3">
                                                <?= $comment['comment'] ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <!-- Sidebar Section -->
            <?php
            include "./include/layout/sidebar.php";
            ?>
        </div>
    </section>
</main>

<!-- Footer -->
<?php
include "./include/layout/footer.php";
?>