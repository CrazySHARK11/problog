<?php
session_start();
require_once "./config/database.php";

$post_slug = $_GET['slug'] ?? null;
  
if (isset($_SESSION['user_id'])) {

  $user_id = $_SESSION['user_id'];
  $user = null;

  // Fetch current user data
  try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([
      ":id" => $user_id
    ]);
    $user = $stmt->fetch();
  } catch (PDOException $e) {
    $errors[] = 'Failed to retrieve user data: ' . $e->getMessage();
  }
}

  try {
    $sql = "SELECT  posts.id, posts.title, posts.content, posts.description, posts.main_image,  posts.published_date, categories.name AS category_name, 
                                authors.name AS author_name,  
                                authors.description AS auth_desc, 
                                authors.titles AS author_title, 
                                authors.profile_picture AS auth_prof_pic,
                                authors.instagram_handle AS auth_ig,
                                authors.reddit_handle AS auth_rd,
                                authors.facebook_handle AS auth_fb,
                                authors.x_handle AS auth_x
                                FROM posts 
                                JOIN categories ON posts.category_id = categories.id
                                JOIN authors ON posts.author_id = authors.id 
                                WHERE posts.slug = :slug ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':slug' => $post_slug]);
    $post = $stmt->fetch();

    if ($post) {
      $post_id = $post['id'];
    }
    
  } catch (PDOException $e) {
    $errors[] = 'Failed to retrieve post data: ' . $e->getMessage();
  }
 

// ------------------ COMMENTS ---------------------

$comments = [];
try {
  $stmt = $pdo->prepare("SELECT c.comment_content, c.created_at, u.username, u.profile_picture 
                           FROM comments c
                           JOIN users u ON c.user_id = u.id
                           WHERE c.post_id = :post_id
                           ORDER BY c.created_at DESC");
  $stmt->execute([':post_id' => $post_id]);
  $comments = $stmt->fetchAll();
} catch (PDOException $e) {
  $errors[] = "Failed to fetch comments: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
  $comment_content = trim($_POST['comment_content']);

  if (!empty($comment_content)) {
    try {
      $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, comment_content) 
                                 VALUES (:post_id, :user_id, :comment_content)");
      $stmt->execute([
        ':post_id' => $post_id,
        ':user_id' => $_SESSION['user_id'],
        ':comment_content' => $comment_content
      ]);
      header("Location: post/" . $post_slug);
      exit;
    } catch (PDOException $e) {
      $errors[] = "Failed to post comment: " . $e->getMessage();
    }
  } else {
    $errors[] = "Comment cannot be empty.";
  }
}

?>


<?php
$pagetitle = $post['title'];
$basePath =  "../";
include './components/header.php' ?>

<?php 
$metadescription = htmlspecialchars($post['description'])  ; 
$metaauthor = "Lovenish";
$ogtitle =  $post['title'] . "- All Blogs" ;
$ogdesc =  htmlspecialchars($post['description']) ;
$ogimage = htmlspecialchars($post['main_image']);
$ogtype = "website";
$ogurl = "https://problog.lovenishlabs.com/post/" . $post_slug;
include './components/publicheader.php' ; ?>


