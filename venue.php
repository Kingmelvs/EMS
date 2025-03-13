<?php 
include 'admin/db_connect.php'; 
?>
<style>
#portfolio .img-fluid {
    width: calc(100%);
    height: 30vh;
    z-index: -1;
    position: relative;
    padding: 1em;
}

.venue-list {
    display: flex;
    flex-direction: column;
    background: linear-gradient(135deg, #1c1c1c, #292929);
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    border: 1px solid #444;
    margin-bottom: 20px;
    height: 100%;
}

.venue-list .carousel img.d-block.w-100 {
    min-height: 230px;
    max-height: 230px;
    object-fit: cover;
    border-radius: 15px 15px 0 0;
    transition: transform 0.3s ease-in-out;
}

.venue-list .carousel img:hover {
    transform: scale(1.05);
}

.venue-list .card-body {
    text-align: center;
    padding: 20px;
}

.venue-list:hover {
    transform: scale(1.03);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);
}

/* Book Button */
.book-venue {
    background: #ff4757;
    color: #fff;
    padding: 12px 25px;
    border-radius: 30px;
    font-weight: bold;
    text-transform: uppercase;
    transition: background 0.3s, transform 0.3s;
    box-shadow: 0 5px 20px rgba(255, 71, 87, 0.4);
    margin-top: 15px;
}

.book-venue:hover {
    background: #e84118;
    transform: scale(1.1);
    box-shadow: 0 10px 30px rgba(255, 71, 87, 0.6);
}

/* Text Customization */
h3 {
    color: #f1f1f1;
    font-size: 22px;
    font-weight: bold;
}

.small-text {
    color: #dcdcdc;
    font-size: 14px;
}

/* Padding Bottom (space between each card) */
.row-items .col-md-6 {
    padding-bottom: 20px;
}

/* Smooth Animation */
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

.row-items {
    animation: fadeIn 1.5s ease-in-out;
}

/* Carousel Control */
.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: #ff4757;
    border-radius: 50%;
    padding: 10px;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    transform: scale(1.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .venue-list {
        flex-direction: column;
    }

    .venue-list .carousel img.d-block.w-100 {
        min-height: 200px;
    }

    .book-venue {
        width: 100%;
    }
}
</style>

<header class="masthead">
</header>

<div class="container-fluid mt-3 pt-2">
    <h4 class="text-center text-white">Our Stunning Event Venues</h4>
    <hr class="divider">
    <div class="row-items">
        <div class="col-lg-12">
            <div class="row">
                <?php
                $venue = $conn->query("SELECT * from venue order by rand()");
                while($row = $venue->fetch_assoc()):
                ?>
                <div class="col-md-6">
                    <div class="card venue-list" data-id="<?php echo $row['id'] ?>">
                        <div id="imagesCarousel_<?php echo $row['id'] ?>" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <?php 
                                $fpath = 'admin/assets/uploads/venue_'.$row['id'];
                                $images = scandir($fpath);
                                $i = 1;
                                foreach($images as $k => $v):
                                    if(!in_array($v, array('.','..'))):
                                    $active = $i == 1 ? 'active' : '';
                                ?>
                                <div class="carousel-item <?php echo $active ?>">
                                    <img class="d-block w-100" src="<?php echo $fpath.'/'.$v ?>">
                                </div>
                                <?php
                                    $i++;
                                    endif;
                                endforeach;
                                ?>
                                <a class="carousel-control-prev" href="#imagesCarousel_<?php echo $row['id'] ?>" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </a>
                                <a class="carousel-control-next" href="#imagesCarousel_<?php echo $row['id'] ?>" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </a>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h3><?php echo ucwords($row['venue']) ?></h3>
                            <small class="small-text"><i><?php echo $row['address'] ?></i></small>
                            <p class="small-text">Rate Per Hour: <?php echo number_format($row['rate'],2) ?></p>
                            <button class="btn book-venue" data-id='<?php echo $row['id'] ?>'>Book Now</button>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Booking Button Animation
let bookButtons = document.querySelectorAll('.book-venue');
bookButtons.forEach(button => {
    button.addEventListener('click', function() {
        button.innerHTML = 'Processing...';
        button.disabled = true;
        setTimeout(() => {
            button.innerHTML = 'Book Now';
            button.disabled = false;
            uni_modal("Submit Booking Request", "booking.php?venue_id=" + button.getAttribute('data-id'));
        }, 2000);
    });
});
</script>
