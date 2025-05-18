<?php $theme = 'darknav';

include 'includes/header.php'; ?>
<?php

include 'includes/navbar.php';
?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "item": {
        "@type": "Service",
        "name": "Solo Package",
        "offers": {
          "@type": "Offer",
          "price": 299,
          "priceCurrency": "PHP"
        },
        "serviceOutput": {
          "@type": "Thing",
          "name": "Photography Package",
          "description": "10 minutes studio shoot, 5 minutes photo selection, 1 Background selection, 10 Raw soft copies, 1 4R print"
        }
      }
    },
    {
      "@type": "ListItem",
      "position": 2,
      "item": {
        "@type": "Service",
        "name": "Basic Package",
        "offers": {
          "@type": "Offer",
          "price": 399,
          "priceCurrency": "PHP"
        },
        "serviceOutput": {
          "@type": "Thing",
          "name": "Photography Package",
          "description": "Good for 1-2 Pax, 15 minutes studio shoot, 10 minutes photo selection, 1 Background selection, 15 Raw soft copies, 2 strips print"
        }
      }
    },
    {
      "@type": "ListItem",
      "position": 3,
      "item": {
        "@type": "Service",
        "name": "Family Package",
        "offers": {
          "@type": "Offer",
          "price": 1249,
          "priceCurrency": "PHP"
        },
        "serviceOutput": {
          "@type": "Thing",
          "name": "Photography Package",
          "description": "Good for 4-5 Pax, 30 minutes studio shoot, unlimited background selection, soft copies of all raw photos"
        }
      }
    }
  ]
}
</script>

<body>
    <section class="pricings-container">

        <!-- SOLO PACKAGE -->
        <article class="package">
            <div class="package-content">
                <div class="package-title" onclick="togglePackage(this)">
                    
                    <h1 class="package-name">Solo Package - 299 <span class="toggle-icon"><svg
                                xmlns="http://www.w3.org/2000/svg" height="64px" viewBox="0 -960 960 960" width="64px"
                                fill="#8b5e3b">
                                <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
                            </svg></span></h1>
                </div>
                <ul class="package-features">
                    <li>Good for 1 Pax</li>
                    <li>10 minutes studio shoot</li>
                    <li>5 minutes photo selection</li>
                    <li>1 Background selection</li>
                    <li>10 Raw soft copies</li>
                    <li>1 4R print</li>
                </ul>
      <button onclick="window.location.href='booking/index.php'" class="offers-button services-button">Book Now!</button>
            </div>
            <div class="package-images">
                <div class="photo-sample">
                    <img src="assets/images/services/solo1.webp" alt="Solo Package Sample - Individual Portrait Photography">
                </div>
                <div class="photo-sample">
                    <img src="assets/images/services/solo2.webp" alt="Solo Package Sample - Professional Studio Portrait">
                </div>
            </div>

            <div class="scroll-hint">
                <svg viewBox="0 0 800 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Curved path for the scroll indicator -->
                    <path d="M10,95 C160,95 240,10 400,10 C560,10 640,95 790,95" fill="#fff4e6" fill-opacity="0.5"
                        stroke="#ada3a4" stroke-width="3" />
                </svg>

                <span class="scroll-hint-text upper">
                    Scroll to See More
                </span>
                <span class="scroll-hint-arrow upper">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="512" height="512" x="0" y="0" viewBox="0 0 128 128"
                        style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                        <g>
                            <path
                                d="M64 88a3.988 3.988 0 0 1-2.828-1.172l-40-40c-1.563-1.563-1.563-4.094 0-5.656s4.094-1.563 5.656 0L64 78.344l37.172-37.172c1.563-1.563 4.094-1.563 5.656 0s1.563 4.094 0 5.656l-40 40A3.988 3.988 0 0 1 64 88z"
                                fill="#000000" opacity="1" data-original="#3b2c28" class=""></path>
                        </g>
                    </svg>
                </span>
            </div>

        </article>

        <!-- BASIC PACKAGE -->
        <article class="package-even package">
    <div class="package-images">
        <div class="photo-sample">
            <img src="assets/images/services/basic1.webp" alt="Basic Package Sample - Professional Portrait for 1-2 People">
        </div>
        <div class="photo-sample">
            <img src="assets/images/services/basic2.webp" alt="Basic Package Sample - Studio Photography with Props">
        </div>
    </div>
    <div class="package-content-even">
        <!-- Added onclick attribute for toggle functionality -->
        <div class="package-title" onclick="togglePackage(this)">
            <h1 class="package-name">Basic Package - 399 <span class="toggle-icon"><svg
                        xmlns="http://www.w3.org/2000/svg" height="64px" viewBox="0 -960 960 960" width="64px"
                        fill="#8b5e3b">
                        <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
                    </svg></span></h1>
        </div>
        <ul class="package-features">
            <li>Good for 1-2 Pax</li>
            <li>15 minutes studio shoot</li>
            <li>10 minutes photo selection</li>
            <li>1 Background selection</li>
            <li>15 Raw soft copies</li>
            <li>2 strips print</li>
            <li><span>Free use of props and <br> selected wardrobe</span></li>
        </ul>
      <button onclick="window.location.href='booking/index.php'" class="offers-button services-button">Book Now!</button>
    </div>
