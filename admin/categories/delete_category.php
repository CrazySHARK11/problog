<?php 
  session_start();
  require_once '../../config/database.php';

  if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
  }

  $category_id = $_GET['id'] ?? null;
  
  if(!$category_id){
    header("Location: categories.php");
    exit;
  }

  try{
    $sql = "DELETE FROM categories WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $category_id
    ]);
  }catch(PDOException $e){
    die("Deleting failed" . $e->getMessage());
  }

  header("Location: categories.php");
  exit;

?>