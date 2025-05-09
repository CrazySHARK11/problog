<?php
session_start();
require_once "./config/database.php";

// Testimonials
try{
  $stmt = $pdo->query("SELECT * FROM testimonials");
  $testimonials = $stmt->fetchAll();
}catch(PDOException $e){
  $errors[] = 'Failed to retrieve user data: ' . $e->getMessage();
}
// Testimonials

// Select the top post 
try {
  $stmt = $pdo->prepare('SELECT posts.title, posts.id, posts.main_image, posts.description, posts.published_date, categories.name AS category_name 
          FROM posts 
          JOIN categories ON posts.category_id = categories.id 
          WHERE is_top_post = :top_post');
  $stmt->execute([
    ':top_post' => 1
  ]);
  $top_post = $stmt->fetch();
} catch (PDOException $e) {
  $errors[] = 'Failed to retrieve user data: ' . $e->getMessage();
}
// Select the top post 


// Navbar auth pfp
if (isset($_SESSION['user_id'])) {

  $user_id = $_SESSION['user_id'];
  $user = null;

  // Fetch current user data
  try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
  } catch (PDOException $e) {
    $errors[] = 'Failed to retrieve user data: ' . $e->getMessage();
  }
}
// Navbar auth pfp

// populating categories
try {
  $sql = "SELECT * FROM categories ";
  $stmt = $pdo->query($sql);
  $categories = $stmt->fetchAll();
} catch (PDOException $e) {
  $errors[] = "Failed to retrieve categories data " . $e->getMessage();
}
// populating categories

// Populating blog posts 
try {
  $sql = "SELECT posts.title, posts.id, posts.main_image, posts.description, posts.published_date, categories.name AS category_name, 
           authors.name AS author_name, 
           authors.titles AS author_title,
           authors.profile_picture AS auth_prof_pic
            FROM posts 
            JOIN categories ON posts.category_id = categories.id
            JOIN authors ON posts.author_id = authors.id 
            ORDER BY posts.published_date DESC LIMIT 8";
  $stmt = $pdo->query($sql);
  $posts = $stmt->fetchAll();
} catch (PDOException $e) {
  $errors[] = "Failed to get posts";
}
// Populating blog posts 

// Fetch the last two posts
try {
  $sql = "SELECT posts.title, posts.id, posts.main_image, posts.description, posts.published_date, categories.name AS category_name 
          FROM posts 
          JOIN categories ON posts.category_id = categories.id 
          ORDER BY posts.published_date DESC LIMIT 2";
  $stmt = $pdo->query($sql);
  $lastTwoPosts = $stmt->fetchAll();
} catch (PDOException $e) {
  $errors[] = "Failed to get the last two posts: " . $e->getMessage();
}

try{
  $sql = "SELECT posts.title, posts.id, posts.main_image FROM posts WHERE is_popular = false LIMIT 3 ";
  $stmt= $pdo->query($sql);
  $popularPosts = $stmt->fetchAll();
}catch(PDOException $e){
  $errors[] = "Failed to get the last two posts: " . $e->getMessage();
}

?>

<?php
$pagetitle = "Home";
$basePath =  './';
include './components/header.php' ?>

<?php 
$metadescription = "Problog is a blog website that covers a wide range of topics, including technology, lifestyle, travel, and more. Our goal is to provide readers with informative and engaging content that inspires and educates. Join us on our journey to explore the world through our blogs."; 
$metaauthor = "Lovenish";
$ogtitle = "Problog - All Blogs";
$ogdesc = "At problog, we believe in the power of storytelling. Our blogs are crafted with care and passion, providing readers with a unique perspective on various topics. Whether you're looking for travel tips, tech reviews, or lifestyle advice, we've got you covered.";
$ogimage = htmlspecialchars($top_post['main_image']);
$ogtype = "website";
$ogurl = "https://problog.lovenishlabs.com/";
include './components/publicheader.php' ; ?>


<?php include './components/navbar.php' ?>
 
