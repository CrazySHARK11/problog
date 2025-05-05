<?php
session_start();
require_once "./config/database.php";

if (isset($_SESSION['user_id'])) {

  $user_id = $_SESSION['user_id'];
  $user = null;

  // Fetch current user data
  try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([
      ":id" => $user_id
    ]);
    $user = $stmt->fetch();
  } catch (PDOException $e) {
    $errors[] = 'Failed to retrieve user data: ' . $e->getMessage();
  }
}

$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Populating blog posts 
$sql = "SELECT posts.title, posts.id, posts.main_image, posts.description, posts.published_date, categories.name AS category_name, 
          authors.name AS author_name, 
          authors.titles AS author_title,
          authors.profile_picture as auth_prof_pic
          FROM posts 
          JOIN categories ON posts.category_id = categories.id
          JOIN authors ON posts.author_id = authors.id 
          WHERE 1=1";

$params = [];

$inisearchterm = $_GET['search'] ?? null;

if (isset($inisearchterm)) {
  $searchTerm = '%' . $inisearchterm . '%';
  $sql .= " AND (posts.title LIKE :search OR posts.description LIKE :search)";
  $params[':search'] = $searchTerm;
}

if (!empty($_GET['category'])) {
  $sql .= " AND categories.id = :category";
  $params[':category'] = $_GET['category'];
}

$sql .= " ORDER BY posts.published_date DESC";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $errors[] = "Failed to get posts" . $e->getMessage();
}
// Populating blog posts

?>


<?php
$pagetitle = "All Blogs";
$basePath =  './';
include './components/header.php' ?>
<?php include './components/navbar.php' ?>

<!-- Search header -->
<div class="search-header mt-5 " style=" background-color: #fff;">
  <div class="container">

    <form action="blogs.php" method="GET" class="d-flex align-items-center justify-content-start">
      <div class="input-group">
        <input type="search" name="search" placeholder="Search the blogs" class="form-control form-control-lg" style="max-width: 400px; box-shadow: none; border-radius: 0;">
        <button class="btn btn-primary px-4" type="submit" style="border-radius: 0; box-shadow: none;  border: 0; background-color: #639b65;" id="button-addon2"><i class="bi bi-search"></i></button>

      </div>

      <select class="form-select select form-select-lg" name="category" style="max-width: 400px; box-shadow: none; border-radius: 0;" aria-label="Large select example">
        <option selected value="">All</option>
        <?php
        // Fetch categories for the dropdown
        try {
          $categoryStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
          $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
          foreach ($categories as $category) {
            $selected = isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'selected' : '';
            echo "<option value='{$category['id']}' $selected>" . htmlspecialchars($category['name']) . "</option>";
          }
        } catch (PDOException $e) {
          echo "<option disabled>Error loading categories</option>";
        }
        ?>
      </select>

    </form>
  </div>
</div>

<section class="container my-5 d-flex flex-column gap-4">
  <?php if (empty($posts)): ?>
    <p>No blog posts found.</p>
  <?php else: ?>
    <?php foreach ($posts as $post): ?>
      <div class="blogcard d-flex flex-lg-row flex-column gap-4 align-items-center ">
        <img src="<?php echo './uploads/' . htmlspecialchars($post['main_image']) ?>" width="270" height="280" class="rounded object-fit-cover float-start" alt="">
        <div class="card-content d-flex flex-column gap-2 justify-content-center justify-content-lg-start align-items-center align-items-lg-start">
          <span class="badge d-inline" style="color: #2e384d; background-color: #ccfcce;"><?php echo htmlspecialchars($post['category_name']) ?></span>
          <h2 class="m-0 text-center line-clamp-two text-lg-start" style="color: #2e384d;"><?php echo htmlspecialchars($post['title']) ?></h2>
          <p class="m-0 text-center line-clamp-two text-lg-start"><?php echo htmlspecialchars($post['description']) ?></p>
          <div class="author d-flex align-items-center gap-2">
            <img width="40" height="40" class="rounded-circle object-fit-cover float-start" src="<?php echo './uploads/' . htmlspecialchars($post['auth_prof_pic']) ?>" alt="">
            <div class="auth-details d-flex flex-column my-3">
              <h4 class="m-0 author-name" style="font-size: 1em;"><?php echo htmlspecialchars($post['author_name']) ?></h4>
              <p class="m-0" style="font-size: .8em; color: color-mix(in srgb, #2e384d 90%, #fff 50%);">
                <?php
                $title =  $post['author_title'];
                $parts = explode(" | ", $title);
                $firstTitle = $parts[0];

                echo htmlspecialchars("$firstTitle");
                ?>
              </p>
            </div>
          </div>
          <a class="fw-bold text-decoration-none" style="color: #639b65;" href="post.php?id=<?php echo htmlspecialchars($post['id']) ?>">READ MORE <i class="bi bi-chevron-double-right"></i> </a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</section>
<?php include './components/footer.php' ?>