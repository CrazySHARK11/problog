<?php
session_start();
require_once '../../config/database.php';

$errors = [];

try {
    $sql = "SELECT tagline FROM settings LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $settings = $stmt->fetch();
} catch (PDOException $e) {
    $errors[] = 'Failed  to retrive tagline ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tagline = $_POST['tagline'];

    try {
        $sql = "UPDATE settings SET tagline = :tagline WHERE id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':tagline' => $tagline]);

        // Refresh to display updated tagline
        header("Location: tagline.php");
        exit;
    } catch (PDOException $e) {
        $errors[] = 'Failed to update tagline: ' . $e->getMessage();
    }
}
?>


<?php $baseurlpath = "../";
include "../admincomponents/header.php"; ?>
<?php include "../admincomponents/navbar.php"; ?>

<section class="container">

    <div class="header d-flex align-items-center justify-content-between mb-5">
        <div class="left">
            <h1 class="my-3 fw-bold" style="color: #2e384d;">Contact Information </h1>
        </div>

        <div class="right d-flex gap-3">
            <a href="./" style="background-color: #639b65; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary ">Back</a>
            <a href="../" style="background-color: #2e384d; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary "><i class="me-2 bi bi-house"></i>Dashboard</a>
        </div>
    </div>

    <?php if ($errors): ?>
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form action="tagline.php" class="d-flex flex-column gap-3 mx-auto w-75" method="post">
        <div>
            <label for="mobile1" class="form-label">Tagline:</label>
            <input placeholder="mobile no. 1" name="tagline" class="form-control subs form-control-lg" value="<?php echo htmlspecialchars($settings['tagline']); ?>" id="mobile1" style="box-shadow: none; border-radius: 0;">
        </div>
        <button style="background-color: #2e384d; border: 0;" class="btn mt-5 px-5 py-2 rounded-0 btn-primary "  type="submit">Update Tagline</button>
    </form>
</section>

<?php include "../admincomponents/footer.php"; ?>