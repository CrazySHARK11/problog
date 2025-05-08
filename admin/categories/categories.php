<?php
    session_start();
    require_once '../../config/database.php';

    $errors = [];

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit;
    }

    try {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $pdo->query($sql);
        $categories = $stmt->fetchAll();
    } catch (PDOException $e) {
        $errors[] = 'Failed to retrieve categories ' . $e->getMessage();
    }

    ?>

    <?php $baseurlpath = "../";
    include "../admincomponents/header.php"; ?>
    <?php include "../admincomponents/navbar.php"; ?>
    <div class="container">

        <div class="header d-flex align-items-center justify-content-between mb-5">
            <div class="left">
                <h1 class="my-3 fw-bold" style="color: #2e384d;">Manage Categories</h1>
                <p class="fs-5" style="color: #7c7c7c;"> Manage Your Categories of your posts</p>
            </div>

            <div class="right d-flex gap-3">
                <a href="./createcategories.php" style="background-color: #639b65; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary"><i class="me-2 bi bi-plus-circle"></i>Create</a>
                <a href="../" style=" border: 0;" class="btn px-3 py-2 rounded-0 btn-dark"><i class="me-2 bi bi-house"></i>Dashboard</a>
            </div>
        </div>

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


        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Background Color</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><span style="width: 30px; height: 30px; display: block; background-color: <?php echo htmlspecialchars($category['background_color']); ?>;"> </span></td>
                        <td>
                            <a style="color: #619b63;" class="text-success fw-medium text-decoration-none" href="edit_category.php?id=<?php echo $category['id']; ?>">Edit</a>
                            <span class="text-secondary fw-light mx-2">|</span>
                            <a class="text-danger fw-medium text-decoration-none" href="delete_category.php?id=<?php echo $category['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <?php include "../admincomponents/footer.php"; ?>