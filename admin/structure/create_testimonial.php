  <?php
  session_start();
  require_once '../../config/database.php';
  $errors = [];

  if (!isset($_SESSION['admin_id'])) {
      header("Location: login.php");
      exit;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = trim($_POST['name']);
      $position = trim($_POST['position']);
      $content = trim($_POST['content']);
      $profile_picture = $_FILES['profile_picture'] ?? null;

      if ($profile_picture && $profile_picture['error'] === UPLOAD_ERR_OK) {
        $file_ext = strtolower(pathinfo($profile_picture['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if (!in_array($file_ext, $allowed_ext)) {
            $errors[] = "Only JPG, PNG and JPEG";
        }

        if ($profile_picture['size'] > 2 * 1024 * 1024) {
            $errors[] = 'File size must be under 2MB.';
        }

        if (empty($errors)) {
            $target_dir = '../../uploads/';
            $new_filename = uniqid()  . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;

            if (!move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
                $errors[] = 'Failed to upload profile picture.';
            }
        }
      }

      if (empty($name) || empty($position) || empty($content)) {
          $errors[] = "All fields are required";
      }

      if (empty($errors)) {
          try {
              $sql = "INSERT INTO testimonials (name, position, content, profilepic) VALUES (:name, :position, :content, :profilepic)";
              $stmt = $pdo->prepare($sql);
              $stmt->execute([
                  ':name' =>  $name,
                  ':position' =>  $position,
                  ':content' =>  $content,
                  ':profilepic' => $new_filename
              ]);
              header("Location: alltestimonials.php");
              exit;
          } catch (PDOException $e) {
              $errors[] = "Failed to retrieve testimonials" . $e->getMessage();
          }
      }
  }
  ?>

  <?php $baseurlpath = "../";
  include "../admincomponents/header.php"; ?>
  <?php include "../admincomponents/navbar.php"; ?>

  <section class="container">

      <!-- Header -->
      <div class="header d-flex align-items-center justify-content-between mb-5">
          <div class="left">
              <h1 class="my-3 fw-bold" style="color: #2e384d;">Create Testimonials </h1>
          </div>

          <div class="right d-flex gap-3">
              <a href="./alltestimonials.php" style="background-color: #639b65; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary "> All testimonials</a>
              <a href="../" style="background-color: #2e384d; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary "><i class="me-2 bi bi-house"></i>Dashboard</a>
          </div>
      </div>
      <!-- Header -->

      <?php if (!empty($errors)): ?>
      <ul>
        <?php foreach ($errors as $error): ?>
          <div class="alert text-danger alert-danger alert-dismissible fade show rounded-pill" role="alert" style="background-color: transparent; border: none;">
            <strong>Errors</strong> <?php echo $error ?>
            <button type="button" class="text-dark btn" data-bs-dismiss="alert" aria-label="Close">
              <i class="bi bi-x-circle fs-4"></i>
            </button>
          </div>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

      <form action="create_testimonial.php" class="d-flex mx-auto w-75 flex-column align-items-center gap-3" method="post" enctype="multipart/form-data">
          <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="text" name="name" placeholder="Name" required>
          <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="text" name="position" placeholder="Position">
          <textarea rows="10" class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" name="content" placeholder="Testimonial content" required></textarea>
          <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="file" name="profile_picture" id="">
          <button style="background-color: #2e384d; border: 0;" class="btn px-5 py-2 rounded-0 btn-primary " type="submit">Add Testimonial</button>
      </form>



      <?php include "../admincomponents/footer.php"; ?>