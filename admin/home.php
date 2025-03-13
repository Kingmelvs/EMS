<?php include 'db_connect.php'; ?>
<style>
/* General Animations */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}

.fade-in.show {
    opacity: 1;
    transform: translateY(0);
}

.card {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

/* Booking Cards Hover Effects */
.new-booking:hover,
.confirmed-booking:hover,
.canceled-booking:hover {
    transform: scale(1.05);
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
}

/* Custom Colors */
.new-booking {
    background-color: #FFDBBB;
    color: black;
    font-weight: bold;
}

.confirmed-booking {
    background-color: #D1FFBD;
    color: black;
    font-weight: bold;
}

.canceled-booking {
    background-color: #ffcccb;
    color: black;
    font-weight: bold;
}

/* Slide-in Animation for Upcoming Events */
.event-card {
    border: 1px solid rgb(77, 199, 255);
    box-shadow: 0 4px 8px rgba(120, 238, 253, 0.88);
    transform: translateX(-50px);
    opacity: 0;
    transition: transform 0.6s ease-out, opacity 0.6s ease-out;
}

.event-card.show {
    transform: translateX(0);
    opacity: 1;
}
</style>

<div class="container-fluid">
    <div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-12">
            <div class="card fade-in">
                <div class="card-body">
                    <?php echo "Welcome back " . $_SESSION['login_name'] . "!" ?>
                    <hr>
                    <?php
                    // Fetch booking counts
                    $verificationCount = $conn->query("SELECT COUNT(*) FROM venue_booking WHERE status=0")->fetch_row()[0] ?? 0;
                    $confirmedBookingCount = $conn->query("SELECT COUNT(*) FROM venue_booking WHERE status=1")->fetch_row()[0] ?? 0;
                    $canceledBookingCount = $conn->query("SELECT COUNT(*) FROM venue_booking WHERE status=2")->fetch_row()[0] ?? 0;
                    // Fetch upcoming confirmed events
                    $upcomingEventsQuery = $conn->query("SELECT v.venue, vb.datetime FROM venue_booking vb JOIN venue v ON vb.venue_id = v.id WHERE vb.status = 1 ORDER BY vb.datetime ASC LIMIT 5");
                    $upcomingEvents = $upcomingEventsQuery->fetch_all(MYSQLI_ASSOC);
                    ?>

                    <!-- Booking Stats with Animation -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card new-booking fade-in">
                                <div class="card-body">
                                    <h5>Total Verification Bookings</h5>
                                    <p class="count-up" data-count="<?php echo $verificationCount; ?>">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card confirmed-booking fade-in">
                                <div class="card-body">
                                    <h5>Total Confirmed Bookings</h5>
                                    <p class="count-up" data-count="<?php echo $confirmedBookingCount; ?>">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card canceled-booking fade-in">
                                <div class="card-body">
                                    <h5>Total Cancelled Bookings</h5>
                                    <p class="count-up" data-count="<?php echo $canceledBookingCount; ?>">0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events with Slide-in Animation -->
                    <h3 class="mb-4 fade-in">Upcoming Events</h3>
                    <div class="row">
                        <?php if (!empty($upcomingEvents)): ?>
                        <?php foreach ($upcomingEvents as $event): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card event-card">
                                <div class="card-body">
                                    <h5><?php echo $event['venue']; ?></h5>
                                    <p>Event Date: <?php echo date('F j, Y', strtotime($event['datetime'])); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <p>No upcoming events found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Graphs & Charts -->
                <h3 class="mb-4">Booking Statistics</h3>
                <canvas id="bookingChart"></canvas>

                <!-- Calendar -->
                <h3 class="mt-5">Calendar</h3>
                <div id="calendar"></div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>



<script>
// Reveal elements on scroll
function revealOnScroll() {
    let elements = document.querySelectorAll('.fade-in, .event-card');
    let windowHeight = window.innerHeight;
    elements.forEach((el, index) => {
        let position = el.getBoundingClientRect().top;
        if (position < windowHeight - 50) {
            setTimeout(() => {
                el.classList.add('show');
            }, index * 200);
        }
    });
}
document.addEventListener('DOMContentLoaded', revealOnScroll);
document.addEventListener('scroll', revealOnScroll);

// Count-Up Animation
function countUpAnimation() {
    document.querySelectorAll('.count-up').forEach(el => {
        let target = parseInt(el.getAttribute('data-count'));
        let count = 0;
        let increment = Math.ceil(target / 100);
        let interval = setInterval(() => {
            count += increment;
            if (count >= target) {
                count = target;
                clearInterval(interval);
            }
            el.textContent = count;
        }, 20);
    });
}
document.addEventListener('DOMContentLoaded', countUpAnimation);

document.addEventListener("DOMContentLoaded", function() {
    // Booking Statistics Chart
    const ctx = document.getElementById("bookingChart").getContext("2d");
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["New Bookings", "Confirmed", "Cancelled"],
            datasets: [{
                label: "Number of Bookings",
                data: [<?php echo $verificationCount; ?>, <?php echo $confirmedBookingCount; ?>,
                    <?php echo $canceledBookingCount; ?>
                ],
                backgroundColor: ["#FFDBBB", "#D1FFBD", "#ffcccb"],
                borderColor: ["#E89A6A", "#86C76E", "#D16A6A"],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Calendar Initialization
    var calendarEl = document.getElementById("calendar");
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        events: [
            <?php foreach ($upcomingEvents as $event): ?> {
                title: "<?php echo $event['venue']; ?>",
                start: "<?php echo date('Y-m-d', strtotime($event['datetime'])); ?>",
                color: "#1E90FF"
            },
            <?php endforeach; ?>
        ]
    });
    calendar.render();
});
</script>