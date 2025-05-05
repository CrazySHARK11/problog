<?php
session_start();
require_once '../../config/database.php';
$errors = [];

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

try {
  $sql = "SELECT * FROM testimonials ORDER BY display_order ASC";
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
      <h1 class="my-3 fw-bold" style="color: #2e384d;">Manage Your Website Structure</h1>
      <p class="fs-5" style="color: #7c7c7c;">Structure of the website</p>
    </div>
    <div class="right d-flex gap-3">
      <a href="../" style="background-color: #2e384d; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary "><i class="me-2 bi bi-house"></i>Dashboard</a>
    </div>
  </div>

  <div class="d-flex gap-4">
      <div class="manage_card d-flex gap-4 py-2 px-4 align-items-center rounded-3" style="border: 4px solid #A5D6A7;">
        <p class="fw-medium fs-3 m-0" style="color: #212938;">Testimonials</p>
        <a href="alltestimonials.php"><i style="color:#212938;" class="bi fs-3 bi-arrow-right-circle-fill"></i></a>
      </div>
   
      <div class="manage_card d-flex gap-4 py-2 px-4 align-items-center rounded-3" style="border: 4px solid #A5D6A7;">
        <p class="fw-medium fs-3 m-0" style="color: #212938;">Contact</p>
        <a href="./contact.php"><i style="color:#212938;" class="bi fs-3 bi-arrow-right-circle-fill"></i></a>
      </div>
   
      <div class="manage_card d-flex gap-4 py-2 px-4 align-items-center rounded-3" style="border: 4px solid #A5D6A7;">
        <p class="fw-medium fs-3 m-0" style="color: #212938;">Tagline</p>
        <a href="./tagline.php"><i style="color:#212938;" class="bi fs-3 bi-arrow-right-circle-fill"></i></a>
      </div>


  </div>

</section>

<?php include "../admincomponents/footer.php"; ?>