<?php
session_start();
require_once '../../config/database.php';

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Fetch all posts with their respective category names and authors
try {
    $stmt = $pdo->query("SELECT posts.id, posts.title, posts.is_top_post, posts.is_popular, posts.published_date, categories.name AS category_name, categories.background_color AS category_bg, authors.name AS author_name 
                         FROM posts 
                         JOIN categories ON posts.category_id = categories.id
                         JOIN authors ON posts.author_id = authors.id
                         ORDER BY posts.published_date DESC");
    $posts = $stmt->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle marking/unmarking top or popular posts
        if (isset($_POST['top_post_id'])) {
            $topPostId = $_POST['top_post_id'];

            // Unset all current top posts
            $unsetTopQuery = "UPDATE posts SET is_top_post = FALSE";
            $pdo->exec($unsetTopQuery);

            // Set selected post as top post
            $setTopQuery = "UPDATE posts SET is_top_post = TRUE WHERE id = :id";
            $stmt = $pdo->prepare($setTopQuery);
            $stmt->bindParam(':id', $topPostId, PDO::PARAM_INT);
            $stmt->execute();
        }

        if (isset($_POST['popular_post_id'])) {
            $popularPostId = $_POST['popular_post_id'];

            // Toggle is_popular for the selected post
            $togglePopularQuery = "UPDATE posts SET is_popular = NOT is_popular WHERE id = :id";
            $stmt = $pdo->prepare($togglePopularQuery);
            $stmt->bindParam(':id', $popularPostId, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Reload the page to reflect changes
        header("Location: list_posts.php");
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching posts: " . $e->getMessage());
}
?>
<?php $baseurlpath = "../";
include "../admincomponents/header.php"; ?>
<?php include "../admincomponents/navbar.php"; ?>

<section class="container">

    <div class="header d-flex align-items-center justify-content-between mb-5">
        <div class="left">
            <h1 class="my-3 fw-bold" style="color: #2e384d;">All Your Posts</h1>
            <p class="fs-5" style="color: #7c7c7c;">Create, Edit and Delete your posts</p>
        </div>

        <div class="right d-flex gap-3">
            <a href="./create_post.php" style="background-color: #639b65; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary"><i class="bi me-2 bi-plus-circle"></i>Create a Post</a>
            <a href="../" style="background-color: #2e384d; border: 0;" class="btn px-3 py-2 rounded-0 btn-primary "><i class="me-2 bi bi-house"></i>Dashboard</a>
        </div>
    </div>



    <table class="table overflow-x-scroll">
        <thead>
            <tr>
                <th class="fw-medium">Title</th>
                <th class="fw-medium">Category</th>
                <th class="fw-medium">Author</th>
                <th class="fw-medium">Published Date</th>
                <th class="fw-medium">Top</th>
                <th class="fw-medium">Priority</th>
                <th class="fw-medium">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td class="w-25 "><?php echo htmlspecialchars($post['title']) ?></td>
                    <td><span class="badge d-inline" style="color: #2e384d; background-color: color-mix(in srgb, <?php echo htmlspecialchars($post['category_bg']) ?> 40%, #fff 60%) ; "><?php echo htmlspecialchars($post['category_name']); ?></span></td>
                    <td><?php echo htmlspecialchars($post['author_name']) ?></td>
                    <td><?php echo htmlspecialchars($post['published_date']) ?></td>
                    <td>
                        <span class="badge d-inline fw-light" style="color: #fff; background-color: <?php echo $post['is_top_post'] ? 'green' : '#b70000'; ?>; "><?php echo $post['is_top_post'] ? 'Yes' : 'No'; ?></span>
                    </td>
                    <td>
                        <!-- Top Post Action -->
                        <?php if (!$post['is_top_post']) : ?>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="top_post_id" value="<?php echo $post['id']; ?>">
                                <button class="btn btn-success" type="submit">Top</button>
                            </form>
                        <?php endif; ?>

                        <!-- Popular Post Action -->
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="popular_post_id" value="<?php echo $post['id']; ?>">
                            <button class="btn btn-<?php echo $post['is_popular'] ? 'primary' : 'success'; ?>" type="submit">
                                <?php echo $post['is_popular'] ? 'Generic' : 'Popular'; ?>
                            </button>
                        </form>
                    </td>
                    <td>
                        <a style="color: #619b63;" class="text-success fw-medium text-decoration-none" href="edit_post.php?id=<?php echo $post['id'] ?>">Edit</a>
                        <span class="text-secondary fw-light mx-2">|</span>
                        <a style="color: #771c24;" class="fw-medium text-decoration-none" href="delete_post.php?id=<?php echo $post['id'] ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</section>

<?php include "../admincomponents/footer.php"; ?>