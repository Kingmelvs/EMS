<?php 
include 'admin/db_connect.php'; 
?>

<header class="masthead">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end mb-4 page-title">
                <h3 class="text-white">Welcome to <?php echo $_SESSION['system']['name']; ?></h3>
                <h4 class="text-white">Discover our upcoming events</h4>
            </div>
        </div>
    </div>
</header>

<?php
$upcomingEventsQuery = $conn->query("SELECT v.venue, vb.datetime 
    FROM venue_booking vb
    JOIN venue v ON vb.venue_id = v.id
    WHERE vb.status = 1
    AND DATE(vb.datetime) BETWEEN '2025-01-01' AND '2025-12-31'
    ORDER BY vb.datetime ASC");

$upcomingEvents = [];
while ($event = $upcomingEventsQuery->fetch_assoc()) {
    $upcomingEvents[] = $event;
}
?>
<div class="container my-5">
    <h2 class="text-center text-white mb-4">🌟 Our Features 🌟</h2>
    <div class="row text-center">

        <!-- Feature 1 -->
        <div class="col-md-3 mb-4">
            <div class="feature-card">
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h4>Easy Booking</h4>
                <p>Book venues with just a few clicks. Fast, reliable, and secured bookings.</p>
            </div>
        </div>

        <!-- Feature 2 -->
        <div class="col-md-3 mb-4">
            <div class="feature-card">
                <div class="icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h4>Instant Notifications</h4>
                <p>Get notified instantly when your booking is approved or rejected.</p>
            </div>
        </div>

        <!-- Feature 3 -->
        <div class="col-md-3 mb-4">
            <div class="feature-card">
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h4>24/7 Availability</h4>
                <p>Our system runs 24/7 to ensure you can book anytime, anywhere.</p>
            </div>
        </div>

        <!-- Feature 4 -->
        <div class="col-md-3 mb-4">
            <div class="feature-card">
                <div class="icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h4>Secure Platform</h4>
                <p>Your data and transactions are fully secured with end-to-end encryption.</p>
            </div>
        </div>

    </div>
</div>
<div class="container my-5">
    <h2 class="text-center text-white mb-4">📸 Photo Gallery 📸</h2>
    <div class="row">
        <div class="col-md-4 mb-4">
            <img src="images/event1.jpg" class="img-fluid gallery-image" onclick="openLightbox('images/event1.jpg')">
        </div>
        <div class="col-md-4 mb-4">
            <img src="images/event2.jpg" class="img-fluid gallery-image" onclick="openLightbox('images/event2.jpg')">
        </div>
        <div class="col-md-4 mb-4">
            <img src="images/event3.jpg" class="img-fluid gallery-image" onclick="openLightbox('images/event3.jpg')">
        </div>
    </div>
</div>

<div id="lightbox" class="lightbox">
    <span class="close" onclick="closeLightbox()">&times;</span>
    <img class="lightbox-content" id="lightbox-img">
</div>

<style>
/* Features Section Styling */
.container.my-5 {
    animation: fadeInUp 1.5s ease-in-out;
}

.feature-card {
    background-color: #111;
    border-radius: 10px;
    padding: 30px;
    color: white;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
    transition: all 0.5s;
    border: 2px solid transparent;
    text-align: center;
}

.feature-card:hover {
    transform: translateY(-10px) scale(1.05);
    border: 2px solid #42f5b6;
    box-shadow: 0 15px 50px rgba(0, 255, 149, 0.7);
    animation: glowing 1.5s infinite alternate;
}

.feature-card .icon i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #42f5b6;
}

.feature-card h4 {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.feature-card p {
    font-size: 0.9rem;
    color: #dcdcdc;
}

/* Glow Animation */
@keyframes glowing {
    0% {
        box-shadow: 0 0 20px rgba(66, 245, 182, 0.7);
    }

    100% {
        box-shadow: 0 0 40px rgba(66, 245, 182, 1), 0 0 80px rgba(66, 245, 182, 0.7);
    }
}

/* Page Load Animation */
@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(50px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
/*Upcoming Page Load Animation */
#event-container {
        background: linear-gradient(135deg, #6e8efb, #a777e3);
        padding: 50px;
        border-radius: 10px;
        animation: fadeIn 2s ease-in-out;
    }

    #event-card {
        background: #111;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        transform: scale(0.9);
        transition: transform 0.5s ease-in-out;
    }

    #event-card:hover {
        transform: scale(1);
    }

    .countdown {
        font-size: 2rem;
        font-weight: bold;
        letter-spacing: 2px;
        color: #00ff00;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    /* Lightbox Styling */
    .feature-card {
    background-color: #111;
    border-radius: 10px;
    padding: 30px;
    color: white;
    text-align: center;
    transition: all 0.5s;
}
.feature-card:hover {
    transform: translateY(-10px);
    background-color: #42f5b6;
    color: #000;
}

.gallery-image {
    border-radius: 10px;
    cursor: pointer;
    transition: transform 0.3s;
}
.gallery-image:hover {
    transform: scale(1.05);
}

.lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
    justify-content: center;
    align-items: center;
}
.lightbox-content {
    max-width: 90%;
    max-height: 90%;
}
.close {
    position: absolute;
    top: 20px;
    right: 30px;
    font-size: 40px;
    color: white;
    cursor: pointer;
}
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<div class="container mt-3 pt-2">
    <h4 class="text-center text-white">Upcoming Events</h4>
    <div class="row">

        <div id="event-container" class="text-center">
            <div id="event-card" class="text-white">
                <h2 class="event-title"></h2>
                <p class="event-time"></p>
                <div id="countdown" class="countdown"></div>
            </div>
        </div>
    </div>


    <script>
    let events = <?php echo json_encode($upcomingEvents); ?>;
    let currentIndex = 0;
    let countdownInterval;

    function updateEvent() {
        if (events.length === 0) {
            document.getElementById('event-card').innerHTML = "<h4>No upcoming events in 2025</h4>";
            clearInterval(countdownInterval);
            return;
        }

        const event = events[currentIndex];
        document.querySelector('.event-title').textContent = "Venue: " + event.venue;
        document.querySelector('.event-time').textContent = "Date: " + event.datetime;

        clearInterval(countdownInterval);
        startCountdown(event.datetime);

        currentIndex++;
        if (currentIndex >= events.length) currentIndex = 0;
    }

    function startCountdown(eventDate) {
        const eventTime = new Date(eventDate).getTime();
        countdownInterval = setInterval(() => {
            const now = new Date().getTime();
            const distance = eventTime - now;

            if (distance < 0) {
                clearInterval(countdownInterval);
                document.getElementById('countdown').innerHTML = `
                <span style="color: #f54242; font-size: 1.5rem; font-weight: bold;">
                🚨 Event Ended
                </span>`;
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000)) / 1000);

            // Real-time countdown that decreases per second
            document.getElementById('countdown').innerHTML = `
            <div style="display: flex; justify-content: center; gap: 15px; font-size: 2rem; font-weight: bold;">
                <span style="color: #00ff00;">${days}d</span>
                <span style="color: #ffd700;">${hours}h</span>
                <span style="color: #f54242;">${minutes}m</span>
                <span style="color: #42a5f5;">${seconds}s</span>
            </div>
        `;
        }, 1000);
    }

    // Auto-start the countdown if event is near (less than 2 days)
    updateEvent();
    setInterval(updateEvent, 5000);



    </script>