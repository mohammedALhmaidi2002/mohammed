<?php
// This view should be rendered by AuthController::showLoginForm()
// It will be wrapped by the main layout: resources/views/layouts/app.php

// Retrieve session instance for flash messages and old input
// In a real setup, a View class or a service container might provide session access more elegantly.
if (session_status() == PHP_SESSION_NONE) { session_start(); } // Ensure session is started
$session = new \App\Core\Session(); // Quick instantiation

$error_message = $session->getFlash('error_message');
$success_message = $session->getFlash('success_message');
$old_input = $session->getFlash('_old_input') ?? [];
$session->remove('_old_input'); // Clean up old input after retrieving

// For translations, assuming __() is available (e.g., loaded in header or layout)
if (!function_exists('__')) {
    function __($key, $default = null) { return $default ?? $key; } // Basic fallback
}
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-10 rounded-xl shadow-lg">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                <?php echo __('auth.login_title', 'Sign in to your account'); ?>
            </h2>
        </div>

        <?php if ($error_message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold"><?php echo __('general.error', 'Error!'); ?></strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold"><?php echo __('general.success', 'Success!'); ?></strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="<?php echo base_url('login'); // Route to AuthController@handleLogin ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php // echo csrf_token(); // Placeholder for CSRF token ?>">
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only"><?php echo __('labels.email', 'Email address'); ?></label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-t-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo __('labels.email', 'Email address'); ?>"
                           value="<?php echo htmlspecialchars($old_input['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div>
                    <label for="password" class="sr-only"><?php echo __('labels.password', 'Password'); ?></label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-b-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo __('labels.password', 'Password'); ?>">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:focus:ring-offset-gray-800">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                        <?php echo __('auth.remember_me', 'Remember me'); ?>
                    </label>
                </div>

                <div class="text-sm">
                    <a href="<?php echo base_url('forgot-password'); // Placeholder for forgot password route ?>" class="font-medium text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300">
                        <?php echo __('auth.forgot_password', 'Forgot your password?'); ?>
                    </a>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="ri-lock-line h-5 w-5 text-green-500 group-hover:text-green-400"></i>
                    </span>
                    <?php echo __('auth.sign_in_button', 'Sign in'); ?>
                </button>
            </div>
        </form>

        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
            <?php echo __('auth.no_account_yet', 'Don\'t have an account?'); ?>
            <a href="<?php echo base_url('register'); ?>" class="font-medium text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300">
                <?php echo __('auth.register_now', 'Register here'); ?>
            </a>
        </p>
    </div>
</div>
