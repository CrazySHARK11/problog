<?php
session_start();
require_once '../../config/database.php';
$errors = [];

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $sql = "DELETE FROM testimonials WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        header("Location: alltestimonials.php");
        exit;
    } catch (PDOException $e) {
        $errors[] = 'Failed to delete testimonial: ' . $e->getMessage();
    }
} else {
    die("Invalid Id");
}
