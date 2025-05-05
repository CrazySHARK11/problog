<?php
session_start();
require_once '../../config/database.php';
$errors = [];

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$category_id = $_GET['id'] ?? null;
if (!$category_id) {
    header("Location: categories.php");
    exit;
}

try {
    $sql = "SELECT * FROM categories WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $category_id
    ]);
    $category = $stmt->fetch();
    if (!$category) {
        header("Location: categories.php");
        exit;
    }
} catch (PDOException $e) {
    $errors[] = 'Failed to fetch category: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $background_color = $_POST['background_color'] ?? '';

    if (empty($name) || empty($background_color)) {
        $errors[] = 'Both name and background color are required.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE categories SET name = :name, background_color = :background_color WHERE id = :id");
            $stmt->execute([':name' => $name, ':background_color' => $background_color, ':id' => $category_id]);
            header("Location: categories.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Failed to update category: ' . $e->getMessage();
        }
    }
}

?>

<?php $baseurlpath = "../";
include "../admincomponents/header.php"; ?>
<?php include "../admincomponents/navbar.php"; ?>
<div class="container">

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

    <div class="header d-flex align-items-center justify-content-between mb-5">
        <div class="left">
            <h1 class="my-3 fw-bold" style="color: #2e384d;">Edit a Category</h1>
            <p class="fs-5" style="color: #7c7c7c;"> Edit this category </p>
        </div>

        <div class="right d-flex gap-3">
            <a href="./categories.php" style="background-color: #639b65; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary"><i class="bi me-2 bi-arrow-left-circle"></i>Categories</a>
            <a href="../" style="background-color: #2e384d; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary "><i class="me-2 bi bi-house"></i>Dashboard</a>
        </div>
    </div>

    <form action="edit_category.php?id=<?php echo htmlspecialchars($category_id) ?>" class="d-flex flex-column gap-4 " style="max-width: 450px;" method="post">
        <div>
            <label for="name">Name:</label>
            <input class="form-control subs form-control-lg" value="<?php echo htmlspecialchars($category['name']) ?>" style="box-shadow: none; border-radius: 0;" type="text" name="name" id="name" required>
        </div>

        <div>
            <label for="background_color">Background Color (HEX):</label>
            <input class="form-control subs form-control-lg" value="<?php echo htmlspecialchars($category['background_color']) ?>" style="box-shadow: none; border-radius: 0;" type="text" name="background_color" id="background_color" required placeholder="#FFFFFF">
        </div>

        <input style="background-color: #2e384d; border: 0;" class="btn mt-5 px-3 py-2 rounded-0 btn-primary" type="submit" value="Edit Category">
    </form>

</div>
<?php include "../admincomponents/footer.php"; ?>