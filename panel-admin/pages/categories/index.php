<?php include "../../include/layout/header.php"; ?>

<?php

$categories = $db->query("SELECT * FROM categories ORDER BY id DESC");
// Fetch all categories from the database and order them by id in descending order
$invalidInptTitle = "";
if (isset($_POST['creatCategory'])) {
    $title = $_POST['title'];
    // Check if the title is not empty after trimming whitespace
    if (!empty(trim($title))) {
        $insert = $db->prepare("INSERT INTO categories (title) VALUE (:title)");
        $insert->execute(['title' => $title]);
        header("Location:./index.php");
    } else {
        $invalidInptTitle = "فیلد عنوان الزامی است!";
    }
}

if (isset($_POST['editCategory'])) {
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $categoryId = $_GET['id'];
        $action = $_GET['action'];

        switch ($action) {
            case 'update':
                    $title = $_POST['title'];
                    // Update the category title in the database
                    $query = $db->prepare("UPDATE categories SET title = :title WHERE id = :id");
                    $query->execute(['id' => $categoryId, 'title' => $title]);
                    header("Location: ./index.php");
                    break;
                
                

            case 'delete':
                // Delete the category from the database
                $query = $db->prepare("DELETE FROM categories WHERE id = :id");
                $query->execute(['id' => $categoryId]);
                break;
        }

        header("Location:./index.php");
        exit();
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
                <h1 class="fs-3 fw-bold">دسته بندی ها</h1>
            </div>

            <!-- Categories -->
            <?php if ($categories->rowCount() > 0): ?>
                <div class="mt-4">
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle overflow-hidden">
                            <thead>
                                <tr>
                                    <th colspan="3">
                                        <form class="row align-items-center" method="post">
                                            <div class="col-auto">
                                                <label class="form-label">ایجاد دسته بندی جدید</label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" name="title" class="form-control" placeholder="عنوان دسته بندی" />
                                                <div class="form-text text-danger"><?= $invalidInptTitle ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" name="creatCategory" class="btn btn-dark">ایجاد</button>
                                            </div>
                                        </form>
                                    </th>
                                </tr>
                                <tr>
                                    <th>id</th>
                                    <th>نام</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?= $category['id'] ?></td>
                                        <td><?= htmlspecialchars($category['title']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-dark edit-btn" data-bs-toggle="collapse" data-bs-target="#collapseEdit<?= $category['id'] ?>" role="button" aria-expanded="false" aria-controls="collapseEdit<?= $category['id'] ?>" onclick="changeUrl(<?= $category['id'] ?>)">
                                                ویرایش
                                            </button>
                                            <a href="./index.php?action=delete&id=<?= $category['id'] ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                            <div class="collapse" id="collapseEdit<?= $category['id'] ?>">
                                                <div class="card card-body mt-2">
                                                    <form class="d-flex flex-wrap gap-2 align-items-center" method="post">
                                                        <label>عنوان دسته بندی</label>
                                                        <div>
                                                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($category['title']) ?>" />
                                                        </div>
                                                        <button type="submit" name="editCategory" class="btn btn-success">ذخیره</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>


                    </div>
                </div>
            <?php else : ?>
                <div class="alert alert-danger">دسته بندی یافت نشد. !!!</div>
            <?php endif ?>
        </main>
    </div>
</div>




<?php include "../../include/layout/footer.php"; ?>