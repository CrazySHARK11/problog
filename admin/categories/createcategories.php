<?php
session_start();
require_once '../../config/database.php';

$errors = [];

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $background_color = $_POST['background_color'] ?? '';

    if (empty($name) || empty($background_color)) {
        $errors[] = "Both name and backgroud color are required";
    } else {
        try {
            $sql = "INSERT INTO categories (name, background_color) VALUES (:name, :background_color)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':name' => $name, ':background_color' => $background_color]);
            header("Location: categories.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Failed to add category: ' . $e->getMessage();
        }
    }
}
?>

<?php $baseurlpath = "../";
include "../admincomponents/header.php"; ?>
<?php include "../admincomponents/navbar.php"; ?>
<div class="container">

    <div class="header d-flex align-items-center justify-content-between mb-5">
        <div class="left">
            <h1 class="my-3 fw-bold" style="color: #2e384d;">Create a Category</h1>
            <p class="fs-5" style="color: #7c7c7c;"> Create a category for your post</p>
        </div>

        <div class="right d-flex gap-3">
            <a href="./categories.php" style="background-color: #639b65; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary"><i class="bi me-2 bi-arrow-left-circle"></i>Categories</a>
            <a href="../" style=" border: 0;" class="btn px-3 py-2 rounded-0 btn-dark"><i class="me-2 bi bi-house"></i>Dashboard</a>
        </div>
    </div>

    <form action="createcategories.php" class="d-flex flex-column gap-4 " style="max-width: 450px;" method="post">
        <div>
            <label for="name">Name:</label>
            <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="text" name="name" id="name" required>
        </div>

        <div>
            <label for="background_color">Background Color (HEX):</label>
            <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="text" name="background_color" id="background_color" required placeholder="#FFFFFF">
        </div>

        <input style="background-color: #639b65; border: 0;" class="btn mt-5 px-3 py-2 rounded-0 btn-primary" type="submit" value="Add Category">
    </form>

</div>
<?php include "../admincomponents/footer.php"; ?>