<?php
session_start();
require_once '../../config/database.php';
$errors = [];

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

try {
  $sql = "SELECT * FROM testimonials";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $testimonials = $stmt->fetchAll();
} catch (PDOException $e) {
  $errors[] = 'Failed to retrieve testimonials: ' . $e->getMessage();
}
?>

<?php $baseurlpath = "../";
include "../admincomponents/header.php"; ?>
<?php include "../admincomponents/navbar.php"; ?>

<section class="container">

  <div class="header d-flex align-items-center justify-content-between mb-5">
    <div class="left">
      <h1 class="my-3 fw-bold" style="color: #2e384d;">Add Testimonials </h1>
      <p class="fs-5" style="color: #7c7c7c;">Testimonials </p>
    </div>

    <div class="right d-flex gap-3">
      <a href="./create_testimonial.php" style="background-color: #639b65; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary "><i class="me-2 bi bi-plus-circle"></i> Create</a>
      <a href="../" style="background-color: #2e384d; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary "><i class="me-2 bi bi-house"></i>Dashboard</a>
    </div>
  </div>

  <table class="table">
    <tr>
      <th class="fw-medium">ID</th>
      <th class="fw-medium">Name</th>
      <th class="fw-medium">Position</th>
      <th class="fw-medium">Content</th>
      <th class="fw-medium">Actions</th>
    </tr>
    <?php if($testimonials): ?>
    <?php foreach ($testimonials as $testimonial): ?>
      <tr>
        <td><?php echo $testimonial['id']; ?></td>
        <td><?php echo $testimonial['name']; ?></td>
        <td><?php echo $testimonial['position']; ?></td>
        <td><p style="max-width:400px;" class="m-0 text-truncate"><?php echo $testimonial['content']; ?></p></td>
        <td>
          <a class="fw-medium text-decoration-none" style="color: darkred;" href="deletetestimonials.php?id=<?php echo $testimonial['id']; ?>" onclick="return confirm('Are you sure you want to delete this testimonial?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php else: ?>
      <td colspan="5" style="padding:1em 0 ; background-color: lightyellow;" align="center">NO TESTIMONIAL HERE</td>
    <?php endif; ?>
  </table>


</section>

<?php include "../admincomponents/footer.php"; ?>