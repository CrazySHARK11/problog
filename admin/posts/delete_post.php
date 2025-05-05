<?php 
  session_start();
  require_once '../../config/database.php';

  if(!isset($_SESSION['admin_id'])){
    header("Location: ../login.php");
    exit;
  }

  if(isset($_GET['id'])){
    $post_id = $_GET['id'];

    try {
        $sql = "SELECT main_image FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

         // Check if the post exists and has an associated image
         if ($post && !empty($post['main_image'])) {
            $image_path = '../../uploads/' . $post['main_image'];

            // Delete the image file from the server
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }


        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute([':id' => $post_id]);
        header("Location: list_posts.php");
        exit;
    } catch (PDOException $e) {
        echo 'Failed to delete post: ' . $e->getMessage();
    }
}
?>