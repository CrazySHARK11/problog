<?php
session_start();
require_once '../../config/database.php';
$errors = [];

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $post = null;

    try {
        $sql = "SELECT * FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $post_id]);
        $post = $stmt->fetch();
    } catch (PDOException $e) {
        $errors[] = 'Failed to fetch post: ' . $e->getMessage();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $category_id = trim($_POST['category_id']);
        $description = trim($_POST['description']);
        $content = trim($_POST['content']);
        $published_date = trim($_POST['published_date']);

        $main_image = $_FILES['main_image']['name'];
        $target_dir = "../../uploads/";
        $uploadOk = 1;

        if(empty($title)){
            $errors[] = "Title is required";
        }
        
        if ($main_image) {
            $imageFileType = strtolower(pathinfo($main_image, PATHINFO_EXTENSION));
            $target_file = $target_dir . uniqid('img_', true) . '.' . $imageFileType;

            if ($_FILES['main_image']['size'] > 2 * 1024 * 1024) { // 2MB
                $errors[] = "Sorry, your file is too large. Maximum allowed size is 2MB.";
                $uploadOk = 0;
            }

            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
                $errors[] = "Sorry, only JPG, JPEG, and PNG files are allowed.";
                $uploadOk = 0;
            }

            if (empty($errors)) {
                $previous_image_path = $target_dir . $post['main_image'];
                if (file_exists($previous_image_path)) {
                    unlink($previous_image_path);
                }
 
                if (move_uploaded_file($_FILES['main_image']['tmp_name'], $target_file)) {
                    $main_image = basename($target_file);
                } else {
                    $errors[] = "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            $main_image = $post['main_image'];
        }


        if (empty($errors)) {
            try {
                $sql = "UPDATE posts SET title = :title, category_id = :category_id, published_date = :published_date, 
                           main_image = :main_image, description = :description, content = :content WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':title' => $title,
                    ':category_id' => $category_id,
                    ':published_date' => $published_date,
                    ':main_image' => $main_image,
                    ':description' => $description,
                    ':content' => $content,
                    ':id' => $post_id
                ]);
                header("Location: list_posts.php");
                exit;
            } catch (PDOException $e) {
                $errors[] = 'Failed to update post: ' . $e->getMessage();
            }
        }
    }
}


?>

<?php $baseurlpath = "../";
include "../admincomponents/header.php"; ?>
<?php include "../admincomponents/navbar.php"; ?>

<style>
    /* Target the actual editable area inside CKEditor */
    .ck-editor__editable_inline {
        min-height: 300px;
        /* optional: control height */
    }
</style>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<section class="container">

    <div class="header d-flex align-items-center justify-content-between mb-5">
        <div class="left">
            <h1 class="my-3 fw-bold" style="color: #2e384d;">Edit this Post</h1>
            <p class="fs-5" style="color: #7c7c7c;">Edit your post</p>
        </div>

        <div class="right d-flex gap-3">
            <a href="./list_posts.php" style="background-color: #639b65; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary"><i class="bi me-2 bi-arrow-left-circle"></i>Back to post page</a>
            <a href="../" style="background-color: #2e384d; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary "><i class="me-2 bi bi-house"></i>Dashboard</a>
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

    <form class="d-flex flex-column gap-4" action="edit_post.php?id=<?php echo $post_id ?>" method="POST" enctype="multipart/form-data">
        <img class="object-fit-cover" width="100%" height="300px" src="<?php echo '../../uploads/' . htmlspecialchars($post['main_image']) ?>" alt="">
        <input value="<?php echo htmlspecialchars($post['title']) ?>" class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="text" name="title" placeholder="Post Title" required>
        <div>
             <input value="<?php echo htmlspecialchars($post['slug']) ?>" class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="text" name="title" placeholder="Post Title" required disabled>
              <div class="form-text m-0">The slug is generated automatically and cannot be edited after publishing.</div>
        </div>
        <input value="<?php echo htmlspecialchars($post['published_date']) ?>" class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="date" name="published_date" required>
        <select class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" name="category_id" required>
            <?php
            $stmt = $pdo->query("SELECT * FROM categories");
            while ($category = $stmt->fetch()) {
                $selected = $category['id'] == $post['category_id'] ? 'selected' : '';
                echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
            }
            ?>
        </select>
        <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="file" name="main_image" accept="image/*" >
        <textarea class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" name="description" placeholder="Short Description" required><?php echo htmlspecialchars($post['description']) ?></textarea>
        <textarea id="editor" class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" name="content" placeholder="Main Content" required><?php echo htmlspecialchars($post['content']) ?></textarea>
        <button style="background-color: #2e384d; border: 0;" class="btn mt-4 px-3 py-2 rounded-0 btn-primary " type="submit">Create Post</button>

    </form>
</section>

<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });
</script>

<?php include "../admincomponents/footer.php"; ?>