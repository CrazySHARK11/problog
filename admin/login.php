<?php
session_start();
require_once '../config/database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  if (empty($username) || empty($password)) {
    $errors[] = 'Username and Password are required';
  } else {
    try {
      $sql = "SELECT * FROM admins WHERE email = :email LIMIT 1";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([':email' => $email]);
      $admin = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: ./");
        exit;
      } else {
        $errors[] = "Invalid username or password";
      }
    } catch (PDOException $e) {
      $errors[] = 'Database error: ' . $e->getMessage();
    }
  }
}

?>

<?php include "./admincomponents/header.php" ?>
<section style="background-color: #f7f7f7;">

  <div class="container d-flex flex-column gap-2 justify-content-center align-items-center" style="min-height: 100vh; ">
    <h2 class="fs-1" style="color: #2e384d;">Admin Login</h2>

    <?php if (!empty($errors)): ?>
      <ul>
        <?php foreach ($errors as $error): ?>
          <div class="alert d-flex align-items-center text-danger alert-danger alert-dismissible fade show rounded-pill" role="alert" style="background-color: transparent; border: none;">
            <?php echo $error ?>
            <button type="button" class="text-dark btn p-0" data-bs-dismiss="alert" aria-label="Close">
              <i class="text-danger bi bi-x fs-4"></i>
            </button>
          </div>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form action="login.php" method="post" class="d-flex mt-3 flex-column align-items-center gap-3" style="max-width: 500px;">

      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" placeholder="" id="floatingInputemail" style="min-width: 450px; box-shadow: none; border-radius: 0;">
        <label for="floatingInputemail">Email address</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" name="password" placeholder="" class="form-control form-control-lg" id="floatingInputpassword" style="min-width: 450px; box-shadow: none; border-radius: 0;">
        <label for="floatingInputpassword">Password</label>
      </div>

      <input type="submit" value="Login" style="background-color: #2e384d; border: 0;" class="btn px-5 py-2 rounded-0 btn-primary">
    </form>
  </div>
</section>
<?php include "./admincomponents/footer.php" ?>