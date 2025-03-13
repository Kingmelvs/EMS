<!-- About Us Section Styles -->
<style>
.about-section {
    padding: 80px 0;
    background-color: #f8f9fa;
    animation: fadeInUp 1.5s ease-in-out;
}

/* About Us Text Styling */
.about-content {
    padding: 20px;
    border-radius: 10px;
    background-color: #111;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transform: translateY(30px);
    opacity: 0;
    animation: fadeIn 2s ease-in-out forwards;
}

/* Section Headers */
.about-content h2,
.about-content h3 {
    color: #42f5b6;
    font-weight: bold;
}

/* Paragraph Styling */
.about-content p {
    font-size: 1rem;
    line-height: 1.8;
    color: #dcdcdc;
}

/* Divider Line */
.divider {
    width: 100px;
    height: 3px;
    background-color: #42f5b6;
    margin: 20px auto;
}

/* Smooth Fade In Animation */
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Vision and Mission Section */
.vision-section,
.mission-section {
    background-color: #1a1a1a;
    padding: 30px;
    border-radius: 10px;
    margin-top: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    transition: all 0.5s;
}

/* Hover Effect */
.vision-section:hover,
.mission-section:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 40px rgba(66, 245, 182, 0.6);
}

/* Button Hover Effect */
.btn-primary {
    background-color: #42f5b6;
    border: none;
    transition: all 0.3s;
}

.btn-primary:hover {
    background-color: #0be39d;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(66, 245, 182, 0.5);
}

.team-card {
    background-color: #111;
    border-radius: 10px;
    padding: 20px;
    color: white;
    text-align: center;
    transition: transform 0.3s;
    cursor: pointer;
    height: 320px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.team-card:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 40px rgba(0, 255, 149, 0.5);
}

.team-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin-bottom: 10px;
    border: 3px solid #00ff00;
    object-fit: cover;
}

.team-card h4 {
    margin: 10px 0 5px;
    font-size: 18px;
}

.team-card p {
    font-size: 14px;
    color: #b2b2b2;
}

/* Ensuring equal height for all cards */
.row.text-center .col-md-3 {
    padding-bottom: 20px;
}

@media (max-width: 768px) {
    .team-card {
        height: auto;
    }
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    justify-content: center;
    align-items: center;
}
.modal-content {
    background-color: #222;
    padding: 20px;
    border-radius: 10px;
    color: white;
    width: 500px;
    text-align: center;
}
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    cursor: pointer;
}
</style>


<!-- Masthead-->
<header class="masthead">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end mb-4" style="background: #0000002e;">
                <h1 class="text-uppercase text-white font-weight-bold">About Us</h1>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<section class="page-section about-section">
    <div class="container">
        <div class="row">
            <!-- About Us Text -->
            <div class="col-lg-12 mb-4">
                <div class="about-content">
                    <h2 class="text-primary mb-4 text-center">
                        🌟 Turning Moments into Unforgettable Memories 🌟
                    </h2>
                    <p class="text-center">
                        Welcome to <strong><?php echo $_SESSION['system']['name']; ?></strong>, the leading 
                        <strong>Event Management System in San Carlos City, Pangasinan</strong> — where dreams become 
                        reality, and every event is crafted with passion and precision.
                    </p>
                    <p>
                        We understand that planning an event can be overwhelming, whether it's a 
                        <strong>wedding, birthday, corporate event, or any special occasion</strong>. That's why we 
                        created a powerful, easy-to-use platform to simplify the entire process — from 
                        <strong>venue booking to event coordination</strong>. Our mission is to make your 
                        <strong>event planning hassle-free</strong> and <strong>extraordinary</strong>.
                    </p>

                    <hr class="divider my-4" />

                    <!-- Our Vision -->
                    <div class="vision-section mt-4">
                        <h3 class="text-primary text-center">
                            🚀 Our Vision
                        </h3>
                        <p class="text-center">
                            Our vision is to become the most trusted and reliable event management platform 
                            in San Carlos City, Pangasinan, where every celebration becomes a memorable experience. 
                            We strive to turn <strong>ordinary events into extraordinary moments</strong> through 
                            seamless planning and coordination.
                        </p>
                    </div>

                    <hr class="divider my-4" />

                    <!-- Our Mission -->
                    <div class="mission-section mt-4">
                        <h3 class="text-primary text-center">
                            💡 Our Mission
                        </h3>
                        <p class="text-center">
                            Our mission is to simplify event management by providing a platform where users can 
                            <strong>book venues, manage events, and stay informed</strong> with real-time updates. 
                            We are committed to making sure every event is handled with <strong>care, creativity, 
                            and professionalism</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">




<!-- Meet Our Team Section -->
<section class="page-section bg-dark text-white">
    <div class="container">
        <h2 class="text-center mb-5">Meet Our Team</h2>
        <div class="row text-center">

            <!-- Team Member 1 -->
            <div class="col-md-3 mb-4">
                <div class="team-card" onclick="openModal('member2')">
                    <img src="images/member2.jpg" alt="Member 2" class="team-img">
                    <h4>David Carlo D. Sanchez</h4>
                    <p>Team Leader / Data Encoder</p>
                </div>
            </div>


            <!-- Team Member 2 -->
            <div class="col-md-3 mb-4">
                <div class="team-card" onclick="openModal('melvin')">
                    <img src="images/profie.png" alt="Melvin" class="team-img">
                    <h4>King Melvin Q. Regacho</h4>
                    <p>Admin / Developer</p>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div class="col-md-3 mb-4">
                <div class="team-card" onclick="openModal('member3')">
                    <img src="images/member3.jpg" alt="Member 3" class="team-img">
                    <h4>Melisa I. Berdologa</h4>
                    <p>Design Consultant</p>
                </div>
            </div>

            <!-- Team Member 4 -->
            <div class="col-md-3 mb-4">
                <div class="team-card" onclick="openModal('member4')">
                    <img src="images/member4.jpg" alt="Member 4" class="team-img">
                    <h4>Mary Joy D. Jarillo</h4>
                    <p>Graphic Designer</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Modal Structure -->
<div class="modal" id="teamModal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <div id="modal-body"></div>
    </div>
</div>


<script>
function openModal(member) {
    let modalBody = document.getElementById('modal-body');

    let content = {
        'melvin': '<h2>King Melvin Q. Regacho</h2><p>As the Team Leader and Developer, Melvin ensures the system is smooth and user-friendly.</p>',
        'member2': '<h2>David Carlo D. Sanchez</h2><p>John coordinates all the events and ensures the venues are well-prepared.</p>',
        'member3': '<h2>Melisa I. Berdologa</h2><p>Jane designs all the event themes and creates stunning layouts.</p>',
        'member4': '<h2>Mary Joy D. Jarillo</h2><p>Michael handles logistics and event materials, ensuring everything is in place.</p>'
    };

    modalBody.innerHTML = content[member];
    document.getElementById('teamModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('teamModal').style.display = 'none';
}
</script>
