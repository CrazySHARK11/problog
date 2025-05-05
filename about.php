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

try {
  $sql = "SELECT * FROM authors WHERE id = 1";
  $stmt = $pdo->query($sql);
  $author = $stmt->fetch();
} catch (PDOException $e) {
  $errors[] = 'Failed to retrieve user data: ' . $e->getMessage();
}

?>

<?php
$pagetitle = "About us";
$basePath =  './';
include './components/header.php' ?>
<?php include './components/navbar.php' ?>
  <div class="container" style="max-width: 1280px; margin-top: 2rem; background-image: url('./assets/img/New\ Project.png');">

    <!-- About the Author Section -->
    <div style="text-align: center; padding: 2rem 0; border-bottom: 1px solid #e1e1e1;">
      <figure class="position-relative">
        <img class="img-thumbnail object-fit-contain" src="./uploads/<?php echo htmlspecialchars($author['profile_picture']) ?>" alt="Author Image" width="350" style=" box-shadow: 0 0 0 10px #607f61;  border-radius: 50%; margin-bottom: 1rem;">
      </figure>
      <h2 style="font-size: 2rem; color: #2e384d; font-weight: 700;"><?php echo htmlspecialchars($author['name']) ?></h2>
      <p class="fs-6" style="color: #7c7c7c;"><?php echo htmlspecialchars($author['titles']) ?></p>
      <div class="d-flex gap-3 justify-content-center my-5 align-items-center">
        <span class="d-flex align-items-center gap-2 fw-medium"><i class="bi bi-book-half fs-4" style="color: #a5d6a7 ;"></i>
          <?php echo htmlspecialchars($author['education_university']) ?></span>
        <span style="color: #7c7c7c;">|</span>
        <span class="d-flex align-items-center gap-2 fw-medium"><i class="bi bi-journals fs-4" style="color: #a5d6a7 ;"></i>
          Professor at <?php echo htmlspecialchars($author['professor_university']) ?>
        </span>
        <span style="color: #7c7c7c;">|</span>
        <span class="d-flex align-items-center gap-2 fw-medium"><i class="bi bi-book-half fs-4" style="color: #a5d6a7 ;"></i>
          <?php echo htmlspecialchars($author['bestseller_count']) ?> Best sellers
        </span>
      </div>
      <p style="font-size: 1.1rem; color: #495057; line-height: 1.8; margin-top: 1rem;">
        <?php echo htmlspecialchars($author['description']) ?>
      </p>
    </div>

<!-- What the Blog Provides Section -->
<div style="padding: 2rem 0;">
  <h3 class="text-center text-md-start" style="font-size: 1.8rem; color: #2e384d; font-weight: 700;">What This Blog Provides</h3>
  <p class="text-center text-md-start" style="font-size: 1.1rem; color: #495057; line-height: 1.8; margin-top: 1rem;">
    Our blog is designed to be a comprehensive resource for anyone interested in [Blog's Niche/Topic]. Here, you’ll find insightful articles, how-to guides, expert advice, and the latest trends. Whether you're a beginner looking to get started or an experienced enthusiast aiming to refine your skills, this blog offers a range of content to inspire and educate you.
  </p>
  <p class="text-center text-md-start" style="font-size: 1.1rem; color: #495057; line-height: 1.8;">
    We cover topics including [List of Topics or Subtopics Covered], providing in-depth discussions, practical applications, and answers to common questions. Thank you for being part of our community, and we hope our content brings value to your life.
  </p>
</div>

</div>

<?php include './components/footer.php' ?>