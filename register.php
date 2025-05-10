<?php
require_once './config/database.php';
$errors = [];

if (isset($_SESSION['user_id'])) {
    header("Location: ./");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $profile_picture = $_FILES['profile_picture'] ?? null;

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = 'All fields are required.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if($profile_picture && $profile_picture['error'] === UPLOAD_ERR_OK){
        $file_ext = strtolower(pathinfo($profile_picture['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if(!in_array($file_ext, $allowed_ext)){
            $errors[] = 'Invalid File type Only Jpg, Png, Jpeg are allowed';
        }

        if($profile_picture['size'] > 2 * 1024 * 1024){
            $errors[] = 'File size must be under 2 mb';
        }
         
        if(empty($errors)){
            $targetdir = './uploads/';
            $new_filename = uniqid() . '.' . $file_ext;
            $target_file = $targetdir . $new_filename;

            if(move_uploaded_file($profile_picture['tmp_name'], $target_file)){

            } else {
                $errors[] = "Failed to upload profile picture";
            }
        }

    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO users (username, email, password, profile_picture) VALUES (:username, :email, :password, :profile_picture)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashed_password,
                ':profile_picture' => $new_filename ?? null
            ]);

            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>

<?php
$pagetitle = "Register";
$basePath =  './';
include './components/header.php' ?>

<?php 
$metadescription = "Register to join our community and gain access to exclusive content. Sign up now to start your journey with us."; 
$metaauthor = "Lovenish";
$ogtitle = "Register - All Blogs";
$ogdesc = "Register now to unlock a world of knowledge and inspiration. Join our community and start your journey today!";
$ogimage = "public/logo.svg";
$ogtype = "website";
$ogurl = "https://problog.lovenishlabs.com/register.php";
include './components/publicheader.php' ; ?>


<?php include './components/navbar.php' ?>

<div style="display: flex; align-items: center; justify-content: center; min-height: 80vh; padding: 4em 0;">
    <div class="container p-0" style="max-width: 400px;">

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div style="border-radius: 8px;">
            <h2 class="text-center" style="color: #607f61; margin-bottom: 1em;">Register</h2>

            <form action="register.php" method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3 justify-content-center align-items-center">
                <!-- Username -->
                <div style="width: 100%;">
                    <label class="mb-3" for="username" style="color: #2e384d; font-weight: 500;">Username</label>
                    <input type="text" id="username" name="username"
                        class="form-control subs form-control-lg" placeholder="Username" style=" box-shadow: none; border-radius: 0;">
                </div>

                <!-- Email input -->
                <div style="width: 100%;">
                    <label class="mb-3" for="email" style="color: #2e384d; font-weight: 500;">Email address</label>
                    <input type="email" id="email" name="email"
                        class="form-control subs form-control-lg" placeholder="Enter Your Email" style=" box-shadow: none; border-radius: 0;">
                </div>

                <!-- Password input -->
                <div style="width: 100%;">
                    <label class="mb-3" for="password" style="color: #2e384d; font-weight: 500;">Password</label>
                    <input type="password" id="password" placeholder="Password" name="password"
                        class="form-control subs form-control-lg" style=" box-shadow: none; border-radius: 0;">
                </div>

                <!-- Confirm Password input -->
                <div style="width: 100%;">
                    <label class="mb-3" for="password" style="color: #2e384d; font-weight: 500;">Confirm Password</label>
                    <input type="password" id="password" placeholder="Repeat Password" name="confirm_password"
                        class="form-control subs form-control-lg" style=" box-shadow: none; border-radius: 0;">
                </div>
              
                <!-- Confirm Password input -->
                <div style="width: 100%;">
                    <label class="mb-3" for="file" style="color: #2e384d; font-weight: 500;">Profile Pic</label>
                    <input type="file" id="file" name="profile_picture"
                        class="form-control subs form-control-lg" style=" box-shadow: none; border-radius: 0;">
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn mt-4 btn-primary w-50"
                    style="background-color: #639b65; border: none; padding: 10px; font-weight: 500;">
                    Register
                </button>
            </form>

            <!-- Sign up link -->
            <div class="text-center mt-3">
                <span style="color: #2e384d;">Already have an account?</span>
                <a href="./login" style="color: #a5d6a7; text-decoration: none;">Login</a>
            </div>
        </div>
    </div>
</div>

<?php include './components/footer.php' ?>