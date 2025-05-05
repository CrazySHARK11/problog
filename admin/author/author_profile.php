<?php
session_start();
require_once '../../config/database.php';
$errors = [];

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
$author = null;

try {
    $sql = "SELECT * FROM authors WHERE id = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $author = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = "Failed to retrive data" . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $titles = $_POST['titles'];
    $education_university = $_POST['education_university'];
    $professor_university  = $_POST['professor_university'];
    $bestseller_count  = $_POST['bestseller_count'];
    $instagram_handle  = $_POST['instagram_handle'];
    $reddit_handle  = $_POST['reddit_handle'];
    $x_handle  = $_POST['x_handle'];
    $facebook_handle  = $_POST['facebook_handle'];
    $description  = $_POST['description'];

    $profile_picture = $author['profile_picture'];

    if (!empty($_FILES['profile_picture']['name'])) {
        $file_name = basename($_FILES['profile_picture']['name']);
        $target_path = "../../uploads/" . $file_name;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
            if ($profile_picture && file_exists('../../uploads/' . $profile_picture)) {
                unlink('../../uploads/' . $profile_picture);
        }
            $profile_picture = $file_name;
        } else {
            $errors[] = "Failed to upload the new profile picture.";
        }
    }

    try {
        $sql = "UPDATE authors SET
            name = :name,
            titles = :titles,
            education_university = :education_university,
            professor_university = :professor_university,
            bestseller_count = :bestseller_count,
            instagram_handle = :instagram_handle,
            reddit_handle = :reddit_handle,
            x_handle = :x_handle,
            facebook_handle = :facebook_handle,
            description = :description,
            profile_picture = :profile_picture
            WHERE id = 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':titles' => $titles,
            ':education_university' => $education_university,
            ':professor_university' => $professor_university,
            ':bestseller_count' => $bestseller_count,
            ':instagram_handle' => $instagram_handle,
            ':reddit_handle' => $reddit_handle,
            ':x_handle' => $x_handle,
            ':facebook_handle' => $facebook_handle,
            ':description' => $description,
            ':profile_picture' => $profile_picture
        ]);

        header("Location: author_profile.php?updated=1");
        exit;
    } catch (PDOException $e) {
        $errors[] = "Failed to update author information: " . $e->getMessage();
    }
}

?>

<?php $baseurlpath = "../";
include "../admincomponents/header.php"; ?>
<?php include "../admincomponents/navbar.php"; ?>
<section class="container">

    <!-- Header -->
    <div class="header d-flex align-items-center justify-content-between mb-5">
        <div class="left">
            <h1 class="my-3 fw-bold" style="color: #2e384d;">Manage Author</h1>
            <p class="fs-5" style="color: #7c7c7c;"> Manage your author </p>
        </div>

        <div class="right d-flex gap-3">
            <a href="../" style=" border: 0;" class="btn px-3 py-2 rounded-0 btn-dark"><i class="me-2 bi bi-house"></i>Dashboard</a>
        </div>
    </div>
    <!-- Header -->

    <!-- Author profile -->
    <form action="author_profile.php" class="d-flex flex-column gap-4" method="POST" enctype="multipart/form-data">
        <img width="250" height="250" class="object-fit-cover" src="<?php echo '../../uploads/' . htmlspecialchars($author['profile_picture']) ?>" alt="profiles">

        <div>
            <label for="name">Name</label>
            <input class="form-control form-control-lg" id="name" style="box-shadow: none; border-radius: 0;" type="text" name="name" value="<?php echo htmlspecialchars($author['name']); ?>" placeholder="Name">
        </div>

        <div>
            <label for="titles">Titles</label>
            <input class="form-control form-control-lg" id="titles" style="box-shadow: none; border-radius: 0;" type="text" name="titles" value="<?php echo htmlspecialchars($author['titles']); ?>" placeholder="Titles">
        </div>


        <div>
            <label for="education_university">Education University</label>
            <input class="form-control form-control-lg" id="education_university" style="box-shadow: none; border-radius: 0;" type="text" name="education_university" value="<?php echo htmlspecialchars($author['education_university']); ?>" placeholder="Education University">
        </div>

        <div>
            <label for="professor_university">Professor University</label>
            <input class="form-control form-control-lg" id="professor_university" style="box-shadow: none; border-radius: 0;" type="text" name="professor_university" value="<?php echo htmlspecialchars($author['professor_university']); ?>" placeholder="Professor University">
        </div>

        <div>
            <label for="bestseller_count">Best Seller count</label>
            <input class="form-control form-control-lg" id="" style="box-shadow: none; border-radius: 0;" type="number" name="bestseller_count" value="<?php echo htmlspecialchars($author['bestseller_count']); ?>" placeholder="Bestseller Count">
        </div>

        <div class="row gap-3 justify-content-center">

            <div class="col-5 d-flex flex-column gap-4">
                <div class="input-group">
                    <span class="input-group-text" style="border-radius: 0;" id="basic-addon1"><label for="instagram_handle"><i class="bi bi-instagram"></i></label></span>
                    <input class="form-control form-control-lg" id="instagram_handle" style="box-shadow: none; border-radius: 0;" type="text" name="instagram_handle" value="<?php echo htmlspecialchars($author['instagram_handle']); ?>" placeholder="Instagram Handle">
                </div>

                <div class="input-group">
                    <span class="input-group-text" style="border-radius: 0;" id="basic-addon1"><label for="reddit_handle"><i class="bi bi-reddit"></i></label></span>
                    <input class="form-control form-control-lg" id="reddit_handle" style="box-shadow: none; border-radius: 0;" type="text" name="reddit_handle" value="<?php echo htmlspecialchars($author['reddit_handle']); ?>" placeholder="Reddit Handle">
                </div>
            </div>

            <div class="col-5 d-flex flex-column gap-4">
                <div class="input-group">
                    <span class="input-group-text" style="border-radius: 0;" id="basic-addon1"><label for="x_handle"><i class="bi bi-twitter-x"></i></label></span>
                    <input class="form-control form-control-lg" id="x_handle" style="box-shadow: none; border-radius: 0;" type="text" name="x_handle" value="<?php echo htmlspecialchars($author['x_handle']); ?>" placeholder="X Handle">
                </div>

                <div class="input-group">
                    <span class="input-group-text" style="border-radius: 0;" id="basic-addon1"><label for="facebook_handle"><i class="bi bi-facebook"></i></label></span>
                    <input class="form-control form-control-lg" id="facebook_handle" style="box-shadow: none; border-radius: 0;" type="text" name="facebook_handle" value="<?php echo htmlspecialchars($author['facebook_handle']); ?>" placeholder="Facebook Handle">
                </div>
            </div>
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" class="form-control form-control-lg" style="box-shadow: none; border-radius: 0;" name="description" placeholder="Description"><?php echo htmlspecialchars($author['description']); ?></textarea>
        </div>

        <div>
            <label for="profile_picture">Author profile picture</label>
            <input class="form-control form-control-lg" id="profile_picture" style="box-shadow: none; border-radius: 0;" type="file" name="profile_picture">
        </div>

        <button style="background-color: #639b65; border: 0;" class="btn mt-5 px-3 py-2 rounded-0 btn-primary" type="submit">Update Profile</button>
    </form>

</section>
<?php include "../admincomponents/footer.php"; ?>