<?php
// Start session to track user login status
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUSTAKA SYAFIE BOOKSTORE</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Slideshow Styles */
        .slideshow-container {
            position: relative;
            max-width: 100%;
            margin: auto;
        }

        .mySlides {
            display: none;
            position: absolute;
            width: 100%;
            height: auto;
        }

        .text {
            position: absolute;
            bottom: 8px;
            left: 16px;
            color: #fff;
            font-size: 20px;
            padding: 8px 12px;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .numbertext {
            position: absolute;
            top: 0;
            right: 0;
            color: #fff;
            font-size: 12px;
            padding: 8px;
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.3s;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }

        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .dot {
            height: 15px;
            width: 15px;
            margin: 0 4px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active {
            background-color: #717171;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
            <a href="home.php">
                <i class="fas fa-book-reader logo-icon"></i>
                <div>
                    <h1>PUSTAKA SYAFIE</h1>
                    <p>BOOKSTORE</p>
                </div>
            </a>
        </div>
        <div class="search-bar">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search for books, authors, or categories..." required />
                <button type="submit">Search</button>
            </form>
        </div>
        <!-- Welcome Message -->
        <div class="welcome-message">
            <?php
            if (isset($_SESSION['userName'])) {
                echo "<p>Welcome, " . htmlspecialchars($_SESSION['userName']) . "!</p>";
            } else {
                echo "<p>Welcome, Guest!</p>";
            }
            ?>
        </div>
        <div class="header-icons">
            <a href="cart.php" class="icon">
                <i class="fas fa-shopping-cart"></i>
                <span>Cart</span>
            </a>
            <a href="wishlist.php" class="icon">
                <i class="fas fa-heart"></i>
                <span>Wishlist</span>
            </a>
            <a href="accInformation.php" class="icon">
                <i class="fas fa-cogs"></i>
                <span>My Account</span>
            </a>
            <a href="contact.php" class="icon">
                <i class="fas fa-envelope"></i>
                <span>Contact</span>
            </a>
        </div>
    </header>

    <!-- Banner Section -->
    <section class="banner">
        <div class="banner-content">
            <h2>Welcome to Pustaka Syafie!</h2>
            <p>Explore a world of knowledge and stories.</p>
            <button class="btn btn-primary" onclick="window.location.href='shop.php';">Shop Now</button>
        </div>
    </section>

    <!-- Popular Categories Section -->
    <section class="categories">
        <h2>Popular Categories</h2>
        <!-- Slideshow container -->
        <div class="slideshow-container">
            <!-- Full-width images with number and caption text -->
            <div class="mySlides fade">
                <div class="numbertext">1 / 5</div>
                <img src="images/crime.png" style="width:50%">
                <div class="text">Crime</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">2 / 5</div>
                <img src="images/nonfiction.png" style="width:50%">
                <div class="text">Non-Fiction</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">3 / 5</div>
                <img src="images/islamic.png" style="width:50%">
                <div class="text">Islamic</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">6 / 5</div>
                <img src="images/revision.png" style="width:50%">
                <div class="text">Revision</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">5 / 5</div>
                <img src="images/romance.png" style="width:50%">
                <div class="text">Romance</div>
            </div>

            <!-- Next and previous buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
        <br>

        <!-- The dots/circles -->
        <div style="text-align:center">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <span class="dot" onclick="currentSlide(5)"></span>
        </div>
    </section>

    <!-- Slideshow JavaScript -->
    <script>
// Slideshow JavaScript
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
}

// Auto slideshow (optional)
let slideInterval = setInterval(function() {
    plusSlides(1);
}, 5000); // Change slide every 5 seconds
    </script>

    <!-- Featured Products Section -->
    <section class="featured-products">
        <h2>Featured Books</h2>
        <div class="product-list">
            <div class="product-item">
                <img src="images/genre/islamic/alquran.jpg" alt="Book 1">
                <p>AL-QURAN</p>
                <p>RM80</p>
                <button class="btn btn-primary">Add to Cart</button>
            </div>
            <!-- Repeat the above block for other featured products -->
        </div>
    </section>

	</script>
<!-- Customer Reviews Section -->
<section class="customer-reviews">
        <h2>What Our Customers Say</h2>
        <div class="reviews">
            <div class="review-item">
                <p>"A wonderful selection of books! Great prices and fast delivery."</p>
                <h4>- Sarah L.</h4>
            </div>
            <div class="review-item">
                <p>"My go-to bookstore for all my academic needs. Always reliable."</p>
                <h4>- James M.</h4>
            </div>
            <div class="review-item">
                <p>"Love the kids' books section! My children can spend hours choosing their next read."</p>
                <h4>- Emma K.</h4>
            </div>
        </div>
    </section>

	<!-- Featured Products Section -->
<section class="featured-products">
    <h2>Featured Books</h2>
    <div class="product-list">
        <div class="product-item">
            <img src="images/genre/islamic/alquran.jpg" alt="Book 1">
            <p>AL-QURAN</p>
            <p>RM 25.00</p>
        </div>
        <div class="product-item">
            <img src="images/genre/islamic/ohAllah.jpg" alt="Book 2">
            <p>OH ALLAH</p>
            <p>RM 15.00</p>
        </div>
        <div class="product-item">
            <img src="images/genre/islamic/doadanzikir.jpg" alt="Book 3">
            <p>DOA DAN ZIKIR</p>
            <p>RM 10.00</p>
        </div>
    </div>
</section>

    <!-- Back-to-Top Button -->
    <button class="back-to-top">↑</button>
    <script>
        var mybutton = document.querySelector(".back-to-top");
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        };
        mybutton.addEventListener("click", function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <ul>
                <li><a href="faq.html">FAQs</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="terms.html">Terms & Conditions</a></li>
                <li><a href="userManual.html">User Manual</a></li>
            </ul>
            <div class="social-media">
                <a href="#">Facebook</a> | <a href="#">Twitter</a> | <a href="#">Instagram</a>
            </div>
            <p>Copyright © 2024 PUSTAKA SYAFIE BOOK CO. (MALAYSIA) SDN. BHD. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>