<?php include './components/navbar.php' ?>
<div class="container" style="max-width: 1280px; margin-top: 2rem; ">
 

  <!-- Blog Main Image -->
  <div style="margin-top: 3em;">
    <img src="<?php echo   '../uploads/' .htmlspecialchars($post['main_image']); ?>" alt="Blog Main Image" height="650" class="object-fit-cover" style="width: 100%; border-radius: 8px;">
  </div>

  <div class="row gap-3" style="justify-content: center;">
    <div class="col-12 col-lg-8">
      <!-- Blog Title and Metadata -->
      <h1 class="mt-4" style="font-size: 2.5rem; color: #2e384d; font-weight: 700; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($post['title']); ?></h1>
      <div class="d-flex flex-wrap gap-3 align-items-center my-4" style="color: #6c757d; font-size: 0.9rem;">
        <span>
          By <strong style="color: #6fbc71;"><?php echo htmlspecialchars($post['author_name']); ?></strong>
        </span> | <span class="badge d-inline" style="color: #2e384d; font-size: 1em; background-color: #ccfcce;">
          <?php echo htmlspecialchars($post['category_name']); ?></span> | <span>Published on:
          <strong style="color: #6fbc71;"><?php echo date("F j, Y", strtotime($post['published_date'])) ?></strong></span>
      </div>

      <!-- Blog Summary -->
      <div style="font-size: 1.1rem; color: #495057; margin-top: 1.5rem;">
        <p><em><?php echo htmlspecialchars($post['description']); ?></em></p>
      </div>

      <!-- Blog Content -->
      <div class="fs-5" style="color: #495057; line-height: 1.8; margin-top: 1rem;">
        <?php echo $post['content']; ?>
      </div>

    </div>

    <!-- Author PC -->
    <div class="col-lg-4 mt-4 w-25 d-none d-lg-block">
      <div class="sticky-md-top px-3" style="top: 20px; border-left: 1px solid #e1e1e1;">
        <h3 style="color: #2e384d;" class="fw-light mb-4">author</h3>
        <div class="d-flex align-items-center gap-3">
          <img loading="lazy" src="<?php echo "../uploads/" . htmlspecialchars($post['auth_prof_pic']) ?>" width="70" height="70" class="object-fit-cover rounded-circle" alt="author's image">
          <div class="auther-details d-flex flex-column">
            <p class="fs-4 m-0 fw-medium"><?php echo htmlspecialchars($post['author_name']); ?></p>
            <p class="fs-6 m-0" style="color: #7c7c7c;">
              <?php
              $title =  $post['author_title'];
              $parts = explode(" | ", $title);
              $firstTitle = $parts[0];

              echo htmlspecialchars("$firstTitle");
              ?>
            </p>
          </div>
        </div>
        <p class="mt-3 line-clamp-four" style="color: #495057;"><?php echo htmlspecialchars($post['auth_desc']) ?></p>

        <div class="d-flex gap-2">
          <a href="<?php echo htmlspecialchars($post['auth_ig']) ?>" style=" color: #727989;"><i class="fs-4 bi bi-instagram"></i></a>
          <a href="<?php echo htmlspecialchars($post['auth_fb']) ?>" style=" color: #727989;"><i class="fs-4 bi bi-facebook"></i></a>
          <a href="<?php echo htmlspecialchars($post['auth_rd']) ?>" style=" color: #727989;"><i class="fs-4 bi bi-reddit"></i></a>
          <a href="<?php echo htmlspecialchars($post['auth_x']) ?>" style=" color: #727989;"><i class="fs-4 bi bi-twitter-x"></i></a>
        </div>

      </div>
    </div>
  </div>

  <!-- About the Author Section Mobile   -->
  <div class="mt-5 d-block d-lg-none" style="padding: 1.5rem .5em; border-top: 1px solid #e1e1e1;">
    <h4 style="font-size: 1.5rem; color: #2e384d; font-weight: 600;">About the Author</h4>

    <div class="d-flex align-items-center gap-3">
      <img loading="lazy" src="<?php echo '../uploads/' . htmlspecialchars($post['auth_prof_pic']) ?>" width="65" height="65" class=" my-4 object-fit-cover rounded-circle" alt="author's image">
      <div class="auther-details d-flex flex-column">
        <p class="fs-4 m-0 fw-medium"><?php echo htmlspecialchars($post['author_name']); ?></p>
        <p class="fs-6 m-0" style="color: #7c7c7c;">
          <?php
          $title =  $post['author_title'];
          $parts = explode(" | ", $title);
          $firstTitle = $parts[0];

          echo htmlspecialchars("$firstTitle");
          ?>
        </p>
      </div>
    </div>

    <div style="color: #495057; margin-top: 0.5rem;">
      <p><?php echo htmlspecialchars($post['auth_desc']) ?></p>
    </div>

    <div class="d-flex gap-2">
      <a href="<?php echo htmlspecialchars($post['auth_ig']) ?>" style=" color: #7C7C7C;"><i class="fs-4 bi bi-instagram"></i></a>
      <a href="<?php echo htmlspecialchars($post['auth_fb']) ?>" style=" color: #7C7C7C;"><i class="fs-4 bi bi-facebook"></i></a>
      <a href="<?php echo htmlspecialchars($post['auth_rd']) ?>" style=" color: #7C7C7C;"><i class="fs-4 bi bi-reddit"></i></a>
      <a href="<?php echo htmlspecialchars($post['auth_x']) ?>" style=" color: #7C7C7C;"><i class="fs-4 bi bi-twitter-x"></i></a>
    </div>
  </div>

  <!-- Comment Section -->
  <div class="mt-5" style="padding: 1.5rem; border-top: 1px solid #e1e1e1;">
    <h4 style="font-size: 1.5rem; color: #2e384d; font-weight: 600;">Leave a Comment</h4>

    <?php if (isset($_SESSION['user_id'])): ?>

      <form style="margin-top: 1rem;" action="post.php?id=<?php echo $post_id; ?>" method="POST">
        <!-- Comment Field -->
        <div class="mb-3">
          <label for="comment" style="font-weight: 500; color: #2e384d;">Comment</label>
          <textarea id="comment" class="form-control mt-3" rows="4" placeholder="Your comment" name="comment_content" style="min-width: 300px; box-shadow: none; border-radius: 0;"></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" name="submit_comment" class="btn btn-primary" style="background-color: #639B65; border: none; padding: 0.75rem 1.5rem; font-weight: 500;">
          Post Comment
        </button>
      </form>

    <?php else: ?>
      <p class="mt-3" style="color: #7c7c7c;"><a class="text-decoration-none" style="color: #6fbc71;" href="../login">Log in</a> to post a comment.</p>
    <?php endif; ?>

  </div>

  <div class="all-comments my-4">

    <?php foreach ($comments as $comment): ?>
      <div class="comment d-flex mb-4">
        <div class="d-flex gap-3">
          <img loading="lazy" width="50" height="50" class="object-fit-cover rounded-circle"
            src="../uploads/<?php echo htmlspecialchars($comment['profile_picture']); ?>" alt="">

        </div>

        <div class="added-comment ms-3">
          <span class="mb-3 d-block">
            <p class="m-0 fs-4 fw-bold" style="color: #2E384D;"><?php echo htmlspecialchars($comment['username']); ?></p>
            <p style="color: #6fbc71;" class="m-0 fw-medium"><?php echo date("F j, Y, g:i a", strtotime($comment['created_at'])); ?></p>
          </span>

          <p style="color: #595959;">
            <?php echo nl2br(htmlspecialchars($comment['comment_content'])); ?>
          </p>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</div>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://problog.lovenishlabs.com/post/<?php echo $post_slug; ?>"
  },
  "headline": "<?php echo htmlspecialchars($post['title']); ?>",
  "description": "<?php echo htmlspecialchars($post['description']); ?>",
  "image": "https://problog.lovenishlabs.com/uploads/<?php echo htmlspecialchars($post['main_image']) ?>",  
    "author": {
    "@type": "Person",
    "name": "<?php echo htmlspecialchars($post['author_name']) ?>",
    "url": "https://problog.lovenishlabs.com/about"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Lovenish Labs",
    "logo": {
      "@type": "ImageObject",
      "url": "https://problog.lovenishlabs.com/images/logo.png"
    }
  },
   "datePublished": "<?php echo date(DATE_ATOM, strtotime($post['published_at'])) ?>"
}
</script>


<?php include './components/footer.php' ?>