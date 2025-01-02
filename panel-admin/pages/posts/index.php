<?php include "../../include/layout/header.php"; ?>
<?php


$messageCreate = "";


if (isset($_SESSION['messageCreate'])) {
    $messageCreate = $_SESSION['messageCreate'];
    unset($_SESSION['messageCreate']); 
}

$posts = $db->query("SELECT * FROM posts ORDER BY id DESC");

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete = $db->prepare("DELETE FROM posts WHERE id = :id");
    $delete->execute(['id' => $id]);

    header("location:index.php");
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include "../../include/layout/sidebar.php"; ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Show message -->
            <?php if (!empty($messageCreate)): ?>
                <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex flex-row ">
                        <button type="button" class=" btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        <div class="toast-body border-start">
                            <?= htmlspecialchars($messageCreate) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">مقالات</h1>

                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="./create.php" class="btn btn-sm btn-dark">ایجاد مقاله</a>
                </div>
            </div>

            <!-- posts -->
            <?php if ($posts->rowCount() > 0): ?>
                <div class="mt-4">
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>عنوان</th>
                                    <th>نویسنده</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <?php foreach ($posts as $post): ?>
                                <tbody>
                                    <tr>
                                        <th><?= htmlspecialchars($post['id']) ?></th>
                                        <td><?= htmlspecialchars($post['title']) ?></td>
                                        <td><?= htmlspecialchars($post['author']) ?></td>
                                        <td>
                                            <a href="./edit.php?id=<?= htmlspecialchars($post['id']) ?>" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                            <a href="?action=delete&id=<?= htmlspecialchars($post['id']) ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                        </td>
                                    </tr>
                                </tbody>
                            <?php endforeach ?>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger"> مقاله‌ای یافت نشد. !!! </div>
            <?php endif ?>
        </main>
    </div>
</div>



<?php include "../../include/layout/footer.php"; ?>