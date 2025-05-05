<?php
session_start();
require_once '../../config/database.php';
$errors = [];

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $sql = "SELECT * FROM contacts LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $contact = $stmt->fetch();
} catch (PDOException $e) {
    $errors[] = "Failed to retrieve data" . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $location = $_POST['location'];
    $mobile1 = $_POST['mobile_1'];
    $mobile2 = $_POST['mobile_2'];

    try {
        $sql = "UPDATE contacts SET email = :email, location = :location, mobile_1 = :mobile_1, mobile_2 = :mobile_2 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':location' => $location,
            ':mobile_1' => $mobile1,
            ':mobile_2' => $mobile2,
            ':id' => $contact['id']
        ]);
        // Refresh to display updated contact
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $errors[] = 'Failed to update contact information: ' . $e->getMessage();
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

    <form action="contact.php" class="d-flex flex-column gap-4 mx-auto w-75" method="post">
        <div>
            <label for="email" class="form-label">Email</label>
            <input placeholder="email" name="email" class="form-control subs form-control-lg" value="<?php echo htmlspecialchars($contact['email']) ?>" id="email" style="box-shadow: none; border-radius: 0;" type="email">
        </div>
        <div>
            <label for="address" class="form-label">Address</label>
            <input placeholder="address" name="location" class="form-control subs form-control-lg" value="<?php echo htmlspecialchars($contact['location']) ?>" id="address" style="box-shadow: none; border-radius: 0;" type="text">
        </div>
        <div>
            <label for="mobile1" class="form-label">Mobile 1</label>
            <input placeholder="mobile no. 1" name="mobile_1" class="form-control subs form-control-lg" value="<?php echo htmlspecialchars($contact['mobile_1']) ?>" id="mobile1" style="box-shadow: none; border-radius: 0;" type="text">
        </div>
        <div>
            <label for="mobile2" class="form-label">Mobile 2</label>
            <input placeholder="mobile no. 2" name="mobile_2" class="form-control subs form-control-lg" value="<?php echo htmlspecialchars($contact['mobile_2']) ?>" id="mobile2" style="box-shadow: none; border-radius: 0;" type="text">
        </div>

        <button style="background-color: #2e384d; border: 0;" class="btn mt-5 px-3 py-2 rounded-0 btn-primary"  type="submit">Update</button>
    </form>

</section>

<?php include '../admincomponents/footer.php' ?>