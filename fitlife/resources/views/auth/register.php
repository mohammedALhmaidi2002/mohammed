<?php
// This view should be rendered by AuthController::showRegisterForm()
// It will be wrapped by the main layout: resources/views/layouts/app.php

if (session_status() == PHP_SESSION_NONE) { session_start(); }
$session = new \App\Core\Session(); // Quick instantiation

$error_messages = $session->getFlash('error_messages'); // This might be an array of errors
$error_message_single = $session->getFlash('error_message'); // For a single generic error
$success_message = $session->getFlash('success_message');
$old_input = $session->getFlash('_old_input') ?? [];
$session->remove('_old_input');

if (!function_exists('__')) {
    function __($key, $default = null) { return $default ?? $key; }
}

function display_error($field, $errors_array) {
    if (isset($errors_array[$field])) {
        echo '<p class="text-red-500 text-xs italic mt-1">' . htmlspecialchars($errors_array[$field], ENT_QUOTES, 'UTF-8') . '</p>';
    }
}
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-10 rounded-xl shadow-lg">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                <?php echo __('auth.register_title', 'Create your account'); ?>
            </h2>
        </div>

        <?php if ($error_message_single): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error_message_single, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        <?php endif; ?>
        <?php if ($success_message): ?>
             <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="<?php echo base_url('register'); // Route to AuthController@handleRegister ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php // echo csrf_token(); // Placeholder for CSRF token ?>">
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo __('labels.name', 'Full Name'); ?></label>
                <input id="name" name="name" type="text" autocomplete="name" required
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border <?php echo isset($error_messages['name']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                       placeholder="<?php echo __('labels.name', 'Full Name'); ?>"
                       value="<?php echo htmlspecialchars($old_input['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <?php display_error('name', $error_messages); ?>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo __('labels.email', 'Email address'); ?></label>
                <input id="email" name="email" type="email" autocomplete="email" required
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border <?php echo isset($error_messages['email']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                       placeholder="<?php echo __('labels.email', 'Email address'); ?>"
                       value="<?php echo htmlspecialchars($old_input['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <?php display_error('email', $error_messages); ?>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo __('labels.password', 'Password'); ?></label>
                <input id="password" name="password" type="password" autocomplete="new-password" required
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border <?php echo (isset($error_messages['password']) || isset($error_messages['password_length'])) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                       placeholder="<?php echo __('labels.password', 'Password'); ?>">
                <?php display_error('password', $error_messages); ?>
                <?php display_error('password_length', $error_messages); ?>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo __('auth.confirm_password', 'Confirm Password'); ?></label>
                <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" required
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border <?php echo isset($error_messages['confirm_password']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                       placeholder="<?php echo __('auth.confirm_password', 'Confirm Password'); ?>">
                <?php display_error('confirm_password', $error_messages); ?>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo __('labels.age', 'Age'); ?></label>
                    <input id="age" name="age" type="number"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border <?php echo isset($error_messages['age']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                           placeholder="<?php echo __('labels.age_optional', 'Age (Optional)'); ?>"
                           value="<?php echo htmlspecialchars($old_input['age'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php display_error('age', $error_messages); ?>
                </div>
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo __('labels.gender', 'Gender'); ?></label>
                    <select id="gender" name="gender"
                            class="mt-1 block w-full py-2 px-3 border <?php echo isset($error_messages['gender']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        <option value=""><?php echo __('labels.select_gender_optional', 'Select Gender (Optional)'); ?></option>
                        <option value="male" <?php echo (isset($old_input['gender']) && $old_input['gender'] === 'male') ? 'selected' : ''; ?>><?php echo __('labels.male', 'Male'); ?></option>
                        <option value="female" <?php echo (isset($old_input['gender']) && $old_input['gender'] === 'female') ? 'selected' : ''; ?>><?php echo __('labels.female', 'Female'); ?></option>
                        <option value="other" <?php echo (isset($old_input['gender']) && $old_input['gender'] === 'other') ? 'selected' : ''; ?>><?php echo __('labels.other', 'Other'); ?></option>
                        <option value="prefer_not_to_say" <?php echo (isset($old_input['gender']) && $old_input['gender'] === 'prefer_not_to_say') ? 'selected' : ''; ?>><?php echo __('labels.prefer_not_to_say', 'Prefer not to say'); ?></option>
                    </select>
                    <?php display_error('gender', $error_messages); ?>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800">
                    <?php echo __('auth.register_button', 'Create Account'); ?>
                </button>
            </div>
        </form>

        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
            <?php echo __('auth.already_have_account', 'Already have an account?'); ?>
            <a href="<?php echo base_url('login'); ?>" class="font-medium text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300">
                <?php echo __('auth.login_now', 'Sign in here'); ?>
            </a>
        </p>
    </div>
</div>
