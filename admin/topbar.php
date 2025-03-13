<style>
/* General Navbar Styling */
.navbar {
    background: linear-gradient(135deg, #1a1a1a, #292929);
    padding: 0;
    min-height: 3.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    border-bottom: 2px solid #ff4757;
}

/* System Name (Left Side) */
.navbar .text-white {
    font-size: 20px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

/* Profile Picture */
.profile-picture {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 3px solid #ff4757;
    object-fit: cover;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s;
    margin-right: 10px;
}

/* Profile Picture Hover */
.profile-picture:hover {
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(255, 71, 87, 0.8);
}

/* Dropdown Menu */
.dropdown-toggle {
    display: flex;
    align-items: center;
    color: #fff;
    font-size: 16px;
    text-transform: capitalize;
    transition: color 0.3s;
}

/* Dropdown Toggle Hover */
.dropdown-toggle:hover {
    color: #ff4757;
}

/* Dropdown Menu Animation */
.dropdown-menu {
    left: -2.5em;
    background: #1a1a1a;
    border: none;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    border-radius: 10px;
    padding: 10px 0;
    transition: all 0.3s ease-in-out;
}

/* Dropdown Items */
.dropdown-item {
    color: #ddd;
    padding: 10px 20px;
    transition: background 0.3s, transform 0.2s;
}

/* Dropdown Item Hover */
.dropdown-item:hover {
    background: #ff4757;
    color: #fff;
    transform: scale(1.05);
}

/* Logout Button */
.dropdown-item i.fa-power-off {
    color: #ff4757;
}

/* Manage Account Button */
.dropdown-item i.fa-cog {
    color: #1e90ff;
}

/* Navbar Glow Effect */
.navbar:hover {
    background: linear-gradient(135deg, #292929, #1a1a1a);
}

/* Responsive Design */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        text-align: center;
    }

    .profile-picture {
        margin: 10px auto;
    }
}
</style>

<nav class="navbar navbar-light fixed-top">
    <div class="container-fluid mt-2 mb-2">
        <div class="col-lg-12">
            <div class="col-md-1 float-left" style="display: flex;">
                <!-- Placeholder for a logo if needed -->
            </div>
            <div class="col-md-4 float-left text-white">
                <large><b><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : 'king' ?></b></large>
            </div>
            <div class="float-right">
                <div class="dropdown mr-4">
                    <a href="#" class="text-white dropdown-toggle" id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <!-- Profile Picture -->
                        <img src="<?php echo isset($_SESSION['photo']) && file_exists($_SESSION['photo']) ? $_SESSION['photo'] : '/uploads/photos/default.jpg'; ?>" 
                        alt="Profile" class="profile-picture">
                        <!-- Display User/ Admin Name -->
                        <span class="ml-2">
                            <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>
                        </span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="account_settings">
                        <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account">
                            <i class="fa fa-cog"></i> Manage Account
                        </a>
                        <a class="dropdown-item" href="ajax.php?action=logout">
                            <i class="fa fa-power-off"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Manage Account Modal
    $('#manage_my_account').click(function(){
        uni_modal("Manage Account", "manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own");
    });
</script>