<!-- BANNER -->
<main class="row container gap-3 justify-content-center align-items-center" style="padding: 0; margin: 0 auto; min-height: calc(100vh - 76px);">

  <div class="main-blog-card col-12 col-md-5 gap-3 d-flex flex-column justify-content-end align-items-start  p-4" style="background-size: cover;  min-height: 37em; background-image: linear-gradient(0deg, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%, rgba(0,0,0,0) 100%) , url('./uploads/<?php echo htmlspecialchars($top_post['main_image']) ?>');">
    <span class="badge" style="color: #2e384d; background-color: #a5d6a7;"><?php echo htmlspecialchars($top_post['category_name']) ?></span>
    <h1 class="line-clamp-two" style="color: #fff;"><?php echo htmlspecialchars($top_post['title']) ?></h1>
    <div class="d-flex align-items-end">
      <p style="color: #e1e1e1;" class="line-clamp m-0">
        <?php echo htmlspecialchars($top_post['description']) ?>
      </p>
      <a class="m-0" href="./post.php?id=<?php echo htmlspecialchars($top_post['id']) ?>" style="margin-left: .5em; color: #a5d6a7;">
        <i class="bi bi-box-arrow-up-right "></i>
      </a>
    </div>
  </div>

  <div class="d-flex col-12 col-md-5 flex-column gap-5">
    <?php foreach ($lastTwoPosts as $post): ?>
      <div class="main-blog-card gap-2 d-flex flex-column justify-content-end align-items-start p-4" style="background-size: cover; min-height: 17em; background-image: linear-gradient(0deg, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%, rgba(0,0,0,0) 100%), url('<?php echo './uploads/' . htmlspecialchars($post['main_image']) ?>');">
        <span class="badge" style="color: #2e384d; background-color: #a5d6a7;"><?php echo htmlspecialchars($post['category_name']) ?></span>
        <h1 style="color: #fff;" class="fs-3 line-clamp-two"><?php echo htmlspecialchars($post['title']) ?></h1>
        <p style="color: #e1e1e1;" class="d-flex align-items-end">
          <span class="line-clamp-two"> <?php echo htmlspecialchars($post['description']) ?></span>
          <a href="post.php?id=<?php echo htmlspecialchars($post['id']) ?>" style="  color: #a5d6a7;">
            <i class="bi bi-box-arrow-up-right"></i>
          </a>
        </p>
      </div>
    <?php endforeach; ?>
  </div>

</main>
<!-- BANNER -->

<hr class="my-5 py-3">

<!-- Blog feed -->
<section class="container row gap-3" style="padding: 0; margin: 0 auto; min-height: calc(100vh - 76px);">
  <div class="blog-posts col-12 col-lg-8 d-flex flex-column gap-5">

    <?php foreach ($posts as $post): ?>
      <div class="blogcard d-flex flex-lg-row flex-column gap-4 align-items-center ">
        <img src="<?php echo './uploads/' . htmlspecialchars($post['main_image']) ?>" width="270" height="280" class="rounded object-fit-cover float-start" alt="">
        <div class="card-content d-flex flex-column gap-2 justify-content-center justify-content-lg-start align-items-center align-items-lg-start">
          <span class="badge d-inline" style="color: #2e384d; background-color: #ccfcce;"><?php echo htmlspecialchars($post['category_name']) ?></span>
          <h2 class="m-0 text-center line-clamp-two text-lg-start" style="color: #2e384d;"><?php echo htmlspecialchars($post['title']) ?></h2>
          <p class="m-0 text-center line-clamp text-lg-start"> <?php echo htmlspecialchars($post['description']) ?> </p>
          <div class="author d-flex align-items-center gap-2">
            <img width="40" height="40" class="rounded-circle object-fit-cover float-start" src="<?php echo './uploads/' . htmlspecialchars($post['auth_prof_pic']) ?>" alt="">
            <div class="auth-details d-flex flex-column my-3">
              <h4 class="m-0 author-name" style="font-size: 1em;"> <?php echo htmlspecialchars($post['author_name']) ?></h4>
              <p class="m-0" style="font-size: .8em; color: color-mix(in srgb, #2e384d 90%, #fff 50%);">

                <?php
                $title =  $post['author_title'];
                $parts = explode(" | ", $title);
                $firstTitle = $parts[0];

                echo htmlspecialchars("$firstTitle");
                ?>

              </p>
            </div>
          </div>
          <a class="fw-bold text-decoration-none" style="color: #639b65;" href="post.php?id=<?php echo htmlspecialchars($post['id']) ?>">READ MORE <i class="bi bi-chevron-double-right"></i> </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>


  <div class="blog-posts d-flex flex-column col-12 col-lg-3 gap-5">
    <div class="sticky-lg-top" style="top: 20px;">

      <div class="categories">
        <h2 class="mb-4 fw-bold">Categories</h2>
        <div class="categories d-flex flex-wrap gap-3 mt-3">
          <?php foreach ($categories as $category): ?>
            <span class="badge d-inline" style="color: #2e384d; background-color: color-mix(in srgb, <?php echo htmlspecialchars($category['background_color']) ?> 45%, #fff 55%);"><?php echo htmlspecialchars($category['name']) ?></span>
          <?php endforeach; ?>

        </div>
      </div>

      <div class="popular-posts mt-5">
        <h2 class="mb-4 fw-bold">Popular Posts</h2>
        <?php foreach($popularPosts as $popularPost): ?>
          <div class="side-popular-card d-flex gap-3 mt-3">
            <img src="./uploads/<?php echo htmlspecialchars($popularPost['main_image']) ?>" width="70" height="70" class="rounded object-fit-cover float-start" alt="" alt="">
            <a href="post.php?id=<?php echo htmlspecialchars($popularPost['id']) ?>" class="fs-6 line-clamp-two text-decoration-none text-dark fw-medium" style="color: color-mix(in srgb, #2e384d 90%, #fff 50%);"><?php echo htmlspecialchars($popularPost['title']) ?></a>
          </div>  
        <?php endforeach; ?>
      </div>
