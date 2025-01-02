<?php include "../../include/layout/header.php"; ?>

<?php

$categories = $db->query("SELECT * FROM categories");

$invalidInptTitle = "";
$invalidInptAuthor = "";
$invalidInptImage = "";
$invalidInptText = "";

if (isset($_POST['createPost'])) {
    // Check inputs
    if (empty(trim($_POST['title']))) {
        $invalidInptTitle = "فیلد عنوان الزامی است!";
    }

    if (empty(trim($_POST['author']))) {
        $invalidInptAuthor = "فیلد نویسنده الزامی است!";
    }

    if (empty(trim($_FILES['image']['name']))) {
        $invalidInptImage = "فیلد تصویر الزامی است!";
    }

    if (empty(trim($_POST['body']))) {
        $invalidInptText = "فیلد متن الزامی است!";
    }

    // If all inputs were valid
    if (!empty(trim($_POST['title'])) && !empty(trim($_POST['author'])) && !empty(trim($_FILES['image']['name'])) && !empty(trim($_POST['body']))) {
        $title = htmlspecialchars($_POST['title']);
        $author = htmlspecialchars($_POST['author']);
        $image = $_FILES['image'];
        $body = htmlspecialchars($_POST['body']);
        $categoryId = htmlspecialchars($_POST['categoryId']);

        $tempName = $image["tmp_name"];
        $imageName = time() . "_" . $image["name"];

        if (move_uploaded_file($tempName, "../../../uploads/posts/$imageName")) {
            $insert = $db->prepare("INSERT INTO posts (title, author, category_id, image, body) VALUE (:title, :author,:category_id, :image, :body)");
            $insert->execute(['title' => $title, 'author' => $author, 'category_id' => $categoryId, 'image' => $imageName, 'body' => $body]);

            // Save the message in the session
            $_SESSION['messageCreate'] = "مقاله با موفقیت ایجاد شد.";
            header('location:index.php');
            exit();
        } else {
            echo "Upload Error";
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include "../../include/layout/sidebar.php"; ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ایجاد مقاله</h1>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <form class="row g-4" method="post" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان مقاله</label>
                        <input type="text" name="title" class="form-control" />
                        <div class="form-text text-danger"><?= $invalidInptTitle ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">نویسنده مقاله</label>
                        <input type="text" name="author" class="form-control" />
                        <div class="form-text text-danger"><?= $invalidInptAuthor ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">دسته بندی مقاله</label>
                        <?php if ($categories->rowCount() > 0): ?>
                            <select name="categoryId" class="form-select">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                                <?php endforeach ?>
                            </select>
                        <?php else: ?>
                            <div class="alert alert-danger"> دسته بندی ای یافت نشد. </div>
                        <?php endif ?>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر مقاله</label>
                        <input name="image" class="form-control" type="file" />
                        <div class="form-text text-danger"><?= $invalidInptImage ?></div>
                    </div>

                    <div class="col-12">
                        <label for="formFile" class="form-label">متن مقاله</label>
                        <textarea name="body" class="form-control" rows="6"></textarea>
                        <div class="form-text text-danger"><?= $invalidInptText ?></div>
                    </div>

                    <div class="col-12">
                        <button type="submit" name="createPost" class="btn btn-dark">ایجاد</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include "../../include/layout/footer.php"; ?>