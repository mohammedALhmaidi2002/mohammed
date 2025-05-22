<footer class="bg-gray-800 dark:bg-gray-900 text-white transition-colors duration-300">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-pacifico text-green-400 mb-3">FitLife</h3>
                <p class="text-gray-400 text-sm mb-3">
                    <?php echo __('footer.tagline', 'Your journey to a healthier lifestyle starts here. We provide the tools and knowledge to help you achieve your fitness goals.'); ?>
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-green-400"><i class="ri-facebook-circle-fill text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-green-400"><i class="ri-instagram-fill text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-green-400"><i class="ri-twitter-fill text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-green-400"><i class="ri-youtube-fill text-xl"></i></a>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-semibold text-gray-200 mb-3"><?php echo __('footer.quick_links', 'Quick Links'); ?></h4>
                <ul class="space-y-2">
                    <li><a href="<?php echo base_url('index.php?page=about'); // Replace later ?>" class="text-gray-400 hover:text-green-400 text-sm"><?php echo __('navigation.about', 'About Us'); ?></a></li>
                    <li><a href="<?php echo base_url('index.php?page=contact'); // Replace later ?>" class="text-gray-400 hover:text-green-400 text-sm"><?php echo __('navigation.contact', 'Contact Us'); ?></a></li>
                    <li><a href="<?php echo base_url('index.php?page=faq'); // Replace later ?>" class="text-gray-400 hover:text-green-400 text-sm"><?php echo __('footer.faq', 'FAQ'); ?></a></li>
                    <li><a href="<?php echo base_url('index.php?page=terms'); // Replace later ?>" class="text-gray-400 hover:text-green-400 text-sm"><?php echo __('footer.terms', 'Terms of Service'); ?></a></li>
                    <li><a href="<?php echo base_url('index.php?page=privacy'); // Replace later ?>" class="text-gray-400 hover:text-green-400 text-sm"><?php echo __('footer.privacy', 'Privacy Policy'); ?></a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold text-gray-200 mb-3"><?php echo __('footer.newsletter_title', 'Stay Updated'); ?></h4>
                <p class="text-gray-400 text-sm mb-3">
                    <?php echo __('footer.newsletter_text', 'Subscribe to our newsletter for the latest updates on fitness tips, diet plans, and new features.'); ?>
                </p>
                <form action="#" method="POST" class="flex">
                    <input type="email" name="email" placeholder="<?php echo __('footer.email_placeholder', 'Enter your email'); ?>" 
                           class="w-full px-3 py-2 rounded-l-md text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-r-md font-semibold">
                        <?php echo __('footer.subscribe_button', 'Subscribe'); ?>
                    </button>
                </form>
            </div>
        </div>

        <hr class="my-6 border-gray-700">

        <div class="text-center text-gray-500 text-sm">
            &copy; <?php echo date('Y'); ?> FitLife. <?php echo __('footer.all_rights_reserved', 'All Rights Reserved.'); ?>
        </div>
    </div>
</footer>
