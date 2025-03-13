<style>
/* Sidebar Styling with Astigmatism-Friendly Colors */
#sidebar {
    background: #2E3A46;
    /* Darker blue-gray for better contrast */
    min-height: 100vh;
    transition: all 0.3s;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
}

/* Styling for sidebar logo and version */
.sidebar-top {
    text-align: center;
    padding: 20px;
    background: #1F2A33;
    /* Slightly darker for better readability */
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    
}

.logo-sidebar {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 10px;
}

.version {
    color: #FFD700;
    /* Yellow-gold for better readability */
    font-size: 20px;
    font-weight: bold;
}

/* Sidebar Navigation */
.sidebar-list a {
    display: block;
    padding: 12px 20px;
    color: rgb(27, 58, 87);
    /* Lighter text for contrast */
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s;
    position: relative;
}

.sidebar-list a .icon-field {
    margin-right: 10px;
}

.sidebar-list a:hover,
.sidebar-list a.active {
    background: #1B3A57;
    /* Blue hue for soft contrast */
    border-left: 5px solid #FFD700;
    padding-left: 25px;
}

/* Collapse animation for reports */
.collapse {
    transition: height 0.3s ease-in-out;
}
</style>

<nav id="sidebar" class='mx-lt-5 bg-dark'>
    <!-- Logo and Version at the top -->
    <div class="sidebar-top">
        <a href="index.php" class="logo-version">
            <img src="assets/img/logo.png" alt="Logo" class="logo-sidebar">
        </a>
        <span class="version">EMSSCCP V2.0</span>
    </div>

    <div class="sidebar-list">
        <a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i
                    class="fa fa-home"></i></span> Home</a>
        <a href="index.php?page=booking" class="nav-item nav-booking"><span class='icon-field'><i
                    class="fa fa-th-list"></i></span> Venue Book List</a>
        <a href="index.php?page=audience" class="nav-item nav-audience"><span class='icon-field'><i
                    class="fa fa-th-list"></i></span> Event Audience List</a>
        <a href="index.php?page=venue" class="nav-item nav-venue"><span class='icon-field'><i
                    class="fa fa-map-marked-alt"></i></span> Venues</a>
        <a href="index.php?page=events" class="nav-item nav-events"><span class='icon-field'><i
                    class="fa fa-calendar"></i></span> Events</a>

        <!-- Reports Collapse -->
        <a class="nav-item nav-reports" data-toggle="collapse" href="#reportCollpase" role="button"
            aria-expanded="false" aria-controls="reportCollpase">
            <span class='icon-field'><i class="fa fa-file"></i></span> Reports <i
                class="fa fa-angle-down float-right"></i>
        </a>
        <div class="collapse" id="reportCollpase">
            <a href="index.php?page=audience_report" class="nav-item nav-audience_report"><span
                    class='icon-field'></span> Audience Report</a>
            <a href="index.php?page=venue_report" class="nav-item nav-venue_report"><span class='icon-field'></span>
                Venue Report</a>
        </div>

        <!-- Admin Only Sections -->
        <?php if ($_SESSION['login_type'] == 1): ?>
        <a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i
                    class="fa fa-users"></i></span> Users</a>
        <a href="index.php?page=site_settings" class="nav-item nav-site_settings"><span class='icon-field'><i
                    class="fa fa-cogs"></i></span> System Settings</a>
        <?php endif; ?>
    </div>
</nav>

<script>
// Smooth collapse toggle
$('.nav-reports').click(function() {
    $($(this).attr('href')).collapse('toggle');
});

// Set active class on current page
$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active');
</script>