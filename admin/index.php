<?php
   session_start();
   
   if (!isset($_SESSION['admin_id'])) {
     header("Location: login.php");
     exit;
   }
?>


<?php $baseurlpath= "./"; include "./admincomponents/header.php"; ?>
<?php include "./admincomponents/navbar.php"; ?>

<div class="container">
  <h1 class="my-3 fw-bold" style="color: #2e384d;">Hello Lovenish, Welcome to the Dashboard</h1>
  <p class="fs-5" style="color: #7c7c7c;"> Manage Your Posts, Users and Data</p>

  <div class="row gap-5 my-5 p-0 m-0">
    <div style="background-color: #d6fed8 ;" class="p-3 overflow-hidden position-relative flex-grow-1 d-flex flex-column gap-5 col-12 col-md-3">
      <div>
        <h2 class="" style="color: #2e384d; font-weight: 700;">All Users</h2>
        <p>Manage All the users</p>
      </div>
      <a href="users/manageusers.php"><i style="color: #2e384d;" class="bi fs-2 bi-box-arrow-up-right"></i></a>
      <i style="right: -40px; bottom: -80px; opacity: .1; font-size: 14em;" class="bi position-absolute bi-person-circle"></i>
    </div>

    <div style="background-color: #d6fed8 ;" class="p-3 overflow-hidden position-relative flex-grow-1 d-flex flex-column gap-5 col-12 col-md-3">
      <div>
        <h2 class="" style="color: #2e384d; font-weight: 700;">All Posts</h2>
        <p>Manage All the Posts</p>
      </div>
      <a href="posts/list_posts.php"><i style="color: #2e384d;" class="bi fs-2 bi-box-arrow-up-right"></i></a>
      <i style="right: -40px; bottom: -80px; opacity: .1; font-size: 14em;" class="position-absolute bi bi-file-post"></i>
    </div>
    
    <div style="background-color: #d6fed8 ;" class="p-3 overflow-hidden position-relative flex-grow-1 d-flex flex-column gap-5 col-12 col-md-3">
      <div>
        <h2 class="" style="color: #2e384d; font-weight: 700;">All Categories</h2>
        <p>Manage All Categories</p>
      </div>
      <a href="categories/categories.php"><i style="color: #2e384d;" class="bi fs-2 bi-box-arrow-up-right"></i></a>
      <i style="right: -40px; bottom: -80px; opacity: .1; font-size: 14em;" class="position-absolute bi bi-file-post"></i>
    </div>

    <div style="background-color: #d6fed8 ;" class="p-3 overflow-hidden position-relative  d-flex flex-column gap-5 col-12 col-md-3">
      <div>
        <h2 class="" style="color: #2e384d; font-weight: 700;">Comments</h2>
        <p>Manage Comments</p>
      </div>
      <a href="#"><i style="color: #2e384d;" class="bi fs-2 bi-box-arrow-up-right"></i></a>
      <i style="right: -40px; bottom: -80px; opacity: .1; font-size: 14em;" class="bi position-absolute  bi-chat-dots"></i>
    </div>
  </div>

  <h1 class="mt-5 mb-3 fw-bold" style="color: #2e384d;">Manage Your Website</h1>
  <p class="fs-5" style="color: #7c7c7c;"> Manage Your Website data</p>

  <div class="row gap-5 justify-content-center my-5 p-0 m-0 ">

    <div style="background-color: #d6fed8 ;" class="p-3 overflow-hidden position-relative flex-grow-1 d-flex flex-column gap-5 col-12 col-md-3">
      <div>
        <h2 class="" style="color: #2e384d; font-weight: 700;">Authors</h2>
        <p>Manage Author</p>
      </div>
      <a href="./author/author_profile.php  "><i style="color: #2e384d;" class="bi fs-2 bi-box-arrow-up-right"></i></a>
      <i style="right: -40px; bottom: -80px; opacity: .1; font-size: 14em;" class="bi position-absolute bi-pencil-fill"></i>
    </div>

    <div style="background-color: #d6fed8 ;" class="p-3 overflow-hidden position-relative flex-grow-1 d-flex flex-column gap-5 col-12 col-md-3">
      <div>
        <h2 class="" style="color: #2e384d; font-weight: 700;">Incoming Enquiries</h2>
        <p>Manage Incoming Enquiries</p>
      </div>
      <a href="#"><i style="color: #2e384d;" class="bi fs-2 bi-box-arrow-up-right"></i></a>
      <i style="right: -40px; bottom: -80px; opacity: .1; font-size: 14em;" class="bi position-absolute bi-telephone-inbound"></i>
    </div>

    <div style="background-color: #d6fed8 ;" class="p-3 overflow-hidden position-relative flex-grow-1 d-flex flex-column gap-5 col-12 col-md-3">
      <div>
        <h2 class="" style="color: #2e384d; font-weight: 700;">Manage Structure</h2>
        <p>Manage Structure of the website</p>
      </div>
      <a href="./structure"><i style="color: #2e384d;" class="bi fs-2 bi-box-arrow-up-right"></i></a>
      <i style="right: -40px; bottom: -80px; opacity: .1; font-size: 14em;" class="bi position-absolute bi-person-circle"></i>
    </div>
  </div>
</div>

<?php include "./admincomponents/footer.php"; ?>