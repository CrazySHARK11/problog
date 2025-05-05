<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    try {
        $sql = "SELECT profile_picture FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $userId
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (!empty($user['profile_picture'])) {
                $filePath = "../../uploads/" . $user['profile_picture'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([
                ':id' => $userId
            ]);

            header("Location: manageusers.php");
            exit;
        } else {
            echo "User not found.";
        }

         } catch (PDOException $e) {
             echo 'Error deleting user: ' . $e->getMessage();
         }
     } else {
         echo "Invalid request.";
     }

?>
     