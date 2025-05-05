<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

$errors = [];

if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: manageusers.php");
  exit;
}

$userId = $_GET['id'];

try {
  $sql = "SELECT * FROM users WHERE id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':id' => $userId
  ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $errors[] = 'Error fetching user data : ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? ''; // New password field


  if (empty($username)) $errors[] = "Username is required.";
  if (empty($email)) $errors[] = "Email is required.";


  if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $profilepicture = $_FILES['profile_picture'];
    $targetDir = "../../uploads/";
    $fileExtension = pathinfo($profilepicture['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $fileExtension;
    $targetFile = $targetDir . $filename;

    //  Move the file
    if (move_uploaded_file($profilepicture['tmp_name'], $targetFile)) {

      if (!empty($user['profile_picture']) && file_exists($targetDir . $user['profile_picture'])) {
        unlink($targetDir . $user['profile_picture']);
      }

      $user['profile_picture'] = $filename;
    } else {
      $errors[] = "Failed to upload profile picture";
    }
  }

  // If no errors, update the database
  if (empty($errors)) {
    try {
      $sql = "UPDATE users SET username = :username, email = :email, profile_picture = :profile_picture"
        . (!empty($password) ? ", password = :password" : "") . " WHERE id = :id";


      $stmt = $pdo->prepare($sql);
      $params = [
        ':username' => $username,
        ':email' => $email,
        ':profile_picture' => $user['profile_picture'],
        ':id' => $userId
      ];

      if (!empty($password)) {
        $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
      }

      $stmt->execute($params);
      header("Location: manageusers.php");
      exit;
    } catch (PDOException $e) {
      $errors[] = 'Error updating user: ' . $e->getMessage();
    }
  }
}

?>
<?php $baseurlpath = "../";
include "../admincomponents/header.php"; ?>
<?php include "../admincomponents/navbar.php"; ?>

<section class="container">
  <h1 class="my-3 fw-bold" style="color: #2e384d;">Edit The User</h1>
  <p class="fs-5 mb-5" style="color: #7c7c7c;">Edit the details of <?php echo htmlspecialchars($user['username']); ?></p>

  <?php if (!empty($errors)): ?>
    <div>
      <?php foreach ($errors as $error): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="edit_user.php?id=<?php echo $userId ?>" method="POST" class="d-flex flex-column gap-4" enctype="multipart/form-data" style="max-width: 500px;">
    <div>
      <label for="username">Username:</label>
      <input type="text" id="username" class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
    </div>

    <div>
      <label for="email">Email:</label><br>
      <input type="email" id="email" class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
    </div>

    <div>
      <label for="password">New Password (leave blank to keep current):</label><br>
      <input type="password" id="password" class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" name="password">
    </div>

    <div>
      <label for="profile_picture">Profile Picture:</label><br>
      <?php if (!empty($user['profile_picture'])): ?>
        <img src="../../uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" width="100" alt="Profile Picture">
      <?php endif; ?>
      <input class="form-control subs form-control-lg" type="file" id="profile_picture" name="profile_picture">
    </div>

    <input type="submit" class="btn btn-primary" value="Update User">
  </form>
</section>

<?php include "../admincomponents/footer.php"; ?>