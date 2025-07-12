<?php
session_start();

$userId = $_SESSION['userId'] ?? 0;
$isAdmin = $_SESSION['isAdmin'] ?? 0;
$username = $_SESSION['username'] ?? '';

?>

<body>
    <header>
        <a class="title-container" href="index.php">
            <img src="TravelTipiaLogo.svg" style="width: 30px; height: 30px;">
            <h1 class="title desktop-only">Travel Tipia</h1>
        </a>
        <nav class="nav-bar">
            <div class="links desktop-only">
                <a class="nav-link hover-underline" href="index.php">Home</a>
                <div class="dropdown">
                    <a class="nav-link">Advice</a>
                    <div class="dropdown-content">
                        <a class="dropdown-item" href="all_advice.php">All Advice</a>
                        <a class="dropdown-item" href="recent_advice.php">Recent Advice</a>              
                        <a class="dropdown-item" href="popular_advice.php">Popular Advice</a>
                        <a class="dropdown-item" href="liked_advice.php">Liked Advice</a>
                        <a class="dropdown-item" href="unread_advice.php">Unread Advice</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a class="nav-link">Discussion</a>
                    <div class="dropdown-content">
                        <a class="dropdown-item" href="#">All Discussions</a>
                        <a class="dropdown-item" href="#">My Discussions</a>
                        <a class="dropdown-item" href="#">Add Discussion</a>
                    </div>
                </div>
                <a class="nav-link hover-underline" href="gallary.php">Gallary</a>
                <a class="nav-link hover-underline" href="about.php">About</a>
                <a class="nav-link hover-underline" href="contact.php">Contact</a>
            </div>

            <div class="links mobile-only">
                <div class="dropdown">
                    <a class="nav-link">Menu</a>
                    <div class="dropdown-content sectioned-dropdown">
                        <h4>Advice</h4>
                        <a class="dropdown-item" href="#">All Advice</a>
                        <a class="dropdown-item" href="#">Recent Advice</a>
                        <a class="dropdown-item" href="#">Popular Advice</a>
                        <a class="dropdown-item" href="#">Favourite Advice</a>

                        <h4>Discussion</h4>
                        <a class="dropdown-item" href="#">All Discussions</a>
                        <a class="dropdown-item" href="#">My Discussions</a>
                        <a class="dropdown-item" href="#">Add Discussion</a>

                        <h4>Other</h4>
                        <a class="dropdown-item" href="gallary.php">Gallary</a>
                        <a class="dropdown-item" href="about.php">About</a>
                        <a class="dropdown-item" href="contact.php">Contact</a>
                    </div>
                </div>
            </div>

            <?php
            if($userId != 0) { 
            ?>
                <div class="dropdown right-container">
                <a class="nav-link">Welcome <?php echo htmlentities($username) ?></a>
                <div class="dropdown-content right-dropdown">
                    <?php 
                    if($isAdmin) { 
                    ?>
                        <a class="dropdown-item" href="#">Admin Panel</a>
                    <?php
                    }
                    ?>
                    <a class="dropdown-item" href="my_profile.php">My Profile</a>
                    <a class="dropdown-item" href="#">Account</a>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </div>
            <?php    
            } 
            else { 
            ?>
                <a class="nav-link" href="login.php">Login</a>
            <?php 
            }
            ?>
        </nav>
    </header>      

    <script>
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const dropdownContent = dropdown.querySelector('.dropdown-content');

            dropdown.addEventListener('mouseenter', (event) => {
                dropdownContent.style.height = dropdownContent.scrollHeight + 'px';
            });

            dropdown.addEventListener('mouseleave', (event) => {
                dropdownContent.style.height = '0';
            });

            dropdown.addEventListener('click', (event) => {
                if(dropdownContent.style.height > 0) {
                    dropdownContent.style.height = '0';
                }
                else {
                    dropdownContent.style.height = dropdownContent.scrollHeight + 'px';
                }
            });
        });
    </script>