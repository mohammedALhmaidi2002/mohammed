<?php
// Start session if not already started (though Session class should handle this)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Example: Get current language from session or config
$current_lang = $_SESSION['fitlife_language'] ?? 'en'; // Default to 'en'
$config_localization_path = BASE_PATH . '/config/localization.php';
$supported_languages = [];
if (file_exists($config_localization_path)) {
    $config_localization = require $config_localization_path;
    $supported_languages = $config_localization['supported_languages'];
}


// Load translations (simplified example, a Language class would be better)
$lang_file_path = BASE_PATH . "/lang/{$current_lang}.json";
$translations = [];
if (file_exists($lang_file_path)) {
    $translations = json_decode(file_get_contents($lang_file_path), true);
    if ($translations === null) { // Handle JSON decode error
        $translations = [];
        error_log("Error decoding JSON from language file: " . $lang_file_path);
    }
}

if (!function_exists('__')) {
    function __($key, $default = null) {
        global $translations; // Use the global $translations variable
        // Basic nested key access, e.g., "navigation.home"
        $keys = explode('.', $key);
        $value = $translations;
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                // Fallback: if key not found, return default or a formatted key
                return $default ?? str_replace(['.', '_'], ' ', ucfirst(basename($key)));
            }
        }
        return is_string($value) ? $value : ($default ?? str_replace(['.', '_'], ' ', ucfirst(basename($key))));
    }
}
?>
<header class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50 transition-colors duration-300">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <a href="<?php echo base_url(); ?>" class="text-2xl font-pacifico text-green-600 dark:text-green-400">
            FitLife
        </a>

        <nav class="hidden md:flex space-x-4 items-center">
            <a href="<?php echo base_url(); ?>" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium"><?php echo __('navigation.home', 'Home'); ?></a>
            <a href="<?php echo base_url('index.php?page=exercises'); // Replace with actual route later ?>" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium"><?php echo __('navigation.exercises', 'Exercises'); ?></a>
            <a href="<?php echo base_url('index.php?page=diets'); // Replace with actual route later ?>" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium"><?php echo __('navigation.diet_plans', 'Diet Plans'); ?></a>
            <a href="<?php echo base_url('index.php?page=calculators'); // Replace with actual route later ?>" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium"><?php echo __('navigation.calculators', 'Calculators'); ?></a>
            <a href="<?php echo base_url('index.php?page=about'); // Replace with actual route later ?>" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium"><?php echo __('navigation.about', 'About'); ?></a>
            <a href="<?php echo base_url('index.php?page=contact'); // Replace with actual route later ?>" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium"><?php echo __('navigation.contact', 'Contact'); ?></a>

            <?php if (isset($_SESSION['user_auth_id'])): ?>
                <a href="<?php echo base_url('index.php?page=dashboard'); // Replace with actual route later ?>" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium"><?php echo __('navigation.dashboard', 'Dashboard'); ?></a>
                <a href="<?php echo base_url('index.php?page=logout'); // Replace with actual route later ?>" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium"><?php echo __('navigation.logout', 'Logout'); ?></a>
            <?php else: ?>
                <a href="<?php echo base_url('index.php?page=login'); // Replace with actual route later ?>" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium"><?php echo __('navigation.login', 'Login'); ?></a>
                <a href="<?php echo base_url('index.php?page=register'); // Replace with actual route later ?>" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-md text-sm font-medium"><?php echo __('navigation.register', 'Register'); ?></a>
            <?php endif; ?>
        </nav>
        
        <div class="flex items-center space-x-2">
            <!-- Theme Toggle -->
            <button onclick="toggleTheme()" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 focus:outline-none">
                <i class="ri-sun-line dark:hidden"></i>
                <i class="ri-moon-line hidden dark:inline"></i>
            </button>

            <!-- Language Switcher -->
            <div class="relative">
                <button id="langSwitcherBtn" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 focus:outline-none flex items-center">
                    <i class="ri-global-line mr-1"></i>
                    <span><?php echo htmlspecialchars(strtoupper($current_lang), ENT_QUOTES, 'UTF-8'); ?></span>
                </button>
                <div id="langDropdown" class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 hidden z-50">
                    <?php foreach ($supported_languages as $code => $lang): ?>
                    <a href="?lang=<?php echo htmlspecialchars($code, ENT_QUOTES, 'UTF-8'); // This should ideally POST to a controller to change lang ?>" 
                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <?php echo htmlspecialchars($lang['native_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="md:hidden text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 focus:outline-none">
                <i class="ri-menu-line text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu (hidden by default) -->
    <div id="mobileMenu" class="md:hidden hidden bg-white dark:bg-gray-800 shadow-lg">
        <a href="<?php echo base_url(); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><?php echo __('navigation.home', 'Home'); ?></a>
        <a href="<?php echo base_url('index.php?page=exercises'); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><?php echo __('navigation.exercises', 'Exercises'); ?></a>
        <a href="<?php echo base_url('index.php?page=diets'); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><?php echo __('navigation.diet_plans', 'Diet Plans'); ?></a>
        <a href="<?php echo base_url('index.php?page=calculators'); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><?php echo __('navigation.calculators', 'Calculators'); ?></a>
        <a href="<?php echo base_url('index.php?page=about'); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><?php echo __('navigation.about', 'About'); ?></a>
        <a href="<?php echo base_url('index.php?page=contact'); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><?php echo __('navigation.contact', 'Contact'); ?></a>
        <?php if (isset($_SESSION['user_auth_id'])): ?>
            <a href="<?php echo base_url('index.php?page=dashboard'); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><?php echo __('navigation.dashboard', 'Dashboard'); ?></a>
            <a href="<?php echo base_url('index.php?page=logout'); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><?php echo __('navigation.logout', 'Logout'); ?></a>
        <?php else: ?>
            <a href="<?php echo base_url('index.php?page=login'); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><?php echo __('navigation.login', 'Login'); ?></a>
            <a href="<?php echo base_url('index.php?page=register'); ?>" class="block px-4 py-2 text-sm text-green-500 hover:bg-gray-100 dark:hover:bg-gray-700"><?php echo __('navigation.register', 'Register'); ?></a>
        <?php endif; ?>
    </div>
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Language switcher dropdown toggle
        const langSwitcherBtn = document.getElementById('langSwitcherBtn');
        const langDropdown = document.getElementById('langDropdown');
        if(langSwitcherBtn && langDropdown) {
            langSwitcherBtn.addEventListener('click', (event) => {
                langDropdown.classList.toggle('hidden');
                event.stopPropagation();
            });
            document.addEventListener('click', (event) => {
                if (!langDropdown.contains(event.target) && !langSwitcherBtn.contains(event.target)) {
                    langDropdown.classList.add('hidden');
                }
            });
        }
    </script>
</header>
