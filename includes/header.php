<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php
    // Dynamic SEO titles and descriptions based on current page
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    
    switch($current_page) {
        case 'index':
            $page_title = "it's ouR Studio | Professional Photography Studio in Valenzuela City";
            $page_description = "Professional photo studio in Valenzuela City offering solo shoots, family portraits, and group photography sessions. Book your photography session today!";
            break;
        case 'services':
            $page_title = "Photography Services & Packages | it's ouR Studio";
            $page_description = "Choose from our affordable photography packages including solo shoots, family portraits, barkada sessions and special birthday promos. Quality studio photography in Valenzuela.";
            break;
        case 'about':
            $page_title = "About it's ouR Studio | Professional Photographers in Valenzuela";
            $page_description = "Learn about it's ouR Studio photography team, our story, and our commitment to capturing your best moments in our Valenzuela City studio.";
            break;
        case 'gallery':
            $page_title = "Photo Gallery | it's ouR Studio Portfolio";
            $page_description = "Browse our photography portfolio featuring solo, duo, and group photography sessions at it's ouR Studio in Valenzuela City.";
            break;
        case 'contact':
            $page_title = "Contact it's ouR Studio | Book Your Photo Session";
            $page_description = "Get in touch with it's ouR Studio in Valenzuela City. Book your photography session or inquire about our services today!";
            break;
        default:
            $page_title = "it's ouR Studio | Professional Photography in Valenzuela City";
            $page_description = "Professional photo studio in Valenzuela City offering quality photography services at affordable prices. Book your session now!";
            break;
    }
    ?>
    
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="photography studio, Valenzuela City, photo studio, professional photography, portrait photography, family photos, solo shoots, affordable studio, it's ouR Studio">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:image" content="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/images/logo/og-image.jpg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo $page_title; ?>">
    <meta property="twitter:description" content="<?php echo $page_description; ?>">
    <meta property="twitter:image" content="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/images/logo/og-image.jpg">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <!-- Preload critical assets -->
    <link rel="preload" href="css/style.css" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=League+Spartan:wght@700&family=Quicksand:wght@400;700&display=swap" as="font" crossorigin>

    <!-- Link to CSS for styling -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/logo/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/logo/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/logo/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=League+Spartan:wght@100..900&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">

    <!-- Schema.org structured data for Local Business -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "PhotographyBusiness",
        "name": "it's ouR Studio",
        "image": "https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/images/logo/LOGO_var1.png",
        "url": "https://<?php echo $_SERVER['HTTP_HOST']; ?>",
        "telephone": "+63 905 336 7103",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "FJ Center 15 Tongco Street, Maysan",
            "addressLocality": "Valenzuela City",
            "postalCode": "",
            "addressRegion": "Metro Manila",
            "addressCountry": "PH"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "14.698870",
            "longitude": "120.976764"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
                "Sunday"
            ],
            "opens": "10:00",
            "closes": "18:00"
        },
        "sameAs": [
            "https://www.facebook.com/itsourstudio1",
            "https://www.instagram.com/itsourstudio1"
        ],
        "priceRange": "₱299 - ₱1,949"
    }
    </script>

    <script src="js/sidebar.js" defer></script>
    <script src="js/script.js" defer></script>

</head>

<body class="<?php echo $theme; ?>">
    

