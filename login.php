<?php
session_start();
require_once './config/database.php';
$errors = [];

if (isset($_SESSION['user_id'])) {
    header("Location: ./");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $errors[] = 'All fields are required';
    }

    if (empty($errors)) {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':email' => $email
            ]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: ./user/profile.php");
                exit;
            } else {
                $errors[] = "Invalid email or Password";
            }
        } catch (PDOException $e) {
            $errors[] = "Login Failed " . $e->getMessage();
        }
    }
}
?>


<?php
$pagetitle = "Login";
$basePath =  './';
include './components/header.php' ?>
<?php include './components/navbar.php' ?>

<div style="display: flex; align-items: center; justify-content: center; min-height: 80vh; ">
    <div class="container p-0" style="max-width: 400px;">
        <h2 class="text-center" style="color: #607f61; margin-bottom: 1em;">Login</h2>

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <div class="alert text-danger alert-danger alert-dismissible fade show rounded-pill" role="alert" style="background-color: transparent; border: none;">
                        <strong>Errors</strong> <?php echo $error ?>
                        <button type="button" class="text-dark btn" data-bs-dismiss="alert" aria-label="Close">
                          <i class="bi bi-x-circle fs-4" ></i>  
                        </button>
                    </div>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div style="border-radius: 8px;">

            <form action="" method="post" class="d-flex flex-column gap-3 justify-content-center align-items-center">
                <!-- Email input -->
                <div style="width: 100%;" class="px-3 px-sm-0">
                    <label class="mb-3" for="email" style="color: #2e384d; font-weight: 500;">Email address</label>
                    <input type="email" id="email" name="email"
                        class="form-control subs form-control-lg" placeholder="Enter Your Email" style=" box-shadow: none; border-radius: 0;">
                </div>

                <!-- Password input -->
                <div style="width: 100%;" class="px-3 px-sm-0">
                    <label class="mb-3" for="password" style="color: #2e384d; font-weight: 500;">Password</label>
                    <input type="password" id="password" placeholder="Password" name="password"
                        class="form-control subs form-control-lg" style=" box-shadow: none; border-radius: 0;">
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn mt-4 btn-primary w-50"
                    style="background-color: #639b65; border: none; padding: 10px; font-weight: 500;">
                    Log in
                </button>
            </form>

            <!-- Sign up link -->
            <div class="text-center mt-3">
                <span style="color: #2e384d;">Don't have an account?</span>
                <a href="./register.php" style="color: #a5d6a7; text-decoration: none;">Sign up</a>
            </div>
        </div>
    </div>
</div>

<?php include './components/footer.php' ?>