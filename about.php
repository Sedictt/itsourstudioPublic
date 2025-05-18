<?php 
$theme = 'darknav';
include 'includes/header.php'; ?>
<?php

include 'includes/navbar.php';
?>

<body>
    <!-- about landing -->
    <section class="about-section">
       
        <div class="about-title">
        <h1>ABOUT US</h1>
        <h2>KNOW US BETTER</h2>
       </div> 
        
    </section>

     <!-- demo reel -->
    <section class = "reel-section">
        <div class ="design-right-corner">
            <img src="assets/images/about/corner-design.webp">    
        </div>
        <div class ="design-left-corner">
            <img src="assets/images/about/corner-design.webp">
            
        </div>

        <!-- content -->
         <div class="reel-content">
            <div class="reel">
                <div class="reel-bg">
                    <h1>      </h1>
                </div>
                <iframe class="demo-reel-vid" width="270" height="480"
                src="https://www.youtube.com/embed/mm8uIpWgsvg?autoplay=1&loop=1&playlist=mm8uIpWgsvg"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
                </iframe>


            </div>
            <div class="reel-text">
                <h1>It's Our Studio: Capturing Moments, Creating Stories</h1>
                <p>
                    A creative space where every shot tells a story. 
                    From timeless portraits to dynamic visuals, 
                    we bring moments to life with passion and precision.
                     Let’s create something unforgettable together.
                </p>

            </div>
         </div>

    </section>
        
    <!-- WHO WE ARE SECTION -->
    <section class="who-section">
        <img id="who-bg"src="assets/images/about/about4.webp" alt="background image">

        <div class="who-holder">
            <div class="who-text">
                <h1>WHO WE ARE</h1>
                <p>it’s ouR Studio is a self-photography studio where creativity meets freedom. We provide 
                    a unique and fun space where anyone can take professional-quality photos of themselves, 
                    with full control over their session. </p>
            </div>
            <div class="who-image">
                <img src="assets/images/about/about5.webp" alt="about us image">

        </div>
    </section>

    <!-- QUOTE SECTION -->
    <section class="quote-section">
        <div class="quote-holder">
            <p>Just a few laughs, a lot of chaos, 
                and plenty of memories!</p>
        </div>

    </section>

    <!-- OUR STORY SECTION -->
     <section class="story-section">
       <!-- design corners -->
        <div class ="design-right-corner">
            <img src="assets/images/about/corner-design.webp">    
        </div>
        <div class ="design-left-corner">
            <img src="assets/images/about/corner-design.webp">
            
        </div>

        <!-- content -->
         <div class="story-text">
            <h1>OUR STORY</h1>
            <p>it’s ouR Studio was founded by Reggie Labis in 2023 with the belief that everyone deserves to be the photographer
                 of their own moments.The name "It’s Our Studio" reflects our vision of making photography a 
                 personal and empowering experience. </p>
         </div>
         <div class="story-image">
            <div class="story-image-bg">
                <!-- odd shape -->
            </div>
            <img id= "story-img" src="assets/images/about/about1.webp" alt="story image">
            
         </div>
     </section>

     <!-- LOCATION SECTION -->
     <section class="location-section">
        <div class="location-holder">
            <!-- LOC IMAGES -->
            <div class="location-image">
                <img id="loc-img1"src="assets/images/about/about4.webp" alt="location image">
                <img id="loc-img2"src="assets/images/about/about2.webp" alt="">
    
            </div>

             <!-- LOC TEXT -->
             <div class="location-text">
                <h1>WE ARE LOCATED AT</h1>
                <p>FJ Center 15 Tongco Street, Barangay Maysan, 
                    Valenzuela City</p>
                <h2>LANDMARKS</h2>
                <P>
                    PLV, Cebuana, Mr. DIY, Ever Supermarket
                </P>
                <button class="locate-button" onclick="window.location.href='contact.php#contact-loc'">LOCATE</button>
                

            </div>
        </div>
        
     </section>

     <button id="back-to-top" class="back-to-top-btn" aria-label="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>


</body>

    <script src="js/page-animation.js"></script>
<div id="loading-screen"></div>



<?php include 'includes/footer.php'; ?>