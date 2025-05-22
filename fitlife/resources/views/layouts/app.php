<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['fitlife_language'] ?? 'en', ENT_QUOTES, 'UTF-8'); ?>" 
      dir="<?php echo htmlspecialchars(($_SESSION['fitlife_language'] ?? 'en') === 'ar' ? 'rtl' : 'ltr', ENT_QUOTES, 'UTF-8'); ?>"
      class="<?php echo htmlspecialchars($_COOKIE['fitlife_theme'] ?? 'light', ENT_QUOTES, 'UTF-8'); // Default to light theme if not set ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' - ' : ''; ?>FitLife</title>

    <!-- Google Fonts: Pacifico, Inter, Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>

    <!-- Swiper CSS (if used globally, otherwise include per page) -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"> -->

    <!-- Tailwind CSS (ensure this is how you're including it - CDN or local build) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Custom Stylesheet (should be loaded after Tailwind if overriding) -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
    
    <!-- Any other global CSS or JS specific to head -->

    <script>
        // Theme switcher logic (can be expanded)
        function toggleTheme() {
            const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.classList.remove(currentTheme);
            document.documentElement.classList.add(newTheme);
            document.cookie = "fitlife_theme=" + newTheme + ";path=/;max-age=" + (60*60*24*365); // Store for 1 year
        }

        // Language switcher logic (can be expanded)
        function changeLanguage(lang) {
            // This would typically involve a redirect or AJAX call to set the language server-side
            // For now, just an alert and potential cookie setting for client-side hints
            alert("Language change to " + lang + " requested. Server-side implementation needed.");
            document.cookie = "fitlife_language_pref=" + lang + ";path=/;max-age=" + (60*60*24*365);
            // window.location.search = '?lang=' + lang; // Example redirect
        }
        
        // Set initial theme based on cookie or system preference
        // This script should be placed early to avoid FOUC (Flash of Unstyled Content)
        (function() {
            const themeCookie = document.cookie.split('; ').find(row => row.startsWith('fitlife_theme='));
            const preferredTheme = themeCookie ? themeCookie.split('=')[1] : null;
            if (preferredTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (preferredTheme === 'light') {
                document.documentElement.classList.remove('dark'); // Ensure dark is removed if light is set
            } else {
                // Fallback to system preference if no cookie
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>
</head>
<body class="font-inter bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">

    <!-- Header -->
    <?php include BASE_PATH . '/resources/views/includes/header.php'; ?>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <?php
        // This is where the specific page content will be injected.
        // The controller should prepare a $viewContentFile variable
        // or the View::render method should handle including the specific view.
        // For simplicity, let's assume a $viewFile variable is passed to this layout.
        if (isset($viewFile) && file_exists(BASE_PATH . '/resources/views/' . $viewFile . '.php')) {
            include BASE_PATH . '/resources/views/' . $viewFile . '.php';
        } elseif (isset($content)) {
            // If $content is directly passed (e.g., from ob_start in View class)
            echo $content;
        } else {
            echo "<p>Page content could not be loaded.</p>";
        }
        ?>
    </main>

    <!-- Footer -->
    <?php include BASE_PATH . '/resources/views/includes/footer.php'; ?>

    <!-- Swiper JS (if used globally) -->
    <!-- <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script> -->
    
    <!-- Echarts JS (if used globally, otherwise include per page) -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.0/dist/echarts.min.js"></script> -->

    <!-- Custom Global JS -->
    <script src="<?php echo base_url('assets/js/script.js'); ?>"></script>

    <!-- Page-specific JS can be pushed here using a stack system if needed -->
    <?php if (isset($scripts) && is_array($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="<?php echo htmlspecialchars($script, ENT_QUOTES, 'UTF-8'); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>
