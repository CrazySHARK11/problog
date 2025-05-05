<?php
   session_start();
   
   if (!isset($_SESSION['admin_id'])) {
     header("Location: login.php");
     exit;
   }
?>


<?php $baseurlpath= "./"; include "./admincomponents/header.php"; ?>
<?php include "./admincomponents/navbar.php"; ?>

<?php include "./admincomponents/footer.php"; ?>
