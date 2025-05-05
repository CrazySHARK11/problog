<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$errors = [];

try {
    $sql = "SELECT * FROM users ORDER BY id ASC";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = 'Error fetching users' . $e->getMessage();
}
?>
<?php $baseurlpath = "../";
include "../admincomponents/header.php"; ?>
<?php include "../admincomponents/navbar.php"; ?>

<section class="container">
    <h1 class="fw-bold" style="color: #2e384d;">Manage All Users</h1>
    <p style="color: #7c7c7c;">Manage all the users on your blog</p>

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

    <table class="table mt-5">
        <thead>
            <tr>
                <th style="color: #2e384d;" class="fw-medium">ID</th>
                <th style="color: #2e384d;" class="fw-medium">Username</th>
                <th style="color: #2e384d;" class="fw-medium">Email</th>
                <th style="color: #2e384d;" class="fw-medium">Profile Picture</th>
                <th style="color: #2e384d;" class="fw-medium">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td=>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td class="p-0">
                        <?php if ($user['profile_picture']): ?>
                            <img class="rounded" src="../../uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" width="50" alt="Profile Picture">
                        <?php else: ?>
                            <span>No Picture</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="text-success fw-medium text-decoration-none" href="edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>">Edit</a> 
                        <span class="text-secondary fw-light mx-2">|</span>
                        <a class="text-danger fw-medium text-decoration-none" href="delete_user.php?id=<?php echo htmlspecialchars($user['id']); ?>" onclick=" return confirm('Are you sure want to delete this account ?') ">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php include "../admincomponents/footer.php"; ?>