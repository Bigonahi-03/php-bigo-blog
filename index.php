<?php
// header Section
include "./include/layout/header.php"
?>

<?php
// Receive posts
if (isset($_GET['category'])) {
  $categoryId = $_GET['category'];
  $posts = $db->prepare("SELECT * FROM posts WHERE category_id = :id ORDER BY id DESC");
  $posts->execute(['id' => $categoryId]);
} else {
  $posts = $db->query("SELECT * FROM posts ORDER BY id DESC");
}

?>

<main>
  <?php
  // slider Section
  include "./include/layout/slider.php"
  ?>

  <!-- Content Section -->
  <section class="mt-4">
    <div class="row">

      <!-- Posts Content -->
      <div class="col-lg-8">
        <div class="row g-2">

          <!-- Placing posts -->
          <?php if ($posts->rowCount() > 0): ?>
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
                      <?= mb_substr($post['body'], 0, 500) . "..." ?>
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
          <?php else : ?>
            <div class="alert alert-danger"> مقاله ای برای نمایش وجود ندارد. </div>
          <?php endif ?>
        </div>
      </div>

      <?php
      //  Slider Section
      include "./include/layout/sidebar.php"
      ?>
    </div>
  </section>
</main>


<?php
// Footer Section 
include "./include/layout/footer.php"

?>