</section>
<!-- Blog feed -->

<!-- Our Focus -->
<!-- Our Focus -->

<!-- Reader's Experience -->
<section class="container " style="padding: 0; margin-top: 4em;  min-height: 40vh;">
  <h2>Reader's Experience</h2>
  <p style="color: #7c7c7c;">Here's some awesome feedbacks from our readers.</p>

  <div class="row gap-5 justify-content-center" style="margin: 4em 0 0 0;">
    <?php foreach($testimonials as $testimonial): ?>   
      <div class="testimonialcard shadow-sm col-12 col-lg-3 py-4 px-4 mt-5 rounded d-flex flex-column gap-3 position-relative" style="background-color:#f9f9f9;">
        <img src="./uploads/<?php echo htmlspecialchars($testimonial['profilepic']) ?>" width="60" height="60" class="rounded-circle position-absolute object-fit-cover float-start" style="top: -2em;" alt="Donald Trump">
        <p class="mb-0 line-clamp-four" style="color: #7c7c7c; margin-top: 2em;"><?php echo htmlspecialchars($testimonial['content']) ?></p>
        <p class="reviewername fw-bold m-0" style="color:#2E572F "><?php echo htmlspecialchars($testimonial['name']) ?></p>
        <span style="color: #7c7c7c;"><?php echo htmlspecialchars($testimonial['position']) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<!-- Reader's Experience -->

<!-- Subscribe Today -->
<section class="my-5" style="padding: 5.4em 0; background-image: url('./assets/img/subscribebg.png'); background-size: cover; background-position: center;">
  <div class="container d-flex gap-5 flex-column align-items-center justify-content-center" style=" min-height: 40vh;">
    <h2 class=" text-center"> <span style="font-size: 2em; color: #639b65;">Subscribe</span> <br>
      <span class="fs-4" style="color: #2e384d;">To Our Newsletter</span>
    </h2>
    <form action="" method="post">
      <div class="input-group">
        <input class="form-control subs form-control-lg" placeholder="Enter Your Email" style="min-width: 300px; background-color: #f9f9f9; box-shadow: none; border-radius: 0;" type="text">
        <button class="btn btn-primary px-4" type="button" style="border-radius: 0; border: 0; background-color: #2e384d;" id="button-addon2">Submit</button>
      </div>
    </form>
  </div>
</section>
<!-- Subscribe Today -->

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Website",
  "name": "Problog",
  "url": "https://problog.lovenishlabs.com",
}
</script>

<?php include './components/footer.php' ?>