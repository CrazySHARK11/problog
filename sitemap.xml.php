<?php

require_once "./config/database.php";
 
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

<url>
  <loc>https://problog.lovenishlabs.com/</loc>
  <lastmod>2025-05-09T12:42:44+00:00</lastmod>
  <priority>1.00</priority>
</url>
<url>
  <loc>https://problog.lovenishlabs.com/about.php</loc>
  <lastmod>2025-05-09T12:42:44+00:00</lastmod>
  <priority>0.80</priority>
</url>
<url>
  <loc>https://problog.lovenishlabs.com/blogs.php</loc>
  <lastmod>2025-05-09T12:42:44+00:00</lastmod>
  <priority>0.80</priority>
</url>
<url>
  <loc>https://problog.lovenishlabs.com/contact.php</loc>
  <lastmod>2025-05-09T12:42:44+00:00</lastmod>
  <priority>0.80</priority>
</url>
<url>
  <loc>https://problog.lovenishlabs.com/register.php</loc>
  <lastmod>2025-05-09T12:42:44+00:00</lastmod>
  <priority>0.3</priority>
</url>
<url>
  <loc>https://problog.lovenishlabs.com/login.php</loc>
  <lastmod>2025-05-09T12:42:44+00:00</lastmod>
  <priority>0.3</priority>
</url>
 
<?php 
  $sql = "SELECT id FROM posts";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
   while ($post = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <url>
      <loc><?php echo 'https://problog.lovenishlabs.com/post.php?slug=' . $post['id'] ?></loc>
      <lastmod><?php echo date('Y-m-d') ?></lastmod>
      <changefreq>daily</changefreq>
      <priority>0.8</priority>
    </url>
  <?php endwhile; ?>

</urlset>