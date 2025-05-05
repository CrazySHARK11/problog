<?php 
   try {
    $sql = "SELECT tagline FROM settings LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $settings = $stmt->fetch();
} catch (PDOException $e) {
    $errors[] = 'Failed  to retrive tagline ' . $e->getMessage();
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
<footer class="text-dark pt-5" style="border-top: 1px solid #e1e1e1;">
    <div class="container text-md-start">
        <div class="row text-md-start text-center">
            <!-- About Section -->
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
            <a href="./" class="navbar-brand d-flex align-items-center text-decoration-none fs-4 fw-bold">
                <img src="<?php echo $basePath; ?>public/logo.svg" alt="logo" width="48px">
                <span class="ms-2">Pro<span style="color: #A5D6A7;">Blog</span>.</span>
            </a>
                <p style="color: #7c7c7c;" class="mt-2 line-clamp-four lg-md">
                    <?php echo htmlspecialchars($settings['tagline']) ?>
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                <h6 class="text-uppercase fw-bold mb-4">Quick Links</h6>
                <ul class="list-unstyled d-flex flex-column gap-3" >
                    <li><a href="#" style="color: #7c7c7c;" class="text-decoration-none">Home</a></li>
                    <li><a href="#" style="color: #7c7c7c;" class="text-decoration-none">About</a></li>
                    <li><a href="#" style="color: #7c7c7c;" class="text-decoration-none">Blogs</a></li>
                    <li><a href="#" style="color: #7c7c7c;" class="text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Useful Links -->
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                <h6 class="text-uppercase fw-bold mb-4">Resources</h6>
                <ul class="list-unstyled d-flex flex-column gap-3">
                    <li><a href="#"  style="color: #7c7c7c;" class="text-decoration-none">Privacy Policy</a></li>
                    <li><a href="#"  style="color: #7c7c7c;" class="text-decoration-none">Terms of Service</a></li>
                    <li><a href="#"  style="color: #7c7c7c;" class="text-decoration-none">Support</a></li>
                    <li><a href="#"  style="color: #7c7c7c;" class="text-decoration-none">FAQ</a></li>
                </ul>
            </div>

            <!-- Contact Section -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                <p style="color: #7c7c7c;"><i style="color: #639B65;" class="mr-2 bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($contact['location']) ?> </p>
                <p style="color: #7c7c7c;"><i style="color: #639B65;" class="mr-2 bi bi-envelope-open-fill"></i> <?php echo htmlspecialchars($contact['email']) ?></p>
                <p style="color: #7c7c7c;"><i style="color: #639B65;" class="mr-2 bi bi-phone-fill"></i> <?php echo htmlspecialchars($contact['mobile_1']) ?> </p>
                <p style="color: #7c7c7c;"><i style="color: #639B65;" class="mr-2 bi bi-phone-fill"></i> <?php echo htmlspecialchars($contact['mobile_2']) ?> </p>
            </div>
        </div>

        <!-- Social Media Links -->
        <div class="text-center mt-4">
            <a href="#" class="me-4 text-reset"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="me-4 text-reset"><i class="fab fa-twitter"></i></a>
            <a href="#" class="me-4 text-reset"><i class="fab fa-instagram"></i></a>
            <a href="#" class="me-4 text-reset"><i class="fab fa-linkedin"></i></a>
            <a href="#" class="me-4 text-reset"><i class="fab fa-github"></i></a>
        </div>
    </div>

    <!-- Footer Bottom -->
</footer>
<div class="text-center p-3" style="background-color:#639B65;">
   <p style="color: #fff; margin: 0;"> © 2024 ProBlog. All rights reserved.</p>
</div>

</body>
</html>