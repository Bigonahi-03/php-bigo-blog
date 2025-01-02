<?php include "../../include/layout/header.php"; ?>

<?php

if (isset($_GET['id'])) {
    $postId = htmlspecialchars($_GET['id']);
    $post = $db->prepare("SELECT * FROM posts WHERE id = :id ");
    $post->execute(['id' => $postId]);
    $post = $post->fetch();

    $categories = $db->query("SELECT * FROM categories");

    $invalidInptTitle = "";
    $invalidInptAuthor = "";
    $invalidInptText = "";

    // Field validation
    if (isset($_POST['editPost'])) {

        if (empty(trim($_POST['title']))) {
            $invalidInptTitle = "فیلد عنوان الزامی است!";
        }

        if (empty(trim($_POST['author']))) {
            $invalidInptAuthor = "فیلد نویسنده الزامی است!";
        }

        if (empty(trim($_POST['body']))) {

            $invalidInptText = "فیلد متن الزامی است!";
        }
        if (!empty(trim($_POST['title'])) && !empty(trim($_POST['author'])) && !empty(trim($_POST['body']))) {
            $title = htmlspecialchars($_POST['title']) ;
            $author = htmlspecialchars($_POST['author']);
            $body = htmlspecialchars($_POST['body']) ;
            $categoryId = htmlspecialchars($_POST['categoryId']) ;

            // Check if new image has been loaded
            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image'];
                $tempName = $image["tmp_name"];
                $imageName = time() . "_" . $image["name"];
                move_uploaded_file($tempName, "../../../uploads/posts/$imageName");
                // Update with new image
                $update = $db->prepare("UPDATE posts SET title = :title, author = :author, category_id = :category_id, image = :image, body = :body WHERE id = :id");
                $update->execute(['title' => $title, 'author' => $author, 'category_id' => $categoryId, 'image' => $imageName, 'body' => $body, 'id' => $id]);
            } else {
                // Update without changing the image
                $update = $db->prepare("UPDATE posts SET title = :title, author = :author, category_id = :category_id, body = :body WHERE id = :id");
                $update->execute(['title' => $title, 'author' => $author, 'category_id' => $categoryId, 'body' => $body, 'id' => $id]);
            }

            // Save the message in the session
            $_SESSION['messageCreate'] = "مقاله با موفقیت ویرایش شد.";
            header('location:index.php'); // هدایت به صفحه index
            exit();
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include "../../include/layout/sidebar.php"; ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ویرایش مقاله</h1>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <form class="row g-4" method="post" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان مقاله</label>
                        <input type="text" name="title" class="form-control" value="<?= $post['title'] ?>" />
                        <div class="form-text text-danger"><?= $invalidInptTitle ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">نویسنده مقاله</label>
                        <input type="text" name="author" class="form-control" value="<?= $post['author'] ?>" />
                        <div class="form-text text-danger"><?= $invalidInptAuthor ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">دسته بندی مقاله</label>

                        <?php

                        if ($categories->rowCount() > 0): ?>
                            <select name="categoryId" class="form-select">
                                <?php foreach ($categories as $category): ?>
                                    <option <?= $category['id'] == $post['category_id'] ? 'selected' : '' ?> value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                                <?php endforeach ?>
                            </select>
                        <?php else: ?>
                            <div class="alert alert-danger"> دسته بندی ای یافت نشد. </div>
                        <?php endif ?>

                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر مقاله</label>
                        <input name="image" class="form-control" type="file" />

                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <img class="rounded" src="../../../uploads/posts/<?= $post['image'] ?>" width="300" />
                    </div>


                    <div class="col-12">
                        <label for="formFile" class="form-label">متن مقاله</label>
                        <textarea name="body" aria-valuetext="" class="form-control" rows="6"> <?= $post['body'] ?> </textarea>
                        <div class="form-text text-danger"><?= $invalidInptText ?></div>
                    </div>


                    <div class="col-12">
                        <button type="submit" name="editPost" class="btn btn-dark">
                            ویرایش
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include "../../include/layout/footer.php"; ?>