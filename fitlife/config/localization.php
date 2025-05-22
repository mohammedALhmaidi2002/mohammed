<?php

// Localization Configuration

return [
    // Default application language
    // Should match one of the keys in 'supported_languages'
    'default_language' => 'en',

    // Supported languages for the application
    // Key is the language code (e.g., 'en', 'ar')
    // Value can be an array of properties (e.g., name, direction)
    'supported_languages' => [
        'en' => [
            'name' => 'English',
            'native_name' => 'English',
            'direction' => 'ltr', // Left-to-Right
            'regional_locale' => 'en_US', // For things like date formatting, number formatting
        ],
        'ar' => [
            'name' => 'Arabic',
            'native_name' => 'العربية',
            'direction' => 'rtl', // Right-to-Left
            'regional_locale' => 'ar_SA', // Example regional locale for Arabic
        ],
        // Add more languages here if needed
    ],

    // Session key to store the user's selected language
    'session_key' => 'fitlife_language',

    // Cookie name to store the user's selected language (optional)
    'cookie_name' => 'fitlife_language_preference',
    'cookie_duration' => 60 * 60 * 24 * 365, // 1 year in seconds

    // Where to find translation files (JSON files named like 'en.json', 'ar.json')
    'translation_files_path' => BASE_PATH . '/lang',
];

?>
