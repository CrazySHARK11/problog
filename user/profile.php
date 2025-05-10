<?php
session_start();
require_once '../config/database.php';
$errors = [];

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = null;

// Fetch current user data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    $errors[] = 'Failed to retrieve user data: ' . $e->getMessage();
}

try {
    $sql = "SELECT comments.id, comments.comment_content, comments.created_at, posts.title AS post_title, posts.id AS post_id
    FROM comments 
    JOIN posts ON comments.post_id = posts.id 
    WHERE comments.user_id = :user_id 
    ORDER BY comments.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $user_comments = $stmt->fetchAll();
} catch (PDOException $e) {
    $errors[] = "Failed to retrieve Comments :" . $e->getMessage();
}

// Handle Profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $profile_picture = $_FILES['profile_picture'] ?? null;
    $new_filename = $user['profile_picture'];
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($email)) {
        $errors[] = 'Username and email are required.';
    }

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
            $target_dir = '../uploads/';
            $new_filename = uniqid()  . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;

            if ($user['profile_picture'] && file_exists($target_dir . $user['profile_picture'])) {
                unlink($target_dir . $user['profile_picture']);
            }

            if (!move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
                $errors[] = 'Failed to upload profile picture.';
            }
        }
    }

    if (!empty($new_password) || !empty($confirm_password)) {
        if ($new_password !== $confirm_password) {
            $errors[] = 'Passwords do not match.';
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, profile_picture = :profilepic WHERE id = :id");
            $stmt->execute([':username' => $username, ':email' => $email, ':profilepic' => $new_filename, ':id' => $user_id]);

            // Update password if provided
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                $stmt->execute([':password' => $hashed_password, ':id' => $user_id]);
            }

            header("Location: profile.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Failed to update profile: ' . $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment_id'])) {
    $delete_comment_id = $_POST['delete_comment_id'];

    try {
        $deleteSql = "DELETE FROM comments WHERE id = :comment_id AND user_id = :user_id";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->execute([':comment_id' => $delete_comment_id, ':user_id' => $user_id]);
        header("Location: profile.php");
        exit;
    } catch (PDOException $e) {
        $errors[] = "Failed to delete comment: " . $e->getMessage();
    }
}


?>

<?php $basePath = '../';
include '../components/header.php' ?>
</head>
<body>
<?php include '../components/navbar.php' ?>

 
    <!-- Top Image section -->
    <div class="backgroundgreen" style="background-size: cover; background-image: url('https://images.pexels.com/photos/1323550/pexels-photo-1323550.jpeg?cs=srgb&dl=pexels-8moments-1323550.jpg&fm=jpg'); padding-top: .1em;">
        <div class="container position-relative" style=" min-height: 25vh;">
            <img src="../uploads/<?php echo $user['profile_picture'] ?>" style="bottom: -4em;" alt="default profile picture" class="position-absolute object-fit-cover rounded-circle" width="200" height="200">
        </div>
    </div>
    <!-- Profile update section -->
    <div class="container" style="margin-top: 7em;">
        <div class="row">
            <div class="col-12 col-lg-5 p-0">

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

                <form method="post" enctype="multipart/form-data" action="profile.php" class="sticky-top d-flex flex-column align-items-start gap-4 px-2 px-sm-5 mb-5 " style="top: 20px;">
                    <h2 class="text-start" style="color: #2e384d;">Update the Profile</h2>
                    <input name="username" type="text" value="<?php echo $user['username'] ?>" class="form-control subs form-control-lg" placeholder="Enter Your Username" style="min-width: 300px; background-color: #f9f9f9; box-shadow: none; border-radius: 0;">
                    <input name="email" type="email" value="<?php echo $user['email'] ?>" class="form-control subs form-control-lg" placeholder="Enter Your Email" style="min-width: 300px; background-color: #f9f9f9; box-shadow: none; border-radius: 0;">
                    <input type="password" name="new_password" class="form-control subs form-control-lg" placeholder="Enter Your Password" style="min-width: 300px; background-color: #f9f9f9; box-shadow: none; border-radius: 0;">
                    <input type="password" name="confirm_password" class="form-control subs form-control-lg" placeholder="Enter Your Password" style="min-width: 300px; background-color: #f9f9f9; box-shadow: none; border-radius: 0;">
                    <input type="file" name="profile_picture" class="form-control subs form-control-lg" style="min-width: 300px; background-color: #f9f9f9; box-shadow: none; border-radius: 0;" accept="image/*">
                    <div class="btngroup d-flex flex-wrap gap-3 align-items-center">
                        <input type="submit" value="Update" style="background-color: #2e384d; border: 0;" class="btn  px-5 py-2 rounded-0 btn-primary">
                        <span class="fs-2 text-disabled text-secondary fw-lighter">|</span>
                        <a class="fs-5 text-decoration-none text-danger fw-light" href="logout.php">Logout</a>
                        <span class="fs-2 text-disabled text-secondary fw-lighter">|</span>
                        <a class="fs-5 text-decoration-none text-danger fw-light" href="logout.php">Delete Account</a>
                    </div>
                </form>
            </div>

            <div class="col-12 col-lg-5">
                <h2 class="text-start" style="color: #2e384d;">Your Comments</h2>
                <ul class="d-flex gap-3 flex-column mt-4" style="list-style: none;">

                    <?php foreach ($user_comments as $comment): ?>
                        <!-- Comment -->
                        <li class="comment">
                            <div class="d-flex justify-content-between">
                                <a href="../post.php?id=<?php echo htmlspecialchars($comment['post_id']) ?>" style="color: #2e384d; max-width: 450px;" class="fs-5 text-truncate fw-bold text-decoration-none">
                                    <?php echo htmlspecialchars($comment['post_title']) ?>
                                </a>
                                <form action="profile.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                    <input type="hidden" name="delete_comment_id" value="<?php echo $comment['id']; ?>">
                                    <button type="submit" class=" bg-transparent border-0 delete-btn"><i style="color: darkred;" class="bi bi-trash"></i></button>
                                </form>
                               
                            </div>
                            <ul style="list-style: none;" class="mt-3">
                                <li>
                                    <p style="color: #7c7c7c;"><?php echo htmlspecialchars($comment['comment_content']) ?></p>
                                </li>
                            </ul>
                        </li>
                        <!-- Comment -->
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
 
<?php include '../components/footer.php' ?>