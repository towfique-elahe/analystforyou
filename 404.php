<?php get_header(); ?>

<div id="auth">
    <div class="container row">
        <div class="col">
            <img class="auth-banner"
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/404.svg'); ?>"
                alt="404 Illustration">
        </div>
        <div class="col">
            <div class="auth-form">
                <h3 class="heading">Oops! Page Not Found</h3>
                <p class="sub-heading">The page you’re looking for doesn’t exist or has been moved.</p>

                <div class="links">
                    <p><a href="<?php echo esc_url(site_url('/')); ?>">Go to Homepage</a></p>
                    <p>Need help? <a href="<?php echo esc_url(site_url('/contact')); ?>">Contact Support</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>