</article>


        <!-- JUST TRANSFER PACKAGE -->
        <article class="package">
            <div class="package-content">
                <div class="package-title" onclick="togglePackage(this)">
                    
                    <h1 class="package-name">Just Transfer - 549 <span class="toggle-icon"><svg
                                xmlns="http://www.w3.org/2000/svg" height="64px" viewBox="0 -960 960 960" width="64px"
                                fill="#8b5e3b">
                                <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
                            </svg></span></h1>
                </div>
                <ul class="package-features">
                    <li>Good for 1-2 Pax</li>
                    <li>30 minutes studio shoot</li>
                    <li>Soft copy of all raw photos</li>
                    <li><span>Free use of props and <br> selected wardrobe</span></li>
                </ul>
      <button onclick="window.location.href='booking/index.php'" class="offers-button services-button">Book Now!</button>
            </div>
            <div class="package-images">
                <div class="photo-sample">
                    <img src="assets/images/services/just_transfer1.webp" alt="Just Transfer Package - All Raw Photos Included">
                </div>
                <div class="photo-sample">
                    <img src="assets/images/services/just_transfer2.webp" alt="Just Transfer Package - 30 Minute Studio Session">
                </div>
            </div>

        </article>

        <!-- STANDARD PACKAGE -->
        <article class="package-even package">

            <div class="package-images">
                <div class="photo-sample">
                    <img src="assets/images/services/standard1.webp" alt="Standard Package - Premium Studio Photography">
                </div>
                <div class="photo-sample">
                    <img src="assets/images/services/standard2.webp" alt="Standard Package - Multiple Background Options">
                </div>
            </div>
            <div class="package-content-even">
        <div class="package-title" onclick="togglePackage(this)">
            <h1 class="package-name">Standard Package - 699 <span class="toggle-icon"><svg
                        xmlns="http://www.w3.org/2000/svg" height="64px" viewBox="0 -960 960 960" width="64px"
                        fill="#8b5e3b">
                        <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
                    </svg></span></h1>
        </div>
                <ul class="package-features">
                    <li>Good for 1-2 Pax</li>
                    <li>30 minutes studio shoot</li>
                    <li>15 minutes photo selection</li>
                    <li>Unlimited background selection</li>
                    <li>Soft copies of all raw photos</li>
                    <li>2 strips print</li>
                    <li>2 4r print</li>
                    <li><span>Free use of props and <br> selected wardrobe</span></li>
                </ul>
      <button onclick="window.location.href='booking/index.php'" class="offers-button services-button">Book Now!</button>
            </div>


        </article>



        <!-- FAMILY PACKAGE -->
        <article class="package">
            <div class="package-content">
                <div class="package-title" onclick="togglePackage(this)">
                    
                    <h1 class="package-name">Family Package - 1,249 <span class="toggle-icon"><svg
                                xmlns="http://www.w3.org/2000/svg" height="64px" viewBox="0 -960 960 960" width="64px"
                                fill="#8b5e3b">
                                <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
                            </svg></span></h1>
                </div>
                <ul class="package-features">
                    <li>Good for 4-5 Pax</li>
                    <li>30 minutes studio shoot</li>
                    <li>20 minutes photo selection</li>
                    <li>Unlimited background selection</li>
                    <li>Soft copies of all raw photos</li>
                    <li>4 strips print</li>
                    <li>2 A5 print and 2 4R prints</li>
                    <li><span>Free use of props and <br> selected wardrobe</span></li>
                </ul>
      <button onclick="window.location.href='booking/index.php'" class="offers-button services-button">Book Now!</button>
            </div>
            <div class="package-images">
                <div class="photo-sample">
                    <img src="assets/images/services/family1.webp" alt="Family Package - Professional Family Portrait Photography">
                </div>
                <div class="photo-sample">
                    <img src="assets/images/services/family2.webp" alt="Family Package - Group Photos for 4-5 People">
                </div>
            </div>

        </article>

        <!-- BARKADA PACKAGE -->
        <article class="package-even package">

            <div class="package-images">
                <div class="photo-sample">
                    <img src="assets/images/services/barkada1.webp" alt="Barkada Package - Friends Group Photography for 8 People">
                </div>
                <div class="photo-sample">
                    <img src="assets/images/services/barkada2.webp" alt="Barkada Package - Creative Group Portrait Session">
                </div>
            </div>
            <div class="package-content-even">
        <div class="package-title" onclick="togglePackage(this)">
            <h1 class="package-name">Barkada Package - 1,949 <span class="toggle-icon"><svg
                        xmlns="http://www.w3.org/2000/svg" height="64px" viewBox="0 -960 960 960" width="64px"
                        fill="#8b5e3b">
                        <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
                    </svg></span></h1>
        </div>
                <ul class="package-features">
                    <li>Good for 8 Pax</li>
                    <li>30 minutes studio shoot</li>
                    <li>20 minutes photo selection</li>
                    <li>Unlimited background selection</li>
                    <li>Soft copies of all raw photos</li>
                    <li>8 strips print</li>
                    <li>2 A5 2 4R prints</li>
                    <li><span>Free use of props and <br> selected wardrobe</span></li>
                </ul>
      <button onclick="window.location.href='booking/index.php'" class="offers-button services-button">Book Now!</button>
            </div>


        </article>


        <!-- Special Promo -->
        <article class="package">

            <div class="package-content">
                <div class="package-title" onclick="togglePackage(this)">
                    
                    <h1 class="package-name">Special Promo: <br>It's Your Birthday! - 599 <span class="toggle-icon"><svg
                                xmlns="http://www.w3.org/2000/svg" height="64px" viewBox="0 -960 960 960" width="64px"
                                fill="#8b5e3b">
                                <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
                            </svg></span></h1>
                </div>
                <ul class="package-features bday-features">
                    <li>Max of 2 pax</li>
                    <li>30 minutes studio shoot</li>
                    <li>15 minutes photo selection</li>
                    <li>Unlimited background selection</li>
                    <li>30 Raw soft copies</li>
                    <li>2 strips print</li>
                    <li>1 4R print</li>
                    <li><span>Free use of props and <br> selected wardrobe</span></li>

                    <li class="reminder"><span>Only applicable for a week, before, after <br> or on the exact
                            birthday</span></li>
                    <li class="reminder"><span>Please present one valid ID <br> or PSA</span></li>
                </ul>
      <button onclick="window.location.href='booking/index.php'" class="offers-button services-button">Book Now!</button>
            </div>

            <div class="package-images">
                <div class="photo-sample">
                    <img src="assets/images/services/bday1.webp" alt="Birthday Special Promo - Celebration Photography Package">
                </div>
                <div class="photo-sample">
                    <img src="assets/images/services/bday2.webp" alt="Birthday Special Promo - Discounted Birthday Portrait Session">
                </div>
            </div>



        </article>

        <!-- ADDON PACKAGE -->
        <article class="package">
            <div class="package-content">
        <div class="package-title" onclick="togglePackage(this)">
            <h1 class="package-name">Addons</h1>
        </div>
                <ul class="package-addons">
                    <li>Additional Pax - PHP 150</li>
                    <li>Pets - PHP 70</li>
                    <li>5 minutes photo selection</li>
                    <li>Additional wallet size 4 pcs - PHP 60</li>
                    <li>Additional A5 2 pcs - PHP 50</li>
                    <li>Additional Strip size 2pcs - PHP 60</li>
                    <li>Additional 4R size 1 pc - PHP 50</li>
                    <li>Additional A4 size 1pc - PHP 149</li>
                    <li>Additional 15 mins (if there's no appointment after) - PHP 150</li>
                    <li>Year book inspired background w/ exclusive wardrobe - PHP 149</li>
                    <li>Year book inspired background only - PHP 100</li>
                </ul>
      <button onclick="window.location.href='booking/index.php'" class="offers-button services-button">Book Now!</button>
            </div>

            <div class="scroll-to-top">

                <svg viewBox="0 0 800 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Curved path for the scroll indicator -->
                    <path d="M10,95 C160,95 240,10 400,10 C560,10 640,95 790,95" fill="#fff4e6" fill-opacity="0.5"
                        stroke="#ada3a4" stroke-width="3" />
                </svg>

                <span class="scroll-hint-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="512" height="512" x="0" y="0" viewBox="0 0 128 128"
                        style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                        <g transform="rotate(180, 64, 64)">
                            <path
                                d="M64 88a3.988 3.988 0 0 1-2.828-1.172l-40-40c-1.563-1.563-1.563-4.094 0-5.656s4.094-1.563 5.656 0L64 78.344l37.172-37.172c1.563-1.563 4.094-1.563 5.656 0s1.563 4.094 0 5.656l-40 40A3.988 3.988 0 0 1 64 88z"
                                fill="#000000" opacity="1" data-original="#3b2c28" class=""></path>
                        </g>
                    </svg>
                </span>
                <span class="scroll-hint-text">
                    <a href="#top" class="scroll-to-top-link" style="text-decoration: none;">
                        <button class="scroll-to-top-button no-hover">Back to top</button>
                    </a>
                </span>

            </div>

        </article>

    </section>


    <button id="back-to-top" class="back-to-top-btn" aria-label="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>


    <script src="js/page-animation.js"></script>

    <script src="js/script.js"></script>
    <div id="loading-screen"></div>


</body>

<?php include 'includes/footer.php'; ?>