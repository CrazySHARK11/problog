<?php 
session_start();
require_once "./config/database.php";

if(isset($_SESSION['user_id'])){
  
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
  $sql = "SELECT * FROM contacts LIMIT 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $contact = $stmt->fetch();
} catch (PDOException $e) {
  $errors[] = "Failed to retrieve data" . $e->getMessage();
}

?>


<?php
$pagetitle = "Register";
$basePath =  './';
include './components/header.php' ?>

<?php 
$metadescription = "Contact us for any queries or feedback. We value your input and are here to assist you."; 
$metaauthor = "Lovenish";
$ogtitle = "Contact Us - ProBlog";
$ogdesc = "Contact us for any queries or feedback. We value your input and are here to assist you.";
$ogimage = "public/logo.svg";
$ogtype = "website";
$ogurl = "https://problog.lovenishlabs.com/contact.php";
include './components/publicheader.php' ; ?>


<?php include './components/navbar.php' ?>

<div style="display: flex; align-items: center; justify-content: center; min-height: 80vh; padding: 4em 0;">
  <div class="container p-0">
    <div style="border-radius: 8px;">


      <div class="row justify-content-evenly gap-5 gap-lg-0">
        <div class="col-12 col-lg-5 p-0">
         <h2 class="text-center" style="color: #607f61; margin-bottom: 1em;">Contact info.</h2>  
          <ul style="list-style: none;" class="d-flex flex-column gap-4">
            <li style="color: #2e384d;"><i style="color: #607f61;" class="me-3 bi bi-pin-map-fill"></i> <?php echo htmlspecialchars($contact['location']) ?> </li>
            <li style="color: #2e384d;"><i style="color: #607f61;" class="me-3 bi bi-envelope-at-fill"></i> <?php echo htmlspecialchars($contact['email']) ?></li>
            <li style="color: #2e384d;"><i style="color: #607f61;" class="me-3 bi bi-telephone-fill"></i>  <?php echo htmlspecialchars($contact['mobile_1']) ?></li>
            <li style="color: #2e384d;"><i style="color: #607f61;" class="me-3 bi bi-telephone-fill"></i>  <?php echo htmlspecialchars($contact['mobile_2']) ?></li>
          </ul>

          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193597.03113662466!2d-74.1445328514272!3d40.69701928616164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1730615251217!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        
        <div class="col-12 col-lg-5">
        <h2 class="text-center" style="color: #607f61; margin-bottom: 1em;">Contact Us</h2>  
        <form action="" method="post" class="d-flex p-4 p-sm-0 flex-column gap-3 justify-content-center align-items-center">
            <!-- Username -->
            <div style="width: 100%;">
              <label class="mb-3" for="email" style="color: #2e384d; font-weight: 500;">Username</label>
              <input type="text" id="email"
                class="form-control subs form-control-lg" placeholder="Username" style=" box-shadow: none; border-radius: 0;">
            </div>

            <!-- Email input -->
            <div style="width: 100%;">
              <label class="mb-3" for="email" style="color: #2e384d; font-weight: 500;">Email address</label>
              <input type="email" id="email"
                class="form-control subs form-control-lg" placeholder="Enter Your Email" style=" box-shadow: none; border-radius: 0;">
            </div>
        
            <!-- Email input -->
            <div style="width: 100%;">
              <label class="mb-3" for="email" style="color: #2e384d; font-weight: 500;">Mobile No.</label>
              <input type="number" id="number"
                class="form-control subs form-control-lg" placeholder="Enter your Mobile" style=" box-shadow: none; border-radius: 0;">
            </div>

            <div style="width: 100%;">
              <label for="comment" style="font-weight: 500; color: #2e384d;">Message</label>
              <textarea id="comment" class="form-control mt-3" rows="6" placeholder="Your comment" style="box-shadow: none; border-radius: 0;"></textarea>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn mt-4 btn-primary w-50"
              style="background-color: #a5d6a7; border: none; padding: 10px; font-weight: 500;">
              Send
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "mainEntity": {
    "@type": "Organization",
    "name": "Problog",
    "url": "https://problog.lovenishlabs.com",
    "contactPoint": {
      "@type": "ContactPoint",
      "telephone": "+91 124353342",
      "contactType": "Customer Service",
      "areaServed": "IN",
      "availableLanguage": ["English", "Hindi"]
    }
  }
}
</script>

<?php include './components/footer.php' ?>