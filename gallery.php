<?php $theme = 'darknav';

include 'includes/header.php'; ?>
<?php 

include 'includes/navbar.php'; ?>

<body>
    
<section class="gallery-banner">
    
    <img src="assets/images/home/its our studio Header.png" alt="It's Our Studio - Professional Photography Gallery in Valenzuela City">

</section>

  <div class="gallery-container">
    <header id="gallery-header">
      <h1>The Studio Collection</h1>
      <p>Discover moments captured in their best light.</p>
    </header>

    <!-- Solo Shoots Carousel -->
    <h1 class="gallery-category">Solo Shoots</h1>
    <div class="slider-container">
      <div class="slider-track" id="soloTrack"></div>
      <div class="slider-nav">
        <button class="no-hover" id="soloPrev">&#10094;</button>
        <button class="no-hover" id="soloNext">&#10095;</button>
      </div>
    </div>

    <!-- Duo Shoots Carousel -->
    <h1 class="gallery-category">Duo Shoots</h1>
    <div class="slider-container">
      <div class="slider-track" id="duoTrack"></div>
      <div class="slider-nav">
        <button class="no-hover" id="duoPrev">&#10094;</button>
        <button class="no-hover" id="duoNext">&#10095;</button>
      </div>
    </div>

    <!-- Group Shoots Carousel -->
    <h1 class="gallery-category">Group Shoots</h1>
    <div class="slider-container">
      <div class="slider-track" id="groupTrack"></div>
      <div class="slider-nav">
        <button class="no-hover" id="groupPrev">&#10094;</button>
        <button class="no-hover" id="groupNext">&#10095;</button>
      </div>
    </div>
  </div>


  <button id="back-to-top" class="back-to-top-btn" aria-label="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>



    <script src="js/script.js"></script>
        <script src="js/page-animation.js"></script>
<div id="loading-screen"></div>




</body>

<?php include 'includes/footer.php'; ?>