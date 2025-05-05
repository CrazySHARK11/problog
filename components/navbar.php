<header style="border-bottom: 1px solid #e1e1e1;">
    <nav class="navbar navbar-expand-md navbar-light py-2">
        <div class="container d-flex justify-content-md-start">
            <!-- LOGO -->
            <a href="<?php echo $basePath; ?>" class="navbar-brand d-flex align-items-center text-decoration-none fs-4 fw-bold">
                <img src="<?php echo $basePath; ?>public/logo.svg" alt="logo" width="48px">
                <span class="ms-2">Pro<span style="color: #A5D6A7;">Blog</span>.</span>
            </a>

            <!-- Toggler Button for Mobile -->
            <button class="navbar-toggler border-0" style="box-shadow: none;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible Content -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mt-4 mt-md-0 gap-md-4">
                    <!-- Menu Links -->
                    <li class="nav-item">
                        <a href="<?php echo $basePath; ?>" class="nav-link fw-medium" style="color: #2e384d;">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo $basePath; ?>about.php" class="nav-link fw-medium" style="color: #2e384d;">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo $basePath; ?>blogs.php" class="nav-link fw-medium" style="color: #2e384d;">Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo $basePath; ?>contact.php" class="nav-link fw-medium" style="color: #2e384d;">Contact us</a>
                    </li>
                </ul>

                <!-- Auth Links -->
                <div class="d-flex auth mt-2 mt-md-0">
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <a href="<?php echo $basePath; ?>user/profile.php" class="text-decoration-none fw-medium" style="color: #607f61;">
                            <img width="45" height="45" class="rounded-circle object-fit-cover" src="<?php echo $basePath . "uploads/" . htmlspecialchars($user['profile_picture']); ?>" alt="">
                        </a>
                    <?php } else { ?>
                        <a href="<?php echo $basePath; ?>register.php" class="text-decoration-none fw-medium" style="color: #607f61;">Sign up</a>
                        <span class="fw-lighter mx-3" style="color: #2e384d;">|</span>
                        <a href="<?php echo $basePath; ?>login.php" class="text-decoration-none fw-medium" style="color: #607f61;">Log in</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
</header>