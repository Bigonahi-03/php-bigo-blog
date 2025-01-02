<?php
// Request categories
$query = "SELECT * FROM categories";
$categories = $db->query($query);


?>

<!-- Sidebar Section -->
<div class="col-lg-4">
    <!-- Sesrch Section -->
    <div class="card">
        <div class="card-body">
            <p class="fw-bold fs-6">جستجو در وبلاگ</p>
            <form action="search.php" method="GET">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search" placeholder="جستجو ..." />
                    <button class="btn btn-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="card mt-4">
        <div class="fw-bold fs-6 card-header">دسته بندی ها</div>
        <ul class="list-group list-group-flush border-bottom-0 p-0">
            <li class="list-group-item ">
                <a class="link-body-emphasis text-decoration-none  <?= (!isset($_GET['category'])) ? "" : "" ?>" href="index.php">همه </a>
            </li>
        </ul>
        <ul class="list-group list-group-flush p-0"> <?php foreach ($categories as $category): ?>
                    <li class="list-group-item ">
                        <a class="link-body-emphasis text-decoration-none  <?= (isset($_GET['category']) && $category['id'] == $_GET['category']) ? "" : "" ?>  " href="index.php?category=<?= $category['id'] ?>"><?= $category['title'] ?></a>
                    </li>
            <?php endforeach ?>
        </ul>
    </div>


    <!-- Subscribue Section -->
    <div class="card mt-4">
        <div class="card-body">
            <p class="fw-bold fs-6">عضویت در خبرنامه</p>

            <?php
            // Subscriber registration
            $invalidInputName = "";
            $invalidInptEmail = "";
            $name = "";
            $message = "";

            if (isset($_POST['subscribe'])) {
                if (empty(trim($_POST['name']))) {
                    $invalidInputName = "فیلد نام الزامی است!";
                }
                if (empty(trim($_POST['email']))) {
                    $invalidInptEmail = "فیلد ایمیل الزامی است!";
                } else {
                    $name = htmlspecialchars($_POST['name']);
                    $email = htmlspecialchars($_POST['email']) ;
                    $subscribersInsert = $db->prepare("INSERT INTO subscribers (name, email) VALUE (:name, :email )");
                    $subscribersInsert->execute(['name' => $name, 'email' => $email]);
                    $message = "عضویت شما موفقیت امیز بود.";
                }
            }

            ?>

            <div class="text-success"><?= $name, " ", $message ?></div>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">نام</label>
                    <input type="text" name="name" class="form-control" />
                    <div class="form-text text-danger"><?= $invalidInputName ?></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">ایمیل</label>
                    <input type="email" name="email" class="form-control" />
                    <div class="form-text text-danger"><?= $invalidInptEmail ?></div>

                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="subscribe" class="btn btn-secondary">
                        ارسال
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- About Section -->
    <div class="card mt-4">
        <div class="card-body">
            <p class="fw-bold fs-6">درباره ما</p>
            <p class="text-justify">
                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و
                با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه
                و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی
                تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                کاربردی می باشد.
            </p>
        </div>
    </div>
</div>