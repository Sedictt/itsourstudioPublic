<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<body>
<section class="hero-section">
    <div class="carousel-container">
      <div class="carousel-track" id="carouselTrack">
        <!-- Manually added slides -->
        <div class="carousel-slide">
          <img src="assets/images/hero-carousel/carousel_1.jpg" alt="Slide 1" class="carousel-image">
          <div class="carousel-overlay"></div>
        </div>
        
        <div class="carousel-slide">
          <img src="assets/images/hero-carousel/carousel_2.jpg" alt="Slide 2" class="carousel-image">
          <div class="carousel-overlay"></div>
        </div>
        
        <div class="carousel-slide">
          <img src="assets/images/hero-carousel/carousel_3.jpg" alt="Slide 3" class="carousel-image">
          <div class="carousel-overlay"></div>
        </div>
        
        <div class="carousel-slide">
          <img src="assets/images/hero-carousel/carousel_4.jpg" alt="Slide 4" class="carousel-image">
          <div class="carousel-overlay"></div>
        </div>
        
        <div class="carousel-slide">
          <img src="assets/images/hero-carousel/carousel_5.jpg" alt="Slide 5" class="carousel-image">
          <div class="carousel-overlay"></div>
        </div>
        
        <!-- Clone of first slide for infinite effect (will be handled by JS) -->
      </div>
    </div>
    
    <div class="carousel-content">
      <h1>BE THE PHOTOGRAPHER, BE THE <br> STAR , CAPTURE YOUR BEST <br> MOMENTS</h1>
      <div class="carousel-btn-container">
      <button onclick="window.location.href='gallery.php'" class="carousel-button ">VIEW GALLERY</button>
      <button onclick="window.location.href='services.php'" class="carousel-button">VIEW SERVICES</button>
      </div>
  
    </div>
  </section>

  <section class="features-section" aria-labelledby="studio-features-heading">
    <h1 id="studio-features-heading" class="features-heading">Why Our Studio Stands Out</h1>
    
    <ul class="features-list">
      <!-- Affordable Pricing -->
      <li class="feature-item">
        <figure>
          <img class="feature-icon" src="assets/images/icons/discount.png" alt="">
        </figure>
        <h3 class="feature-title">Affordable Pricing</h3>
        <p class="feature-description">We offer competitive pricing structure, ensuring that our clients get the best value for their money.</p>
      </li>

      <!-- High Quality Photos -->
      <li class="feature-item">
        <figure>
        <img class="feature-icon" src="assets/images/icons/camera.png" alt="">
        </figure>
        <h3 class="feature-title">High Quality Photos</h3>
        <p class="feature-description">We've invested in the best equipment to ensure your photos are sharp, detailed, and of the highest quality.</p>
      </li>

      <!-- Privacy and Comfort -->
      <li class="feature-item">
        <figure>
        <img class="feature-icon" src="assets/images/icons/heart.png" alt="">
        </figure>
        <h3 class="feature-title">Privacy and Comfort</h3>
        <p class="feature-description">Enjoy a private and comfortable environment where you can be yourself.</p>
      </li>

      <!-- Pet Friendly -->
      <li class="feature-item">
        <figure>
        <img class="feature-icon"src="assets/images/icons/paw.png" alt="">
        </figure>
        <h3 class="feature-title">Pet Friendly</h3>
        <p class="feature-description">Creating timeless memories with you and your furry companions—because pets are family too!</p>
      </li>
    </ul>
  </section>

  <section class="testimonials-section" aria-labelledby="testimonials-heading">
    <h2 id="testimonials-heading" class="testimonials-heading">Our Clients Love it!</h2>
    
    <div class="testimonials-container">
      <!-- Testimonial 1 -->
      <article class="testimonial-card">
      <div class="avatar">
      <img src="assets\images\hero-carousel\carousel_1.jpg" alt="">
        </div>
        <blockquote class="testimonial-quote">
          <div class="quote-mark"><img src="assets\images\icons\text.png" alt=""></div>
          <p class="testimonial-text">
            "Highly recommended if naghahanap kayo ng aesthetics and magandang quality ng studio, I will surely back again!"
          </p>
        </blockquote>
      </article>
      
      <!-- Testimonial 2 -->
      <article class="testimonial-card">
        <div class="avatar">
        <img src="assets\images\hero-carousel\carousel_1.jpg" alt="">
        </div>
        <blockquote class="testimonial-quote">
        <div class="quote-mark"><img src="assets\images\icons\text.png" alt=""></div>
        <p class="testimonial-text">
            The price is so budget-friendly & studio is so super minimalist lang! I love it! Babalikan talaga dahil super linis
          </p>
        </blockquote>
      </article>
      
      <!-- Testimonial 3 -->
      <article class="testimonial-card">
      <div class="avatar">
      <img src="assets\images\hero-carousel\carousel_1.jpg" alt="">
      </div>
        <blockquote class="testimonial-quote">
        <div class="quote-mark"><img src="assets\images\icons\text.png" alt=""></div>

          <p class="testimonial-text">
            Highly Recommended Surely will be back with our kids na daming props and super accomodating nila Ma'am
          </p>
        </blockquote>
      </article>

    </div>
  </section>

  <section class="offer-banner">
    <div class="banner-container">
      <div class="banner-left">
        <h1 class="banner-heading">See What We Have to Offer</h1>
      </div>
      <div class="banner-right">
        <p class="banner-text">Take your photos to a whole new level with our affordable packages</p>
        <a href="services.php" class="banner-link">
          Check all of our offers
          <svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </a>
      </div>
    </div>
  </section>

  <section class="offers">
  <div class="pricing-container">
    <!-- Solo Package -->
    <article class="pricing-plan">
      <header class="plan-header">
        <h2 class="plan-name">Solo Package - 299</h2>
        <p class="plan-audience">1 Pax</p>
      </header>
      <ul class="offers-list">
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>Good for 1 Pax</span>
        </li>
        <li class="offers-item">
          <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
          <span>10 minutes studio shoot</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>5 minutes photo selection</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>1 Background selection</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>10 Raw soft copies</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>1 4r print</span>
        </li>
      </ul>
      <button onclick="window.location.href='booking/index.php'" class="offers-button">Book Now!</button>

    </article>

    <!-- Basic Package -->
    <article class="pricing-plan">
      <header class="plan-header">
        <h2 class="plan-name">Basic Package - 399</h2>
        <p class="plan-audience">1-2 Pax</p>
      </header>
      <ul class="offers-list">
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>Good for 1-2 Pax</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>15 minutes studio shoot</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>10 minutes photo selection</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>1 Background selection</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>15 Raw soft copies</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>2 Strips print</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>Free use of props and selected wardrobe</span>
        </li>

      </ul>

      <button onclick="window.location.href='booking/index.php'" class="offers-button">Book Now!</button>

    </article>

    <!-- Barkada Package -->
    <article class="pricing-plan">
      <header class="plan-header">
        <h2 class="plan-name">Barkada Package - 1,949</h2>
        <p class="plan-audience">8 Pax</p>
      </header>
      <ul class="offers-list">
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>Good for up to 8 Pax</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>30 minutes studio shoot</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>20 minutes photo selection</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>Unlimited Background selection</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>Soft copies of all raw photos</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>8 Strips print</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>4 A5 prints and 2 4r prints</span>
        </li>
        <li class="offers-item">
        <span class="offers-icon"><img src="assets/images/icons/check-mark.png" alt=""></span>
        <span>Free use of props and selected wardrobe</span>
        </li>


      </ul>

      <button onclick="window.location.href='booking/index.php'" class="offers-button">Book Now!</button>

    </article>
  </div>
  </section>

    <!-- HOW IT WORKS -->
  <section class="steps-section">
      <h1 id="how-it-works-title" class="section-heading">
        Step Into the Spotlight: How It Works
        <span class="camera-icon" role="img" aria-label="Camera">📷</span>
      </h1>
      
      <ol class="process-steps">
        <!-- Step 1 -->
        <li class="step-card">
          <span class="step-number" aria-hidden="true">01</span>
          <div class="step-content">
            <h2 class="step-heading">Choose & Book</h2>
            <p class="step-description">
              Select your preferred photography package and book your session online with secure payment.
            </p>
          </div>
        </li>
        
        <!-- Step 2 -->
        <li class="step-card">
          <span class="step-number" aria-hidden="true">02</span>
          <div class="step-content">
            <h2 class="step-heading">Arrive & Get Ready</h2>
            <p class="step-description">
              Arrive at the studio and use our on-site facilities to prepare for your self-shoot session.
            </p>
          </div>
        </li>
        
        <!-- Step 3 -->
        <li class="step-card">
          <span class="step-number" aria-hidden="true">03</span>
          <div class="step-content">
            <h2 class="step-heading">Shoot & Select</h2>
            <p class="step-description">
              Capture your photos at your own pace using our professional setup, then review and choose your favorites.
            </p>
          </div>
        </li>
        
        <!-- Step 4 -->
        <li class="step-card">
          <span class="step-number" aria-hidden="true">04</span>
          <div class="step-content">
            <h2 class="step-heading">Receive & Share</h2>
            <p class="step-description">
              Download digital copies instantly or receive prints as per your package—then share your stunning shots!
            </p>
          </div>
        </li>
      </ol>
    </section>

    <section class="photo-gallery">
            <!-- Photo Item 1 - Person with beret -->
            <article class="photo-item">
                <img src="assets/images/home/seemore_1.webp" alt="" class="seemore-photo">
            </article>
            
            <!-- Photo Item 2 - Person with flowers -->
            <article class="photo-item">
                <img src="assets/images/home/seemore_2.webp" alt="" class="seemore-photo">
            </article>
            
            <!-- Photo Item 3 - Person in hoodie -->
            <article class="photo-item">
                <img src="assets/images/home/seemore_3.webp" alt="" class="seemore-photo">
            </article>
            
            <!-- Photo Item 4 - Dog with balloon -->
            <article class="photo-item">
                <img src="assets/images/home/seemore_5.webp" alt="" class="seemore-photo">
            </article>
            
            <!-- Photo Item 5 - Person with hand on face -->
            <article class="photo-item">
                <img src="assets/images/home/seemore_7.webp" alt="" class="seemore-photo">
            </article>
        </section>
        
        <div class="view-more-container">
            <button onclick="window.location.href='gallery.php'" class="view-more-btn">View More</button>
        </div>

    <button id="back-to-top" class="back-to-top-btn" aria-label="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>

    <script src="js/home.js"></script>
    <script src="js/page-animation.js"></script>
    <script src="js/script.js"></script>
<div id="loading-screen"></div>

</body>
<?php include 'includes/footer.php'; ?>