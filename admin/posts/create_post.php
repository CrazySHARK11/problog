<?php
session_start();
require_once '../../config/database.php';
require_once '../posts/sluggenarator.php';

$errors = [];

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $posturl_slug = trim($_POST['url_slug']);
    $meta_title = trim($_POST['meta_title']);
    $meta_desc = trim($_POST['meta_desc']);
    $main_img_alt = trim($_POST['main_img_alt']);
    $description = trim($_POST['description']);
    $category_id = trim($_POST['category_id']);
    $content = trim($_POST['content']);
    $published_date = $_POST['published_date'];

    $main_image = $_FILES['main_image']['name'];
    $main_image_tmp = $_FILES['main_image']['tmp_name'];
    $main_image_size = $_FILES['main_image']['size'];
    $main_image_error = $_FILES['main_image']['error'];

    if (empty($title)) {
        $errors[] = "Title is required";
    }

    if (empty($description)) {
        $errors[] = "Description is required";
    }

    if (empty($content)) {
        $errors[] = "Content is required";
    }

    // Allowed file types
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    $file_extension = strtolower(pathinfo($main_image, PATHINFO_EXTENSION));

    if ($main_image) {

        if (!in_array($file_extension, $allowed_extensions)) {
            $errors[] = "Only JPG, JPEG, WEBP and PNG files are allowed.";
        }

        if ($main_image_size > 2 * 1024 * 1024) {
            $errors[] = "File size must be less than 2MB.";
        }

        if ($main_image_error !== UPLOAD_ERR_OK) {
            $errors[] = "An error occurred during file upload.";
        }

        if (empty($errors)) {
            $target_dir = '../../uploads/';
            $target_file = $target_dir . uniqid('img_', true) . '.' . $file_extension;
            move_uploaded_file($main_image_tmp, $target_file);

            $uploaded_image = basename($target_file);
        } else {
            $errors[] = "Failed to move uploaded file.";
        }
    } else {
        $errors[] = "Main Image is required";
    }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO posts (title, meta_title, meta_description, img_alt, category_id, author_id, published_date, main_image, description, content, slug) 
                VALUES (:title, :meta_title , :meta_description, :img_alt, :category_id, :author_id, :published_date, :main_image, :description, :content, :slug)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':meta_title' => $meta_title,
                ':meta_description' => $meta_desc,
                ':category_id' => $category_id,
                ':author_id' =>    $_SESSION['admin_id'],
                ':published_date' => $published_date,
                ':main_image' => $uploaded_image,
                ':description' => $description,
                ':content' => $content,
                ':slug' =>   generateSlug($posturl_slug),
                ':img_alt' => $main_img_alt
            ]);
            header("Location: list_posts.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Failed to create post: ' . $e->getMessage();
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
            <h1 class="my-3 fw-bold" style="color: #2e384d;">Create a Post</h1>
            <p class="fs-5" style="color: #7c7c7c;">Create a post today</p>
        </div>

        <div class="right d-flex gap-3">
            <a href="./list_posts.php" style="background-color: #639b65; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary"><i class="bi me-2 bi-arrow-left-circle"></i>Back to post page</a>
            <a href="../" style="background-color: #2e384d; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary "><i class="me-2 bi bi-house"></i>Dashboard</a>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <div class="alert d-flex align-items-center text-danger alert-danger alert-dismissible py-1 mb-0 fade show rounded-pill" role="alert" style="background-color: transparent; border: none;">
                    <?php echo $error ?>
                    <button type="button" class="text-dark btn p-0" data-bs-dismiss="alert" aria-label="Close">
                        <i class="bi bi-x fs-4" style="color: darkred;"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>


    <form action="create_post.php" method="POST" enctype="multipart/form-data" class="w-100 w-sm-75 d-flex flex-column gap-3 mx-auto">
        <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="text" name="title" placeholder="Post Title : h1" required>

        <div>
            <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="text" name="url_slug" placeholder="Post URL slug" required>
            <div class="form-text d-flex align-items-center gap-1"> <span class="badge" style="color: #2e384d; background-color:rgb(194, 255, 196);">SEO</span> You won't be able to change the post URL slug later <span style="color: blue;"> Keep the URL slug small and Unique </span> for <span style="color: red;"> SEO purpose </span> </div>
        </div>

        <div>
            <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="text" name="meta_title" placeholder="Title Tag" required>
            <div class="form-text d-flex align-items-center gap-1"> <span class="badge" style="color: #2e384d; background-color:rgb(194, 255, 196);">SEO</span> Keep the Title tag under <span style="color: blue;">60</span> Characters for <span style="color: red;"> SEO purpose </span> </div>
        </div>

        <div>
            <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="text" name="main_img_alt" placeholder="Main Image alt tag" required>
            <div class="form-text d-flex align-items-center gap-1"> <span class="badge" style="color: #2e384d; background-color:rgb(194, 255, 196);">SEO</span> Keep the Image alt tag <span style="color: blue;">unique</span> for <span style="color: red;"> SEO purpose </span> </div>
        </div>

        <div>
            <textarea class="form-control subs form-control-lg" rows="6" style="box-shadow: none; border-radius: 0;" type="text" name="meta_desc" placeholder="Meta Description" required></textarea>
            <div class="form-text d-flex align-items-center gap-1"> <span class="badge" style="color: #2e384d; background-color:rgb(194, 255, 196);">SEO</span>Keep the Meta description under <span style="color: blue;">160</span> Characters for <span style="color: red;"> SEO purpose </span> </div>
        </div>

        <select class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" name="category_id" required>
            <?php
            $stmt = $pdo->query("SELECT * FROM categories");
            while ($category = $stmt->fetch()) {
                echo "<option value='{$category['id']}'>{$category['name']}</option>";
            }
            ?>
        </select>

        <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="date" name="published_date" required>
        <input class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" type="file" name="main_image" accept="image/*" required>
        <textarea class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0; min-height: 250px;" name="description" placeholder="Short Description" required></textarea>
        <textarea id="editor" class="form-control subs form-control-lg" style="box-shadow: none; border-radius: 0;" name="content" placeholder="Main Content"></textarea>
        <button style="background-color: #2e384d; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary " type="submit">Create Post</button>
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